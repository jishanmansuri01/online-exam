@extends('layouts.student')

@section('title', 'Start Exam')
@section('page-title', 'Exam Instructions')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-journal-text me-2"></i>{{ $exam->title }}
                </h5>
            </div>
            <div class="card-body p-4">

                @if($exam->description)
                <p class="text-muted mb-4">{{ $exam->description }}</p>
                @endif

                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="bi bi-clock fs-4 text-primary"></i>
                            <div class="fw-bold mt-1">{{ $exam->duration }} min</div>
                            <div class="text-muted small">Duration</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="bi bi-patch-check fs-4 text-success"></i>
                            <div class="fw-bold mt-1">{{ $exam->total_marks }}</div>
                            <div class="text-muted small">Total Marks</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="bi bi-trophy fs-4 text-warning"></i>
                            <div class="fw-bold mt-1">{{ $exam->pass_marks }}</div>
                            <div class="text-muted small">Pass Marks</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="bi bi-question-circle fs-4 text-info"></i>
                            <div class="fw-bold mt-1">{{ $exam->questions->count() }}</div>
                            <div class="text-muted small">Questions</div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <h6 class="fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Instructions</h6>
                    <ul class="mb-0 ps-3">
                        <li>Once started, the timer cannot be paused.</li>
                        <li>The exam will auto-submit when time runs out.</li>
                        <li>You can only attempt this exam <strong>once</strong>.</li>
                        <li>Make sure you have a stable internet connection.</li>
                        <li>Do not refresh or close the browser during the exam.</li>
                    </ul>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <a href="{{ route('student.exams.take', $exam) }}"
                       class="btn btn-success btn-lg px-5">
                        <i class="bi bi-play-fill me-2"></i> Start Now
                    </a>
                    <a href="{{ route('student.exams.index') }}"
                       class="btn btn-outline-secondary btn-lg">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection