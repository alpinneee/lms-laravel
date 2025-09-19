@extends('layouts.admin')

@section('title', 'Course Schedule')

@section('content')
<div class="space-y-3">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-lg font-medium text-gray-900">Course Schedule</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.courses.create-class', ['course' => 1]) }}" class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs hover:bg-blue-700">
                Add New
            </a>
            <form method="GET" action="{{ route('admin.course-schedule.index') }}" class="flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="border border-gray-300 rounded px-2 py-1.5 text-xs w-40">
                <button type="submit" class="ml-1 bg-blue-600 text-white px-3 py-1.5 rounded text-xs hover:bg-blue-700">Search</button>
            </form>
        </div>
    </div>



    <!-- Schedule Table -->
    <div class="bg-white shadow rounded border">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($classes as $index => $class)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 text-xs text-gray-900">{{ $classes->firstItem() + $index }}</td>
                            <td class="px-3 py-2 text-xs text-gray-900 font-medium">{{ $class->course->course_name }}</td>
                            <td class="px-3 py-2 text-xs text-gray-600">
                                {{ $class->start_date->format('M d') }} - {{ $class->end_date->format('M d, Y') }}
                            </td>
                            <td class="px-3 py-2 text-xs text-gray-600">
                                {{ $class->location }}{{ $class->room ? ' - ' . $class->room : '' }}
                            </td>
                            <td class="px-3 py-2">
                                @if($class->status === 'active')
                                    <span class="px-1.5 py-0.5 text-xs rounded bg-green-100 text-green-700">
                                        Active
                                    </span>
                                @elseif($class->status === 'inactive')
                                    <span class="px-1.5 py-0.5 text-xs rounded bg-red-100 text-red-700">
                                        Inactive
                                    </span>
                                @else
                                    <span class="px-1.5 py-0.5 text-xs rounded bg-gray-100 text-gray-700">
                                        {{ ucfirst($class->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.course-schedule.show', [$class->course_id, $class->id]) }}" class="text-gray-500 hover:text-gray-700" title="View">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.courses.edit-class', [$class->course_id, $class->id]) }}" class="text-blue-500 hover:text-blue-700" title="Edit">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="#" class="inline" onsubmit="return confirm('Delete this schedule?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Delete">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-4 text-center text-xs text-gray-500">
                                @if(request('search'))
                                    No schedules found for "{{ request('search') }}"
                                @else
                                    No schedules found
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($classes->hasPages())
        <div class="bg-gray-50 px-3 py-2 flex items-center justify-between border-t">
            <div class="text-xs text-gray-600">
                {{ $classes->firstItem() ?? 0 }} - {{ $classes->lastItem() ?? 0 }} of {{ $classes->total() }}
            </div>
            <div class="flex gap-1">
                @if($classes->previousPageUrl())
                    <a href="{{ $classes->previousPageUrl() }}" class="px-2 py-1 text-xs border rounded hover:bg-gray-100">Prev</a>
                @endif
                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 border rounded">{{ $classes->currentPage() }}</span>
                @if($classes->nextPageUrl())
                    <a href="{{ $classes->nextPageUrl() }}" class="px-2 py-1 text-xs border rounded hover:bg-gray-100">Next</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
