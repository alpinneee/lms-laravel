@extends('layouts.admin')

@section('title', 'Issue Certificate')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Issue Certificate</h1>
        <p class="mt-1 text-sm text-gray-500">Create a new certificate for a participant</p>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.certificates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Certificate Information -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-medium text-gray-900">Certificate Information</h2>
                        
                        <!-- Recipient Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Recipient Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Name as it will appear on the certificate</p>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Participant -->
                        <div>
                            <label for="participant_id" class="block text-sm font-medium text-gray-700">Participant</label>
                            <select name="participant_id" id="participant_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                <option value="">Select Participant</option>
                                @foreach($participants as $participant)
                                    <option value="{{ $participant->id }}" {{ old('participant_id') == $participant->id ? 'selected' : '' }}
                                            data-name="{{ $participant->full_name }}">
                                        {{ $participant->full_name }} ({{ $participant->user->email ?? 'No email' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('participant_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Course -->
                        <div>
                            <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
                            <select name="course_id" id="course_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instructor -->
                        <div>
                            <label for="instructure_id" class="block text-sm font-medium text-gray-700">Instructor</label>
                            <select name="instructure_id" id="instructure_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                <option value="">Select Instructor</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ old('instructure_id') == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('instructure_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dates and Files -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-medium text-gray-900">Dates and Files</h2>
                        
                        <!-- Issue Date -->
                        <div>
                            <label for="issue_date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                            <input type="date" name="issue_date" id="issue_date" value="{{ old('issue_date', now()->format('Y-m-d')) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   required>
                            @error('issue_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Expiry Date -->
                        <div>
                            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', now()->addYears(2)->format('Y-m-d')) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Default validity is 2 years</p>
                            @error('expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Certificate File -->
                        <div>
                            <label for="certificate_file" class="block text-sm font-medium text-gray-700">Certificate PDF</label>
                            <input type="file" name="certificate_file" id="certificate_file" 
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                                   accept="application/pdf">
                            <p class="mt-1 text-xs text-gray-500">Upload the certificate PDF file (max 5MB)</p>
                            @error('certificate_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Drive Link -->
                        <div>
                            <label for="drive_link" class="block text-sm font-medium text-gray-700">Google Drive Link (Optional)</label>
                            <input type="url" name="drive_link" id="drive_link" value="{{ old('drive_link') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <p class="mt-1 text-xs text-gray-500">Link to certificate file in Google Drive</p>
                            @error('drive_link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Issue Certificate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-fill recipient name when participant is selected
        const participantSelect = document.getElementById('participant_id');
        const nameInput = document.getElementById('name');
        
        participantSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                nameInput.value = selectedOption.getAttribute('data-name');
            }
        });
        
        // Validate dates
        const issueDateInput = document.getElementById('issue_date');
        const expiryDateInput = document.getElementById('expiry_date');
        
        issueDateInput.addEventListener('change', function() {
            if (expiryDateInput.value && new Date(expiryDateInput.value) <= new Date(this.value)) {
                expiryDateInput.value = '';
            }
            expiryDateInput.min = this.value;
        });
        
        // Set min date for expiry date
        if (issueDateInput.value) {
            expiryDateInput.min = issueDateInput.value;
        }
    });
</script>
@endpush
@endsection
