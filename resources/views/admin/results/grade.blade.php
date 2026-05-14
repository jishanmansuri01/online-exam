@extends('layouts.admin')

@section('title', 'Grade Result')
@section('page-title', 'Grade Student Answers')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.results.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Results
    </a>
</div>

<!-- Student Info -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="text-muted small">Student</div>
                <div class="fw-bold">{{ $result->user->name }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Exam</div>
                <div class="fw-bold">{{ $result->exam->title }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">MCQ Marks</div>
                <div class="fw-bold text-primary">{{ $result->obtained_marks }} / {{ $result->total_marks }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Pass Marks</div>
                <div class="fw-bold text-warning">{{ $result->exam->pass_marks }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Student Answers -->
@foreach($manualAnswers as $index => $answer)
<div class="card mb-3">
    <div class="card-header bg-white d-flex justify-content-between">
        <span class="fw-semibold">Q{{ $index + 1 }}. {{ $answer->question->question_text }}</span>
        <div>
            <span class="badge {{ $answer->question->question_type == 'programming' ? 'bg-warning text-dark' : 'bg-secondary' }}">
                {{ strtoupper($answer->question->question_type) }}
            </span>
            <span class="badge bg-dark ms-1">{{ $answer->question->marks }} mark(s)</span>
        </div>
    </div>
    <div class="card-body">
        @if($answer->question->question_type === 'short_answer')
            <label class="form-label fw-semibold text-muted small">Student Answer:</label>
            <div class="bg-light p-3 rounded">
                {{ $answer->answer_text ?? 'No answer provided' }}
            </div>
        @else
            <label class="form-label fw-semibold text-muted small">Submitted Code ({{ $answer->language ?? 'N/A' }}):</label>
            <pre class="bg-dark text-success p-3 rounded" style="font-size:13px;">{{ $answer->code_answer ?? 'No code submitted' }}</pre>
            @if($answer->code_output)
                <label class="form-label fw-semibold text-muted small mt-2">Output:</label>
                <pre class="bg-dark text-info p-3 rounded" style="font-size:13px;">{{ $answer->code_output }}</pre>
            @endif
        @endif
    </div>
</div>
@endforeach

<!-- Grading Form -->
<div class="card border-warning">
    <div class="card-header bg-warning text-dark fw-semibold">
        <i class="bi bi-pencil-square me-2"></i> Give Marks for Short Answer & Programming
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.results.saveGrade', $result) }}" method="POST">
            @csrf

            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        Manual Marks
                        <span class="text-muted small">
                            (Max: {{ $result->total_marks - $result->obtained_marks }})
                        </span>
                    </label>
                    <input type="number"
                           name="manual_marks"
                           class="form-control @error('manual_marks') is-invalid @enderror"
                           min="0"
                           max="{{ $result->total_marks - $result->obtained_marks }}"
                           value="{{ old('manual_marks', 0) }}"
                           placeholder="Enter marks...">
                    @error('manual_marks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">MCQ Marks Already</label>
                    <input type="text" class="form-control bg-light"
                           value="{{ $result->obtained_marks }} marks" readonly>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-circle me-1"></i> Save & Calculate Result
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection