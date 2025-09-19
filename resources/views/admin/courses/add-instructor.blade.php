@extends('layouts.admin')

@section('title', 'Add Instructor')

@section('content')
<div class="space-y-3">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-lg font-medium text-gray-900">Add Instructor</h1>
        <a href="{{ route('admin.course-schedule.show', [$course->id, $class->id]) }}" class="bg-gray-600 text-white px-3 py-1.5 rounded text-xs hover:bg-gray-700">
            Back
        </a>
    </div>

    <!-- Course Info -->
    <div class="bg-white shadow rounded border p-3">
        <h2 class="text-sm font-medium text-gray-900 mb-2">{{ $course->course_name }}</h2>
        <div class="text-xs text-gray-600">
            {{ $class->start_date->format('j M') }} - {{ $class->end_date->format('j M Y') }} | {{ $class->location }}
        </div>
    </div>

    <!-- Add Instructor Form -->
    <div class="bg-white shadow rounded border p-4">
        <form method="POST" action="{{ route('admin.courses.store-instructor', [$course->id, $class->id]) }}">
            @csrf
            
            <div class="mb-4">
                <label for="instructor_id" class="block text-xs font-medium text-gray-700 mb-1">Select Instructor</label>
                <select name="instructor_id" id="instructor_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Choose an instructor...</option>
                    @foreach($availableInstructors as $instructor)
                        <option value="{{ $instructor->id }}">
                            {{ $instructor->full_name }} - {{ $instructor->specialization }}
                        </option>
                    @endforeach
                </select>
                @error('instructor_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                    Add Instructor
                </button>
                <a href="{{ route('admin.course-schedule.show', [$course->id, $class->id]) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Available Instructors -->
    <div class="bg-white shadow rounded border p-3">
        <h3 class="text-sm font-medium text-gray-900 mb-2">Available Instructors ({{ $availableInstructors->count() }})</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Specialization</th>
                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($availableInstructors as $instructor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-2 text-xs text-gray-900 font-medium">{{ $instructor->full_name }}</td>
                            <td class="px-2 py-2 text-xs text-gray-600">{{ $instructor->specialization }}</td>
                            <td class="px-2 py-2 text-xs text-gray-600">{{ $instructor->user->email }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-2 py-4 text-center text-xs text-gray-500">
                                No available instructors
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection