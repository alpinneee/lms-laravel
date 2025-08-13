@extends('layouts.admin')

@section('title', 'Create Class')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Create New Class</h1>
        <p class="mt-1 text-sm text-gray-500">Add a new class for {{ $course->course_name }}</p>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.courses.store-class', $course) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Class Information -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-medium text-gray-900">Class Information</h2>
                        
                        <!-- Quota -->
                        <div>
                            <label for="quota" class="block text-sm font-medium text-gray-700">Quota</label>
                            <input type="number" name="quota" id="quota" value="{{ old('quota') }}" min="1" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Maximum number of participants</p>
                            @error('quota')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" step="0.01"
                                       class="block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       required>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration_day" class="block text-sm font-medium text-gray-700">Duration (Days)</label>
                            <input type="number" name="duration_day" id="duration_day" value="{{ old('duration_day') }}" min="1" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   required>
                            @error('duration_day')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Schedule & Location -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-medium text-gray-900">Schedule & Location</h2>
                        
                        <!-- Registration Period -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="start_reg_date" class="block text-sm font-medium text-gray-700">Registration Start</label>
                                <input type="date" name="start_reg_date" id="start_reg_date" value="{{ old('start_reg_date') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       required>
                                @error('start_reg_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="end_reg_date" class="block text-sm font-medium text-gray-700">Registration End</label>
                                <input type="date" name="end_reg_date" id="end_reg_date" value="{{ old('end_reg_date') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       required>
                                @error('end_reg_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Class Period -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Class Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       required>
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Class End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       required>
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" name="location" id="location" value="{{ old('location') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   required>
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Room -->
                        <div>
                            <label for="room" class="block text-sm font-medium text-gray-700">Room</label>
                            <input type="text" name="room" id="room" value="{{ old('room') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   required>
                            @error('room')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Instructors -->
                    <div class="space-y-4 md:col-span-2">
                        <h2 class="text-lg font-medium text-gray-900">Instructors</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Assign Instructors</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($instructures as $instructor)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="instructors[]" id="instructor_{{ $instructor->id }}" value="{{ $instructor->id }}" 
                                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                               {{ in_array($instructor->id, old('instructors', [])) ? 'checked' : '' }}>
                                        <label for="instructor_{{ $instructor->id }}" class="ml-3 text-sm text-gray-700">
                                            {{ $instructor->full_name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('instructors')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.courses.classes', $course) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Create Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Date validation
    document.addEventListener('DOMContentLoaded', function() {
        const startRegDate = document.getElementById('start_reg_date');
        const endRegDate = document.getElementById('end_reg_date');
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        
        // Set min dates
        const today = new Date().toISOString().split('T')[0];
        startRegDate.setAttribute('min', today);
        
        // Update min dates when values change
        startRegDate.addEventListener('change', function() {
            endRegDate.setAttribute('min', startRegDate.value);
        });
        
        endRegDate.addEventListener('change', function() {
            startDate.setAttribute('min', endRegDate.value);
        });
        
        startDate.addEventListener('change', function() {
            endDate.setAttribute('min', startDate.value);
        });
    });
</script>
@endpush
@endsection
