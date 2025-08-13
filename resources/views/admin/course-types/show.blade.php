@extends('layouts.admin')

@section('title', 'Course Type Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Course Type Details</h1>
            <p class="mt-1 text-sm text-gray-500">View course type information and associated courses</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.course-types.edit', $courseType) }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.course-types.index') }}" class="btn btn-secondary">
                Back to List
            </a>
        </div>
    </div>

    <!-- Course Type Info Card -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Course Type Information</h2>
        </div>
        <div class="card-body">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Course Type Name</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $courseType->course_type }}</dd>
                </div>
                
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $courseType->description ?? 'No description provided.' }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created On</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $courseType->created_at->format('M d, Y') }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $courseType->updated_at->format('M d, Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Associated Courses Card -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Associated Courses</h2>
            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-medium">
                {{ $courseType->courses->count() }} {{ Str::plural('course', $courseType->courses->count()) }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Course Name
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Code
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Classes
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($courseType->courses as $course)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $course->course_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Illuminate\Support\Str::limit($course->description, 50) }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $course->course_code }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $course->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $course->classes_count ?? $course->classes->count() }} {{ Str::plural('class', $course->classes_count ?? $course->classes->count()) }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.courses.show', $course) }}" class="text-blue-600 hover:text-blue-900">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No courses associated with this type.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
