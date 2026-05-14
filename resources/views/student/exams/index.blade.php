@extends('layouts.student')

@section('title', 'Available Exams')
@section('page-title', 'Available Exams')

@section('content')
<div class="row g-4">
    @forelse($exams as $exam)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="fw-bold mb-0">{{ $exam->title }}</h6>
                    @if(in_array($exam->id, $attemptedExamIds))
                        <span class="badge bg-success">Completed</span>
                    @else
                        <span class="badge bg-primary">Available</span>
                    @endif
                </div>

                @if($exam->description)
                    <p class="text-muted small mb-3">{{ $exam->description }}</p>
                @endif

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <div class="fw-bold text-primary">{{ $exam->duration }}</div>
                            <div class="text-muted" style="font-size:0.75rem">Minutes</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <div class="fw-bold text-success">{{ $exam->total_marks }}</div>
                            <div class="text-muted" style="font-size:0.75rem">Total Marks</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <div class="fw-bold text-warning">{{ $exam->pass_marks }}</div>
                            <div class="text-muted" style="font-size:0.75rem">Pass Marks</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <div class="fw-bold text-info">{{ $exam->questions_count }}</div>
                            <div class="text-muted" style="font-size:0.75rem">Questions</div>
                        </div>
                    </div>
                </div>

                @if(in_array($exam->id, $attemptedExamIds))
                    <a href="{{ route('student.results.index') }}"
                       class="btn btn-outline-success w-100">
                        <i class="bi bi-eye me-1"></i> View Result
                    </a>
                @else
                    <a href="{{ route('student.exams.start', $exam) }}"
                       class="btn btn-primary w-100">
                        <i class="bi bi-play-circle me-1"></i> Start Exam
                    </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-journal-x fs-1 text-muted"></i>
                <p class="text-muted mt-3">No exams available at the moment.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection