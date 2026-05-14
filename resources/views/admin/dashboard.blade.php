@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card text-white" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            <div class="card-body d-flex justify-content-between align-items-center p-4">
                <div>
                    <div class="fs-2 fw-bold">{{ $totalExams }}</div>
                    <div class="opacity-75">Total Exams</div>
                </div>
                <i class="bi bi-journal-text fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
            <div class="card-body d-flex justify-content-between align-items-center p-4">
                <div>
                    <div class="fs-2 fw-bold">{{ $totalStudents }}</div>
                    <div class="opacity-75">Total Students</div>
                </div>
                <i class="bi bi-people fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
            <div class="card-body d-flex justify-content-between align-items-center p-4">
                <div>
                    <div class="fs-2 fw-bold">{{ $totalResults }}</div>
                    <div class="opacity-75">Total Results</div>
                </div>
                <i class="bi bi-bar-chart-line fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <h6 class="fw-semibold mb-3">Quick Actions</h6>
        <a href="{{ route('admin.exams.create') }}" class="btn btn-primary me-2">
            <i class="bi bi-plus-circle me-1"></i> Create New Exam
        </a>
        <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-eye me-1"></i> View All Results
        </a>
    </div>
</div>
@endsection