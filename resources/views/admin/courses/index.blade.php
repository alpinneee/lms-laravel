@extends('layouts.admin')

@section('title', 'Course Management')



@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Course Management</h1>
            <p class="mt-1 text-sm text-gray-500">Create and manage training courses, schedules, and class assignments</p>
        </div>
        <div>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Course
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.courses.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <!-- Search -->
                <div class="sm:col-span-2">
                    <label for="search" class="sr-only">Search courses</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input id="search" name="search" value="{{ request('search') }}" class="form-input pl-10" placeholder="Search courses by name or description...">
                    </div>
                </div>
                <!-- Course Type Filter -->
                <div class="flex gap-2">
                    <select name="course_type_id" class="form-select">
                        <option value="">All Course Types</option>
                        @foreach($courseTypes as $type)
                            <option value="{{ $type->id }}" {{ request('course_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->course_type }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($courses as $course)
            <div class="card group hover:shadow-lg transition-shadow duration-200">
                <!-- Course Image -->
                <div class="aspect-w-16 aspect-h-9">
                    <img src="{{ $course->image ? asset('storage/' . $course->image) : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&h=200&fit=crop' }}" 
                         alt="{{ $course->course_name }}" 
                         class="w-full h-48 object-cover rounded-t-lg">
                </div>

                <div class="card-body">
                    <!-- Course Header -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $course->courseType->course_type }}
                        </span>
                        <div class="flex space-x-1">
                            <a href="{{ route('admin.courses.show', $course) }}" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.courses.edit', $course) }}" class="text-gray-400 hover:text-indigo-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Course Title -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-primary-800">
                        {{ $course->course_name }}
                    </h3>

                    <!-- Course Description -->
                    <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                        {{ Str::limit($course->description, 120) }}
                    </p>

                    <!-- Course Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $course->classes->count() }}</div>
                            <div class="text-xs text-gray-500">Classes</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $course->getTotalStudentsAttribute() }}</div>
                            <div class="text-xs text-gray-500">Students</div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.courses.classes', $course) }}" class="btn btn-secondary btn-sm flex-1 text-center">
                            View Classes
                        </a>
                        <a href="{{ route('admin.courses.create-class', $course) }}" class="btn btn-primary btn-sm flex-1 text-center">
                            Add Class
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No courses found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first course.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Course
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($courses->hasPages())
        <div class="card">
            <div class="card-body">
                {{ $courses->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto-submit form when selecting course type
    document.querySelector('select[name="course_type_id"]').addEventListener('change', function() {
        this.form.submit();
    });

    // Delete confirmation
    function confirmDelete(courseId, courseName) {
        if (confirm(`Are you sure you want to delete "${courseName}"? This action cannot be undone.`)) {
            document.getElementById(`delete-form-${courseId}`).submit();
        }
    }
</script>
@endpush
@endsection 