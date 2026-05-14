@extends('layouts.admin')

@section('title', 'Edit Exam')
@section('page-title', 'Edit Exam')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-semibold">Edit Exam Details</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.exams.update', $exam) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Exam Title <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $exam->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $exam->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Duration (minutes) <span class="text-danger">*</span></label>
                            <input type="number" name="duration"
                                   class="form-control @error('duration') is-invalid @enderror"
                                   value="{{ old('duration', $exam->duration) }}" min="1">
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Total Marks <span class="text-danger">*</span></label>
                            <input type="number" name="total_marks"
                                   class="form-control @error('total_marks') is-invalid @enderror"
                                   value="{{ old('total_marks', $exam->total_marks) }}" min="1">
                            @error('total_marks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Pass Marks <span class="text-danger">*</span></label>
                            <input type="number" name="pass_marks"
                                   class="form-control @error('pass_marks') is-invalid @enderror"
                                   value="{{ old('pass_marks', $exam->pass_marks) }}" min="1">
                            @error('pass_marks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status', $exam->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $exam->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle me-1"></i> Update Exam
                        </button>
                        <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 