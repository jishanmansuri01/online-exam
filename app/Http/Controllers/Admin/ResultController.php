<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Exam;
use App\Models\StudentAnswer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::with(['user', 'exam'])
                         ->latest()
                         ->get();

        $exams = Exam::all();

        return view('admin.results.index', compact('results', 'exams'));
    }

    public function grade(Result $result)
    {
        $result->load('user', 'exam', 'attempt');

        $manualAnswers = StudentAnswer::where('attempt_id', $result->attempt->id)
                         ->whereHas('question', function($q) {
                             $q->whereIn('question_type', ['short_answer', 'programming']);
                         })
                         ->with('question')
                         ->get();

        return view('admin.results.grade', compact('result', 'manualAnswers'));
    }

    public function saveGrade(Request $request, Result $result)
    {
        $request->validate([
            'manual_marks' => 'required|integer|min:0|max:' . $result->total_marks,
        ]);

        $totalObtained = $result->obtained_marks + $request->manual_marks;
        $percentage    = round(($totalObtained / $result->total_marks) * 100, 2);
        $status        = $totalObtained >= $result->exam->pass_marks ? 'pass' : 'fail';

        $result->update([
            'manual_marks'   => $request->manual_marks,
            'obtained_marks' => $totalObtained,
            'percentage'     => $percentage,
            'status'         => $status,
            'is_graded'      => true,
        ]);

        return redirect()->route('admin.results.index')
                         ->with('success', 'Result graded successfully!');
    }

    public function download(Result $result)
    {
        $result->load('exam', 'user');

        $pdf = Pdf::loadView('student.results.download', compact('result'));

        return $pdf->download('result-' . $result->user->name . '-' . $result->exam->title . '.pdf');
    }

    public function viewCode(Result $result)
    {
        $result->load('user', 'exam');
        $attempt = $result->attempt;
        $programmingAnswers = StudentAnswer::where('attempt_id', $attempt->id)
                              ->whereNotNull('code_answer')
                              ->with('question')
                              ->get();

        return view('admin.results.viewcode', compact('result', 'programmingAnswers'));
    }
}