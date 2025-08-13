@extends('layouts.admin')

@section('title', 'Create Course')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Create New Course</h1>
        <p class="mt-1 text-sm text-gray-500">Add a new training course to the system</p>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Course Information -->
                    <div class="space-y-4 md:col-span-2">
                        <h2 class="text-lg font-medium text-gray-900">Course Information</h2>
                        
                        <!-- Course Name -->
                        <div>
                            <label for="course_name" class="block text-sm font-medium text-gray-700">Course Name</label>
                            <input type="text" name="course_name" id="course_name" value="{{ old('course_name') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   required>
                            @error('course_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Course Type -->
                        <div>
                            <label for="course_type_id" class="block text-sm font-medium text-gray-700">Course Type</label>
                            <select name="course_type_id" id="course_type_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                <option value="">Select Course Type</option>
                                @foreach($courseTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('course_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->course_type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Course Image -->
                    <div class="space-y-4 md:col-span-1">
                        <h2 class="text-lg font-medium text-gray-900">Course Image</h2>
                        
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Cover Image</label>
                            <input type="file" name="image" id="image" 
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                            <p class="mt-1 text-xs text-gray-500">Upload a cover image for the course (JPEG, PNG, GIF, max 2MB)</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-md flex items-center justify-center">
                                <div id="image-preview" class="text-center p-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-1 text-sm text-gray-500">Image preview will appear here</p>
                                </div>
                                <img id="preview-image" class="hidden w-full h-full object-cover rounded-md" alt="Preview">
                            </div>
                        </div>
                    </div>

                    <!-- Course Description -->
                    <div class="space-y-4 md:col-span-2">
                        <h2 class="text-lg font-medium text-gray-900">Course Description</h2>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="6" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                      required>{{ old('description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Provide a detailed description of the course content, objectives, and outcomes</p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Create Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').classList.add('hidden');
                const previewImage = document.getElementById('preview-image');
                previewImage.src = e.target.result;
                previewImage.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
