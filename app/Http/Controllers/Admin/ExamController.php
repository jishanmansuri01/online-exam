<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::withCount('questions')->latest()->get();
        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        return view('admin.exams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration'    => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'pass_marks'  => 'required|integer|min:1',
            'status'      => 'required|in:active,inactive',
        ]);

        Exam::create($request->all());

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam created successfully!');
    }

    public function edit(Exam $exam)
    {
        return view('admin.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration'    => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'pass_marks'  => 'required|integer|min:1',
            'status'      => 'required|in:active,inactive',
        ]);

        $exam->update($request->all());

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam updated successfully!');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam deleted successfully!');
    }

    public function show(Exam $exam)
    {
        return redirect()->route('admin.questions.index', $exam);
    }
}
