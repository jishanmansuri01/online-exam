<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Exam $exam)
    {
        $questions = $exam->questions()->with('options')->get();
        return view('admin.questions.index', compact('exam', 'questions'));
    }

    public function create(Exam $exam)
    {
        return view('admin.questions.create', compact('exam'));
    }

    public function store(Request $request, Exam $exam)
    {
        $rules = [
            'question_text' => 'required|string',
            'question_type' => 'required|in:mcq,short_answer,programming',
            'marks'         => 'required|integer|min:1',
        ];

        if ($request->question_type === 'mcq') {
            $rules['options']        = 'required|array|min:2';
            $rules['options.*']      = 'required|string';
            $rules['correct_option'] = 'required';
        }

        $request->validate($rules);

        $question = $exam->questions()->create([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'marks'         => $request->marks,
        ]);

        if ($request->question_type === 'mcq') {
            foreach ($request->options as $index => $optionText) {
                if (!empty($optionText)) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'is_correct'  => ($request->correct_option == $index) ? true : false,
                    ]);
                }
            }
        }

        return redirect()->route('admin.questions.index', $exam)
            ->with('success', 'Question added successfully!');
    }

    public function edit(Question $question)
    {
        $exam = $question->exam;
        return view('admin.questions.edit', compact('question', 'exam'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'marks'         => 'required|integer|min:1',
            'options'       => 'required_if:question_type,mcq|array',
            'options.*'     => 'required_if:question_type,mcq|string',
            'correct_option' => 'required_if:question_type,mcq',
        ]);

        $question->update([
            'question_text' => $request->question_text,
            'marks'         => $request->marks,
        ]);

        if ($question->question_type === 'mcq') {
            $question->options()->delete();
            foreach ($request->options as $index => $optionText) {
                if (!empty($optionText)) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'is_correct'  => ($request->correct_option == $index) ? true : false,
                    ]);
                }
            }
        }

        return redirect()->route('admin.questions.index', $question->exam)
            ->with('success', 'Question updated successfully!');
    }

    public function destroy(Question $question)
    {
        $exam = $question->exam;
        $question->delete();

        return redirect()->route('admin.questions.index', $exam)
            ->with('success', 'Question deleted successfully!');
    }
}
