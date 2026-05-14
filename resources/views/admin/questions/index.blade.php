@extends('layouts.admin')

@section('title', 'Questions')
@section('page-title', 'Manage Questions')

@section('content')
<div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
        <a href="{{ route('admin.exams.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Exams
        </a>
        <span class="ms-2 text-muted">Exam: <strong>{{ $exam->title }}</strong></span>
    </div>
    <a href="{{ route('admin.questions.create', $exam) }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Add Question
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Type</th>
                    <th>Marks</th>
                    <th>Options</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $question)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ Str::limit($question->question_text, 60) }}</td>
                    <td>
                        <span class="badge {{ $question->question_type == 'mcq' ? 'bg-info' : 'bg-warning text-dark' }}">
                            {{ strtoupper($question->question_type) }}
                        </span>
                    </td>
                    <td>{{ $question->marks }}</td>
                    <td>
                        @if($question->question_type === 'mcq')
                            @foreach($question->options as $option)
                                <span class="badge {{ $option->is_correct ? 'bg-success' : 'bg-light text-dark border' }} me-1">
                                    {{ $option->option_text }}
                                    @if($option->is_correct) <i class="bi bi-check"></i> @endif
                                </span>
                            @endforeach
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.questions.edit', $question) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.questions.destroy', $question) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this question?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No questions yet. <a href="{{ route('admin.questions.create', $exam) }}">Add one!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection