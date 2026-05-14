@extends('layouts.admin')

@section('title', 'Edit Question')
@section('page-title', 'Edit Question')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-3">
            <a href="{{ route('admin.questions.index', $exam) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Questions
            </a>
        </div>
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-semibold">Edit Question</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.questions.update', $question) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Question Text <span class="text-danger">*</span></label>
                        <textarea name="question_text" rows="3"
                                  class="form-control @error('question_text') is-invalid @enderror">{{ old('question_text', $question->question_text) }}</textarea>
                        @error('question_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Type</label>
                        <input type="text" class="form-control bg-light"
                               value="{{ strtoupper($question->question_type) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Marks <span class="text-danger">*</span></label>
                        <input type="number" name="marks"
                               class="form-control @error('marks') is-invalid @enderror"
                               value="{{ old('marks', $question->marks) }}" min="1">
                        @error('marks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($question->question_type === 'mcq')
                    <div id="mcq-options">
                        <label class="form-label fw-semibold">Options <span class="text-danger">*</span></label>
                        <p class="text-muted small">Select the radio button next to the correct answer.</p>
                        @foreach($question->options as $index => $option)
                        <div class="input-group mb-2">
                            <div class="input-group-text">
                                <input type="radio" name="correct_option" value="{{ $index }}"
                                       {{ $option->is_correct ? 'checked' : '' }}>
                            </div>
                            <input type="text" name="options[]"
                                   class="form-control"
                                   value="{{ old('options.'.$index, $option->option_text) }}">
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle me-1"></i> Update Question
                        </button>
                        <a href="{{ route('admin.questions.index', $exam) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection