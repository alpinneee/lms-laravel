@extends('layouts.instructor')

@section('title', 'Issue Certificate')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <a href="{{ route('instructor.certificates.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Certificates</a>
    </li>
    <li class="flex items-center">
        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Issue Certificate</span>
    </li>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Issue Certificate
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Create a new certificate for a student who has completed a course.
            </p>
        </div>
    </div>

    <!-- Certificate Form -->
    <div class="bg-white shadow overflow-hidden rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Certificate Details</h3>
        </div>
        
        <form action="{{ route('instructor.certificates.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-5 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
                    <select id="course_id" name="course_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md @error('course_id') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" required>
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $selectedCourseId) == $course->id ? 'selected' : '' }}>{{ $course->course_name }}</option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="participant_id" class="block text-sm font-medium text-gray-700">Participant</label>
                    <select id="participant_id" name="participant_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md @error('participant_id') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" required>
                        <option value="">Select Participant</option>
                        @foreach($participants as $participant)
                            <option value="{{ $participant->id }}" {{ old('participant_id', $selectedParticipantId) == $participant->id ? 'selected' : '' }}>{{ $participant->user->name }}</option>
                        @endforeach
                    </select>
                    @error('participant_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="issue_date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                    <input type="date" name="issue_date" id="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('issue_date') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" required>
                    @error('issue_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('expiry_date') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Leave blank if the certificate does not expire</p>
                    @error('expiry_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label for="certificate_file" class="block text-sm font-medium text-gray-700">Certificate PDF</label>
                <input type="file" name="certificate_file" id="certificate_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 @error('certificate_file') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" accept="application/pdf">
                <p class="mt-1 text-xs text-gray-500">Upload the certificate PDF file (max 5MB)</p>
                @error('certificate_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('instructor.certificates.index') }}" class="btn-secondary btn-sm mr-3">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Issue Certificate</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const courseSelect = document.getElementById('course_id');
        const participantSelect = document.getElementById('participant_id');
        
        // Function to load participants based on selected course
        function loadParticipants() {
            const courseId = courseSelect.value;
            
            if (!courseId) {
                // Clear participants dropdown if no course selected
                participantSelect.innerHTML = '<option value="">Select Participant</option>';
                return;
            }
            
            // Show loading state
            participantSelect.innerHTML = '<option value="">Loading participants...</option>';
            
            // Fetch participants for the selected course
            fetch(`{{ route('instructor.certificates.get-participants') }}?course_id=${courseId}`)
                .then(response => response.json())
                .then(data => {
                    // Reset the dropdown
                    participantSelect.innerHTML = '<option value="">Select Participant</option>';
                    
                    // Add participants to dropdown
                    data.forEach(participant => {
                        const option = document.createElement('option');
                        option.value = participant.id;
                        option.textContent = participant.user.name;
                        
                        // Check if this participant was previously selected
                        const selectedParticipantId = '{{ old('participant_id', $selectedParticipantId ?? '') }}';
                        if (participant.id == selectedParticipantId) {
                            option.selected = true;
                        }
                        
                        participantSelect.appendChild(option);
                    });
                    
                    // Show message if no participants
                    if (data.length === 0) {
                        participantSelect.innerHTML = '<option value="">No participants found for this course</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading participants:', error);
                    participantSelect.innerHTML = '<option value="">Error loading participants</option>';
                });
        }
        
        // Load participants when the course changes
        courseSelect.addEventListener('change', loadParticipants);
        
        // Load participants on page load if a course is selected
        if (courseSelect.value) {
            loadParticipants();
        }
    });
</script>
@endsection
