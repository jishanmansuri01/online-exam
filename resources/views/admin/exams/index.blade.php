@extends('layouts.admin')

@section('title', 'Exams')
@section('page-title', 'Manage Exams')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-semibold">All Exams</h6>
        <a href="{{ route('admin.exams.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Add New Exam
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Duration</th>
                    <th>Total Marks</th>
                    <th>Pass Marks</th>
                    <th>Questions</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $exam)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $exam->title }}</td>
                    <td>{{ $exam->duration }} mins</td>
                    <td>{{ $exam->total_marks }}</td>
                    <td>{{ $exam->pass_marks }}</td>
                    <td>
                        <a href="{{ route('admin.questions.index', $exam) }}" class="badge bg-primary text-decoration-none">
                            {{ $exam->questions_count }} Questions
                        </a>
                    </td>
                    <td>
                        @if($exam->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.questions.index', $exam) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-list-ul"></i>
                        </a>
                        <a href="{{ route('admin.exams.edit', $exam) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.exams.destroy', $exam) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this exam?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        No exams found. <a href="{{ route('admin.exams.create') }}">Create one!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection