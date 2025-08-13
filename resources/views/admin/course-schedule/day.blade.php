@extends('layouts.admin')

@section('title', 'Daily Schedule')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daily Schedule: {{ $selectedDate->format('F j, Y') }}</h1>
            <p class="mt-1 text-sm text-gray-500">Classes scheduled for this day</p>
        </div>
        <div>
            <a href="{{ route('admin.course-schedule.index', ['month' => $selectedDate->month, 'year' => $selectedDate->year]) }}" class="btn btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Calendar
            </a>
        </div>
    </div>

    <!-- Date Navigation -->
    <div class="flex items-center justify-center space-x-4">
        <a href="{{ route('admin.course-schedule.day', ['date' => $selectedDate->copy()->subDay()->format('Y-m-d')]) }}" class="btn btn-sm btn-secondary">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Previous Day
        </a>
        <div class="text-lg font-medium">{{ $selectedDate->format('l, F j, Y') }}</div>
        <a href="{{ route('admin.course-schedule.day', ['date' => $selectedDate->copy()->addDay()->format('Y-m-d')]) }}" class="btn btn-sm btn-secondary">
            Next Day
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>

    <!-- Classes List -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Scheduled Classes</h2>
        </div>
        <div class="card-body p-0">
            @if($classes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Course
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Schedule
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Location
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Instructors
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
                            @foreach($classes as $class)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $class->course->course_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Class ID: {{ $class->class_id ?? 'CLS-' . $class->id }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $class->start_date->format('M d') }} - {{ $class->end_date->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Day {{ $selectedDate->diffInDays($class->start_date) + 1 }} of {{ $class->duration_day }}
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
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-gray-900">
                                            @foreach($class->instructures as $instructor)
                                                <div>{{ $instructor->full_name }}</div>
                                            @endforeach
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
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.courses.classes', $class->course_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No classes scheduled</h3>
                    <p class="mt-1 text-sm text-gray-500">There are no classes scheduled for this day.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
