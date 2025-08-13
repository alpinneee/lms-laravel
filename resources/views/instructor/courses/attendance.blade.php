@extends('layouts.instructor')

@section('title', 'Course Attendance - ' . $course->course_name)

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <a href="{{ route('instructor.courses.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">My Courses</a>
    </li>
    <li class="flex items-center">
        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <a href="{{ route('instructor.courses.show', $course) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">{{ $course->course_name }}</a>
    </li>
    <li class="flex items-center">
        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Attendance</span>
    </li>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Attendance Management
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Track and manage student attendance for {{ $course->course_name }}.
            </p>
        </div>
    </div>

    <!-- Placeholder Content -->
    <div class="bg-white shadow overflow-hidden rounded-lg">
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Attendance Management</h3>
            <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto">
                This feature is coming soon. You will be able to track and manage student attendance for your classes here.
            </p>
            <div class="mt-6">
                <a href="{{ route('instructor.courses.show', $course) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Back to Course
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
