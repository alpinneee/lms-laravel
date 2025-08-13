@extends('layouts.admin')

@section('title', 'Edit Course Type')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Course Type</h1>
        <p class="mt-1 text-sm text-gray-500">Update course type information</p>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.course-types.update', $courseType) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Course Type Name -->
                    <div>
                        <label for="course_type" class="block text-sm font-medium text-gray-700">Course Type Name</label>
                        <input type="text" name="course_type" id="course_type" value="{{ old('course_type', $courseType->course_type) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                               required>
                        <p class="mt-1 text-xs text-gray-500">Enter a descriptive name for this course type</p>
                        @error('course_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('description', $courseType->description) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Provide a detailed description of this course type</p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.course-types.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Update Course Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
