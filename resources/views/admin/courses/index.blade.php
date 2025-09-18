@extends('layouts.admin')

@section('title', 'Courses')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-900">Courses</h1>
        <a href="{{ route('admin.courses.create') }}" class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-700">
            + Add Course
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-3">
            <form method="GET" class="flex gap-2">
                <input name="search" value="{{ request('search') }}" class="flex-1 border border-gray-300 rounded px-3 py-1.5 text-sm" placeholder="Search courses...">
                <select name="course_type_id" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                    <option value="">All Types</option>
                    @foreach($courseTypes as $type)
                        <option value="{{ $type->id }}" {{ request('course_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->course_type }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-gray-600 text-white px-3 py-1.5 rounded text-sm hover:bg-gray-700">Filter</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
        @forelse($courses as $course)
            <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                <img src="{{ $course->image ? asset('storage/' . $course->image) : 'https://via.placeholder.com/300x150' }}" 
                     alt="{{ $course->course_name }}" 
                     class="w-full h-24 object-cover rounded-t-lg">
                
                <div class="p-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded">
                            {{ $course->courseType->course_type }}
                        </span>
                        <div class="flex gap-1">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="text-gray-400 hover:text-blue-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <h3 class="font-medium text-gray-900 text-sm mb-1">
                        {{ Str::limit($course->course_name, 30) }}
                    </h3>
                    
                    <p class="text-xs text-gray-600 mb-2">
                        {{ Str::limit($course->description, 60) }}
                    </p>
                    
                    <div class="flex justify-between text-xs text-gray-500 mb-2">
                        <span>{{ $course->classes->count() }} Classes</span>
                        <span>{{ $course->getTotalStudentsAttribute() }} Students</span>
                    </div>
                    
                    <div class="flex gap-1">
                        <a href="{{ route('admin.courses.classes', $course) }}" class="flex-1 bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs text-center hover:bg-gray-200">
                            Classes
                        </a>
                        <a href="{{ route('admin.courses.create-class', $course) }}" class="flex-1 bg-blue-600 text-white px-2 py-1 rounded text-xs text-center hover:bg-blue-700">
                            + Class
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500 text-sm">No courses found.</p>
                <a href="{{ route('admin.courses.create') }}" class="mt-2 inline-block bg-blue-600 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-700">
                    + Add Course
                </a>
            </div>
        @endforelse
    </div>

    @if($courses->hasPages())
        <div class="bg-white rounded-lg shadow-sm border p-3">
            {{ $courses->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection