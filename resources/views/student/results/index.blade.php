@extends('layouts.student')

@section('title', 'My Results')
@section('page-title', 'My Results')

@section('content')
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Exam</th>
                    <th>Total Marks</th>
                    <th>Obtained</th>
                    <th>Percentage</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $result)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $result->exam->title }}</td>
                    <td>{{ $result->total_marks }}</td>
                    <td>{{ $result->obtained_marks }}</td>
                    <td>{{ $result->percentage }}%</td>
                    <td>
                        @if($result->status === 'pass')
                        <span class="badge bg-success">Pass</span>
                        @elseif($result->status === 'fail')
                        <span class="badge bg-danger">Fail</span>
                        @else
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-hourglass-split me-1"></i>Pending Review
                        </span>
                        @endif
                    </td>
                    <td>{{ $result->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('student.results.download', $result) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download me-1"></i> Download
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        No results yet.
                        <a href="{{ route('student.exams.index') }}">Take an exam!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection