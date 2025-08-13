@extends('layouts.admin')

@section('title', 'Course Schedule')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Course Schedule</h1>
            <p class="mt-1 text-sm text-gray-500">Manage course schedules, time slots, and room assignments</p>
        </div>
        <div>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Courses
            </a>
        </div>
    </div>

    <!-- Month Navigation and Filters -->
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.course-schedule.index', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" class="btn btn-sm btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="text-lg font-medium text-gray-900">{{ $currentDate->format('F Y') }}</h2>
                <a href="{{ route('admin.course-schedule.index', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="btn btn-sm btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            <button type="button" id="filter-toggle" class="btn btn-sm btn-secondary">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filters
            </button>
        </div>
        
        <!-- Filters Form -->
        <div id="filters-panel" class="card-body border-t border-gray-200 {{ request()->hasAny(['course_id', 'instructor_id', 'location']) ? '' : 'hidden' }}">
            <form method="GET" action="{{ route('admin.course-schedule.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="year" value="{{ $year }}">
                
                <!-- Course Filter -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
                    <select name="course_id" id="course_id" class="form-select mt-1">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Instructor Filter -->
                <div>
                    <label for="instructor_id" class="block text-sm font-medium text-gray-700">Instructor</label>
                    <select name="instructor_id" id="instructor_id" class="form-select mt-1">
                        <option value="">All Instructors</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" {{ $instructorId == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Location Filter -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <select name="location" id="location" class="form-select mt-1">
                        <option value="">All Locations</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ $location == $loc ? 'selected' : '' }}>
                                {{ $loc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Filter Actions -->
                <div class="md:col-span-3 flex justify-end space-x-2">
                    <a href="{{ route('admin.course-schedule.index', ['month' => $month, 'year' => $year]) }}" class="btn btn-secondary">
                        Clear Filters
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Calendar -->
    <div class="card">
        <div class="card-body p-0">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Legend -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Legend</h2>
        </div>
        <div class="card-body">
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                    <span class="text-sm text-gray-700">Active Classes</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded-full bg-yellow-500 mr-2"></div>
                    <span class="text-sm text-gray-700">Inactive Classes</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded-full bg-gray-500 mr-2"></div>
                    <span class="text-sm text-gray-700">Completed Classes</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded-full bg-blue-500 mr-2"></div>
                    <span class="text-sm text-gray-700">Other</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div id="event-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900" id="modal-title"></h3>
                <button type="button" id="close-modal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="px-6 py-4">
            <div class="space-y-3">
                <div>
                    <p class="text-sm font-medium text-gray-500">Date</p>
                    <p class="text-sm text-gray-900" id="modal-date"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Location</p>
                    <p class="text-sm text-gray-900" id="modal-location"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Room</p>
                    <p class="text-sm text-gray-900" id="modal-room"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Instructors</p>
                    <p class="text-sm text-gray-900" id="modal-instructors"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Status</p>
                    <p class="text-sm text-gray-900" id="modal-status"></p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <a href="#" id="modal-view-link" class="btn btn-primary">View Class Details</a>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet">
<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-day-today {
        background-color: rgba(96, 165, 250, 0.1) !important;
    }
    .fc-toolbar-title {
        font-size: 1.25rem !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize calendar
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            initialDate: '{{ $currentDate->format("Y-m-d") }}',
            headerToolbar: {
                left: '',
                center: '',
                right: ''
            },
            events: @json($events),
            eventClick: function(info) {
                showEventModal(info.event);
            }
        });
        calendar.render();
        
        // Event modal handling
        const modal = document.getElementById('event-modal');
        const closeModal = document.getElementById('close-modal');
        
        closeModal.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
        
        // Filter toggle
        const filterToggle = document.getElementById('filter-toggle');
        const filtersPanel = document.getElementById('filters-panel');
        
        filterToggle.addEventListener('click', function() {
            filtersPanel.classList.toggle('hidden');
        });
        
        // Auto-submit form when filters change
        const filterSelects = document.querySelectorAll('#course_id, #instructor_id, #location');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });
    });
    
    function showEventModal(event) {
        const modal = document.getElementById('event-modal');
        
        // Set modal content
        document.getElementById('modal-title').textContent = event.title;
        document.getElementById('modal-date').textContent = formatDateRange(event.start, new Date(event.end.getTime() - 86400000)); // Subtract a day from end date
        document.getElementById('modal-location').textContent = event.extendedProps.location;
        document.getElementById('modal-room').textContent = event.extendedProps.room;
        document.getElementById('modal-instructors').textContent = event.extendedProps.instructors;
        document.getElementById('modal-status').textContent = event.extendedProps.status.charAt(0).toUpperCase() + event.extendedProps.status.slice(1);
        
        // Set view link
        document.getElementById('modal-view-link').href = event.extendedProps.url;
        
        // Show modal
        modal.classList.remove('hidden');
    }
    
    function formatDateRange(start, end) {
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        const startStr = start.toLocaleDateString('en-US', options);
        const endStr = end.toLocaleDateString('en-US', options);
        
        if (startStr === endStr) {
            return startStr;
        }
        
        return `${startStr} - ${endStr}`;
    }
</script>
@endpush
@endsection
