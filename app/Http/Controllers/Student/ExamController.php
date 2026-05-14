<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\StudentExamAttempt;
use App\Models\StudentAnswer;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::where('status', 'active')
            ->withCount('questions')
            ->get();

        $attemptedExamIds = StudentExamAttempt::where('user_id', Auth::id())
            ->where('status', 'submitted')
            ->pluck('exam_id')
            ->toArray();

        return view('student.exams.index', compact('exams', 'attemptedExamIds'));
    }
    public function compile(Request $request)
    {
        $code     = $request->code;
        $language = $request->language;

        $jdoodleLanguages = [
            'c'      => ['language' => 'c',       'versionIndex' => '5'],
            'cpp'    => ['language' => 'cpp17',    'versionIndex' => '1'],
            'python' => ['language' => 'python3',  'versionIndex' => '4'],
            'java'   => ['language' => 'java',     'versionIndex' => '4'],
        ];

        $langConfig = $jdoodleLanguages[$language] ?? $jdoodleLanguages['python'];

        try {
            $response = Http::post('https://api.jdoodle.com/v1/execute', [
                'clientId'     => 'b48fe9e1c1023ef62b082420997e869b',      // 👈 replace this
                'clientSecret' => 'f89b81c027fac4291b21d5388c1783139294ca02d00cd6830929e6a45e37f0c8',  // 👈 replace this
                'script'       => $code,
                'language'     => $langConfig['language'],
                'versionIndex' => $langConfig['versionIndex'],
                'stdin'        => '',
            ]);

            $result = $response->json();

            return response()->json([
                'stdout' => $result['output'] ?? '',
                'stderr' => $result['error']  ?? '',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'stderr' => 'Compiler error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function start(Exam $exam)
    {
        $alreadyAttempted = StudentExamAttempt::where('user_id', Auth::id())
            ->where('exam_id', $exam->id)
            ->where('status', 'submitted')
            ->exists();

        if ($alreadyAttempted) {
            return redirect()->route('student.exams.index')
                ->with('error', 'You have already attempted this exam!');
        }

        return view('student.exams.start', compact('exam'));
    }

    public function take(Exam $exam)
    {
        $alreadyAttempted = StudentExamAttempt::where('user_id', Auth::id())
            ->where('exam_id', $exam->id)
            ->where('status', 'submitted')
            ->exists();

        if ($alreadyAttempted) {
            return redirect()->route('student.exams.index')
                ->with('error', 'You have already attempted this exam!');
        }

        $attempt = StudentExamAttempt::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'exam_id' => $exam->id,
                'status'  => 'in_progress',
            ],
            [
                'started_at' => now(),
            ]
        );

        $questions = $exam->questions()->with('options')->get();

        $timeLeft = $exam->duration * 60; // default full time

        if ($attempt->started_at) {
            $elapsed  = now()->diffInSeconds($attempt->started_at);
            $timeLeft = (int) max(0, ($exam->duration * 60) - $elapsed);
        }

        return view('student.exams.take', compact('exam', 'attempt', 'questions', 'timeLeft'));
    }

    public function submit(Request $request, Exam $exam)
    {
        $attempt = StudentExamAttempt::where('user_id', Auth::id())
            ->where('exam_id', $exam->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        // Save MCQ and short answers
        if ($request->answers) {
            foreach ($request->answers as $questionId => $answer) {
                $existing = StudentAnswer::where('attempt_id', $attempt->id)
                    ->where('question_id', $questionId)
                    ->first();

                $question = $exam->questions()->find($questionId);

                if ($question->question_type === 'mcq') {
                    if ($existing) {
                        $existing->update(['option_id' => $answer]);
                    } else {
                        StudentAnswer::create([
                            'attempt_id'  => $attempt->id,
                            'question_id' => $questionId,
                            'option_id'   => $answer,
                        ]);
                    }
                } else {
                    if ($existing) {
                        $existing->update(['answer_text' => $answer]);
                    } else {
                        StudentAnswer::create([
                            'attempt_id'  => $attempt->id,
                            'question_id' => $questionId,
                            'answer_text' => $answer,
                        ]);
                    }
                }
            }
        }

        // Save programming answers
        if ($request->code) {
            foreach ($request->code as $questionId => $code) {
                $existing = StudentAnswer::where('attempt_id', $attempt->id)
                    ->where('question_id', $questionId)
                    ->first();

                $output = $request->code_output[$questionId] ?? '';
                $lang   = $request->code_lang[$questionId] ?? 'c';

                if ($existing) {
                    $existing->update([
                        'code_answer' => $code,
                        'code_output' => $output,
                        'language'    => $lang,
                    ]);
                } else {
                    StudentAnswer::create([
                        'attempt_id'  => $attempt->id,
                        'question_id' => $questionId,
                        'code_answer' => $code,
                        'code_output' => $output,
                        'language'    => $lang,
                    ]);
                }
            }
        }

        // Calculate marks (MCQ auto graded)
        $obtainedMarks = 0;
        $hasManualQuestions = false;

        foreach ($exam->questions as $question) {
            if ($question->question_type === 'mcq') {
                $studentAnswer = StudentAnswer::where('attempt_id', $attempt->id)
                    ->where('question_id', $question->id)
                    ->first();
                if ($studentAnswer && $studentAnswer->option_id) {
                    $correctOption = $question->options()
                        ->where('is_correct', true)
                        ->first();
                    if ($correctOption && $studentAnswer->option_id == $correctOption->id) {
                        $obtainedMarks += $question->marks;
                    }
                }
            } else {
                // Short answer or programming — needs manual grading
                $hasManualQuestions = true;
            }
        }

        $percentage = ($exam->total_marks > 0)
            ? round(($obtainedMarks / $exam->total_marks) * 100, 2)
            : 0;

        // If has manual questions — mark as pending
        $status    = $hasManualQuestions ? 'pending' : ($obtainedMarks >= $exam->pass_marks ? 'pass' : 'fail');
        $isGraded  = $hasManualQuestions ? false : true;

        Result::create([
            'user_id'        => Auth::id(),
            'exam_id'        => $exam->id,
            'attempt_id'     => $attempt->id,
            'total_marks'    => $exam->total_marks,
            'obtained_marks' => $obtainedMarks,
            'percentage'     => $percentage,
            'status'         => $status,
            'is_graded'      => $isGraded,
            'manual_marks'   => 0,
        ]);

        $percentage = ($exam->total_marks > 0)
            ? round(($obtainedMarks / $exam->total_marks) * 100, 2)
            : 0;

        $status = $obtainedMarks >= $exam->pass_marks ? 'pass' : 'fail';

        Result::create([
            'user_id'        => Auth::id(),
            'exam_id'        => $exam->id,
            'attempt_id'     => $attempt->id,
            'total_marks'    => $exam->total_marks,
            'obtained_marks' => $obtainedMarks,
            'percentage'     => $percentage,
            'status'         => $status,
        ]);

        $attempt->update([
            'status'       => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.results.index')
            ->with('success', 'Exam submitted successfully!');
    }
}
