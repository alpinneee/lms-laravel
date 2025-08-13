@extends('layouts.instructor')

@section('title', $course->course_name)

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
        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $course->course_name }}</span>
    </li>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Course Header -->
    <div class="bg-white shadow overflow-hidden rounded-lg">
        <div class="relative">
            @if($course->image)
                <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->course_name }}" class="h-64 w-full object-cover">
            @else
                <div class="h-64 w-full bg-gray-200 flex items-center justify-center">
                    <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-6 text-white">
                <h1 class="text-3xl font-bold">{{ $course->course_name }}</h1>
                <p class="mt-2 text-lg text-gray-200">{{ $course->course_code }}</p>
            </div>
        </div>
        <div class="border-t border-gray-200 px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Duration</h3>
                    <p class="mt-1 text-lg font-medium text-gray-900">{{ $course->duration }} {{ Str::plural('hour', $course->duration) }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Level</h3>
                    <p class="mt-1 text-lg font-medium text-gray-900">{{ $course->level ?? 'Beginner' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Type</h3>
                    <p class="mt-1 text-lg font-medium text-gray-900">{{ $course->courseType->type_name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Left Column - Course Info -->
        <div class="md:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white shadow overflow-hidden rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Course Description</h3>
                </div>
                <div class="px-6 py-5">
                    @if($course->description)
                        <p class="text-gray-700 whitespace-pre-line">{{ $course->description }}</p>
                    @else
                        <p class="text-gray-500 italic">No description available for this course.</p>
                    @endif
                </div>
            </div>

            <!-- Classes -->
            <div class="bg-white shadow overflow-hidden rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">My Classes</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $classes->count() }} {{ Str::plural('class', $classes->count()) }}
                    </span>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($classes as $class)
                        <div class="px-6 py-5 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-base font-medium text-gray-900">Class #{{ $class->id }}</h4>
                                    <div class="mt-1 flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $class->start_date->format('M d, Y') }} - {{ $class->end_date->format('M d, Y') }}
                                    </div>
                                    <div class="mt-1 flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $class->location }}, {{ $class->room }}
                                    </div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($class->status) }}
                                    </span>
                                    <p class="mt-1 text-sm text-gray-500">{{ $class->registrations->count() }}/{{ $class->quota }} students</p>
                                    <div class="mt-3 flex space-x-3">
                                        <a href="{{ route('instructor.courses.attendance', $course) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Attendance
                                        </a>
                                        <a href="{{ route('instructor.courses.materials', $course) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Materials
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No classes assigned</h3>
                            <p class="mt-1 text-sm text-gray-500">You haven't been assigned to any classes for this course.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column - Additional Info -->
        <div class="space-y-6">
            <!-- Instructors -->
            <div class="bg-white shadow overflow-hidden rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Instructors</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @php
                        $instructors = collect();
                        foreach($classes as $class) {
                            $instructors = $instructors->merge($class->instructures);
                        }
                        $instructors = $instructors->unique('id');
                    @endphp
                    
                    @forelse($instructors as $instructor)
                        <div class="px-6 py-4 flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($instructor->user && $instructor->user->profile_photo)
                                    <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $instructor->user->profile_photo) }}" alt="{{ $instructor->user->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-800 font-medium text-sm">{{ $instructor->user ? substr($instructor->user->name, 0, 2) : substr($instructor->full_name ?? 'IN', 0, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $instructor->user ? $instructor->user->name : $instructor->full_name }}</div>
                                <div class="text-sm text-gray-500">{{ $instructor->specialization ?? 'Instructor' }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-sm text-gray-500">
                            No instructors assigned to your classes.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white shadow overflow-hidden rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Links</h3>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <a href="{{ route('instructor.courses.attendance', $course) }}" class="flex items-center text-blue-600 hover:text-blue-900">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Manage Attendance
                    </a>
                    <a href="{{ route('instructor.courses.materials', $course) }}" class="flex items-center text-blue-600 hover:text-blue-900">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Course Materials
                    </a>
                    <a href="{{ route('instructor.certificates.create') }}" class="flex items-center text-blue-600 hover:text-blue-900">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Issue Certificate
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
