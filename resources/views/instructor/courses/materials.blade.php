@extends('layouts.instructor')

@section('title', 'Course Materials - ' . $course->course_name)

@section('content')
<div class="space-y-3">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-lg font-medium text-gray-900">Course Materials</h1>
        <a href="{{ route('instructor.courses.show', $course) }}" class="bg-gray-600 text-white px-3 py-1.5 rounded text-xs hover:bg-gray-700">
            Back to Course
        </a>
    </div>

    <!-- Course Info -->
    <div class="bg-white shadow rounded border p-3">
        <h2 class="text-sm font-medium text-gray-900 mb-2">{{ $course->course_name }}</h2>
        <div class="text-xs text-gray-600">
            Managing materials for {{ $classes->count() }} class(es)
        </div>
    </div>

    @foreach($classes as $class)
    <!-- Class Materials -->
    <div class="bg-white shadow rounded border">
        <div class="border-b border-gray-200 p-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Class: {{ $class->start_date->format('M d') }} - {{ $class->end_date->format('M d, Y') }}</h3>
                    <p class="text-xs text-gray-600">{{ $class->location }} | {{ $class->materials->count() }} materials</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('instructor.courses.add-material', [$course, $class]) }}" class="bg-purple-600 text-white px-2 py-1 rounded text-xs hover:bg-purple-700">
                        Add Material
                    </a>
                    <a href="{{ route('instructor.certificates.create', ['class_id' => $class->id]) }}" class="bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700">
                        Issue Certificate
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-3">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Day</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($class->materials as $material)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-2 text-xs text-gray-900">Day {{ $material->day }}</td>
                                <td class="px-2 py-2 text-xs text-gray-900 font-medium">{{ $material->title }}</td>
                                <td class="px-2 py-2 text-xs text-gray-600">{{ $material->size }}</td>
                                <td class="px-2 py-2">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ $material->file_url }}" target="_blank" class="text-blue-500 hover:text-blue-700" title="Download">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('instructor.courses.remove-material', [$course, $class, $material]) }}" class="inline" onsubmit="return confirm('Remove this material?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Remove">
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
                                <td colspan="4" class="px-2 py-4 text-center text-xs text-gray-500">
                                    No materials uploaded for this class
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
