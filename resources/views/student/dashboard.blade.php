@extends('layouts.student')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card text-white" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            <div class="card-body d-flex justify-content-between align-items-center p-4">
                <div>
                    <div class="fs-2 fw-bold">{{ $availableExams }}</div>
                    <div class="opacity-75">Available Exams</div>
                </div>
                <i class="bi bi-journal-text fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
            <div class="card-body d-flex justify-content-between align-items-center p-4">
                <div>
                    <div class="fs-2 fw-bold">{{ $myResults }}</div>
                    <div class="opacity-75">My Results</div>
                </div>
                <i class="bi bi-bar-chart-line fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <h6 class="fw-semibold mb-3">Quick Actions</h6>
        <a href="{{ route('student.exams.index') }}" class="btn btn-success me-2">
            <i class="bi bi-play-circle me-1"></i> Browse Exams
        </a>
        <a href="{{ route('student.results.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-eye me-1"></i> View My Results
        </a>
    </div>
</div>
@endsection