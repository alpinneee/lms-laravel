@extends('layouts.admin')

@section('title', 'Add Material')

@section('content')
<div class="space-y-3">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-lg font-medium text-gray-900">Add Material</h1>
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

    <!-- Add Material Form -->
    <div class="bg-white shadow rounded border p-4">
        <form method="POST" action="{{ route('admin.courses.store-material', [$course->id, $class->id]) }}" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="title" class="block text-xs font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" id="title" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="day" class="block text-xs font-medium text-gray-700 mb-1">Day</label>
                    <select name="day" id="day" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select day...</option>
                        @for($i = 1; $i <= $class->duration_day; $i++)
                            <option value="{{ $i }}">Day {{ $i }}</option>
                        @endfor
                    </select>
                    @error('day')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-2">Upload Method</label>
                <div class="flex gap-4">
                    <label class="flex items-center">
                        <input type="radio" name="upload_method" value="file" class="mr-2" checked>
                        <span class="text-sm">Upload File</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="upload_method" value="google_drive" class="mr-2">
                        <span class="text-sm">Google Drive Link</span>
                    </label>
                </div>
            </div>

            <div id="file-upload" class="mb-4">
                <label for="file" class="block text-xs font-medium text-gray-700 mb-1">File</label>
                <input type="file" name="file" id="file" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Max file size: 10MB</p>
                @error('file')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="google-drive-link" class="mb-4 hidden">
                <label for="google_drive_url" class="block text-xs font-medium text-gray-700 mb-1">Google Drive URL</label>
                <input type="url" name="google_drive_url" id="google_drive_url" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://drive.google.com/...">
                @error('google_drive_url')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                    Add Material
                </button>
                <a href="{{ route('admin.course-schedule.show', [$course->id, $class->id]) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadMethodRadios = document.querySelectorAll('input[name="upload_method"]');
    const fileUpload = document.getElementById('file-upload');
    const googleDriveLink = document.getElementById('google-drive-link');

    uploadMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'file') {
                fileUpload.classList.remove('hidden');
                googleDriveLink.classList.add('hidden');
            } else {
                fileUpload.classList.add('hidden');
                googleDriveLink.classList.remove('hidden');
            }
        });
    });
});
</script>
@endsection