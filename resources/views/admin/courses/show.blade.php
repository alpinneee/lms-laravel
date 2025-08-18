@extends('layouts.admin')

@section('title', 'Course Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $course->course_name }}</h1>
            <p class="mt-1 text-sm text-gray-500">Course details and statistics</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                Edit Course
            </a>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                Back to Courses
            </a>
        </div>
    </div>

    <!-- Course Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Course Image and Info -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-body p-0">
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="{{ $course->image ? asset('storage/' . $course->image) : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=400&fit=crop' }}" 
                             alt="{{ $course->course_name }}" 
                             class="w-full h-64 object-cover rounded-t-lg">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $course->courseType->course_type }}
                            </span>
                        </div>
                        
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Description</h2>
                        <div class="prose max-w-none text-gray-700">
                            <p>{{ $course->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Stats -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">Course Statistics</h2>
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Total Classes</dt>
                            <dd class="mt-1 text-xl font-semibold text-gray-900">{{ $stats['total_classes'] }}</dd>
                        </div>
                        
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Active Classes</dt>
                            <dd class="mt-1 text-xl font-semibold text-gray-900">{{ $stats['active_classes'] }}</dd>
                        </div>
                        
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Total Students</dt>
                            <dd class="mt-1 text-xl font-semibold text-gray-900">{{ $stats['total_students'] }}</dd>
                        </div>
                        
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Total Revenue</dt>
                            <dd class="mt-1 text-xl font-semibold text-gray-900">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</dd>
                        </div>
                        
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Created On</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $course->created_at->format('M d, Y') }}</dd>
                        </div>
                        
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $course->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex space-x-3">
                        <a href="{{ route('admin.courses.classes', $course) }}" class="btn btn-secondary btn-sm flex-1 justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            View Classes
                        </a>
                        <a href="{{ route('admin.courses.create-class', $course) }}" class="btn btn-primary btn-sm flex-1 justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Class
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Classes List -->
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Course Classes</h2>
            <a href="{{ route('admin.courses.create-class', $course) }}" class="btn btn-primary btn-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Class
            </a>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Class ID
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Schedule
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Location
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Enrollment
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($course->classes as $class)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $class->class_id ?? 'CLS-' . $class->id }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $class->instructures->count() }} instructor(s)
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $class->start_date->format('M d, Y') }} - {{ $class->end_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $class->duration_day }} days
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $class->location }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Room: {{ $class->room }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $class->registrations->where('reg_status', 'approved')->count() }} / {{ $class->quota }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $class->registrations->where('reg_status', 'pending')->count() }} pending
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $statusColor = 'gray';
                                        if ($class->status === 'active') $statusColor = 'green';
                                        elseif ($class->status === 'inactive') $statusColor = 'yellow';
                                        elseif ($class->status === 'completed') $statusColor = 'blue';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                        {{ ucfirst($class->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="#" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No classes have been created for this course yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="card border-red-200">
        <div class="card-header bg-red-50">
            <h2 class="text-lg font-medium text-red-800">Danger Zone</h2>
        </div>
        <div class="card-body bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Delete this course</h3>
                    <p class="text-sm text-gray-500">
                        Once deleted, all course data will be permanently removed. This action cannot be undone.
                    </p>
                </div>
                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" {{ $course->classes->where('status', 'active')->count() > 0 ? 'disabled' : '' }}>
                        Delete Course
                    </button>
                </form>
            </div>
            @if($course->classes->where('status', 'active')->count() > 0)
                <p class="mt-2 text-sm text-red-600">
                    This course has active classes and cannot be deleted.
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
