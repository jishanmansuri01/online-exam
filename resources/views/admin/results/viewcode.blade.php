@extends('layouts.admin')

@section('title', 'View Code')
@section('page-title', 'Submitted Code')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">
<style>
    .CodeMirror { height: auto; min-height: 150px; border-radius: 8px; }
    .output-box {
        background: #1e1e1e; color: #00ff00;
        border-radius: 8px; padding: 15px;
        font-family: monospace; font-size: 13px;
        white-space: pre-wrap;
    }
</style>
@endpush

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.results.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Results
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h6 class="fw-semibold">Student: {{ $result->user->name }}</h6>
        <p class="text-muted mb-0">Exam: {{ $result->exam->title }}</p>
    </div>
</div>

@forelse($programmingAnswers as $index => $answer)
<div class="card mb-4">
    <div class="card-header bg-white d-flex justify-content-between">
        <span class="fw-semibold">Q{{ $index + 1 }}. {{ $answer->question->question_text }}</span>
        <span class="badge bg-warning text-dark">{{ $answer->language ?? 'N/A' }}</span>
    </div>
    <div class="card-body">
        <label class="form-label fw-semibold">Submitted Code:</label>
        <textarea class="code-viewer">{{ $answer->code_answer }}</textarea>

        @if($answer->code_output)
        <label class="form-label fw-semibold mt-3">Output:</label>
        <div class="output-box">{{ $answer->code_output }}</div>
        @endif
    </div>
</div>
@empty
<div class="card">
    <div class="card-body text-center text-muted py-4">
        No programming answers submitted.
    </div>
</div>
@endforelse
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/python/python.min.js"></script>
<script>
document.querySelectorAll('.code-viewer').forEach(function(textarea) {
    CodeMirror.fromTextArea(textarea, {
        lineNumbers: true,
        theme: 'dracula',
        readOnly: true,
        lineWrapping: true,
    });
});
</script>
@endpush