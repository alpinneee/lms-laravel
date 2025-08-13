@extends('layouts.admin')

@section('title', 'Edit Certificate')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Certificate</h1>
        <p class="mt-1 text-sm text-gray-500">Update certificate information</p>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.certificates.update', $certificate) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Certificate Information -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-medium text-gray-900">Certificate Information</h2>
                        
                        <!-- Certificate Number (Read-only) -->
                        <div>
                            <label for="certificate_number" class="block text-sm font-medium text-gray-700">Certificate Number</label>
                            <input type="text" id="certificate_number" value="{{ $certificate->certificate_number }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                                   readonly>
                        </div>
                        
                        <!-- Recipient Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Recipient Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $certificate->name) }}" 
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
                                    <option value="{{ $participant->id }}" 
                                            {{ old('participant_id', $certificate->participant_id) == $participant->id ? 'selected' : '' }}
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
                                    <option value="{{ $course->id }}" 
                                            {{ old('course_id', $certificate->course_id) == $course->id ? 'selected' : '' }}>
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
                                    <option value="{{ $instructor->id }}" 
                                            {{ old('instructure_id', $certificate->instructure_id) == $instructor->id ? 'selected' : '' }}>
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
                        
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                <option value="valid" {{ old('status', $certificate->status) === 'valid' ? 'selected' : '' }}>
                                    Valid
                                </option>
                                <option value="revoked" {{ old('status', $certificate->status) === 'revoked' ? 'selected' : '' }}>
                                    Revoked
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Issue Date -->
                        <div>
                            <label for="issue_date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                            <input type="date" name="issue_date" id="issue_date" 
                                   value="{{ old('issue_date', $certificate->issue_date->format('Y-m-d')) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   required>
                            @error('issue_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Expiry Date -->
                        <div>
                            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <input type="date" name="expiry_date" id="expiry_date" 
                                   value="{{ old('expiry_date', $certificate->expiry_date->format('Y-m-d')) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   required>
                            @error('expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Certificate File -->
                        @if($certificate->getRawOriginal('pdf_url'))
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Current Certificate PDF</label>
                                <div class="mt-1 flex items-center">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <a href="{{ asset('storage/' . $certificate->getRawOriginal('pdf_url')) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-500">
                                            View current PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Certificate File -->
                        <div>
                            <label for="certificate_file" class="block text-sm font-medium text-gray-700">
                                {{ $certificate->getRawOriginal('pdf_url') ? 'Replace Certificate PDF' : 'Upload Certificate PDF' }}
                            </label>
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
                            <input type="url" name="drive_link" id="drive_link" value="{{ old('drive_link', $certificate->drive_link) }}" 
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
                    <a href="{{ route('admin.certificates.show', $certificate) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Update Certificate
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
