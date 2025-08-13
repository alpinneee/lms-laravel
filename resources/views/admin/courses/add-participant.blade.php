@extends('layouts.admin')

@section('title', 'Add Participant')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Add Participant to Class</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $course->course_name }} - {{ $class->class_id ?? 'CLS-' . $class->id }}</p>
    </div>

    <!-- Class Info Card -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Class Information</h2>
        </div>
        <div class="card-body">
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Schedule</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $class->start_date->format('M d, Y') }} - {{ $class->end_date->format('M d, Y') }}
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $class->location }} ({{ $class->room }})
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Enrollment</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $class->registrations->where('reg_status', 'approved')->count() }} / {{ $class->quota }}
                        <span class="text-xs {{ $class->isFull() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                            ({{ $class->isFull() ? 'Full' : $class->availableSpotsAttribute . ' spots available' }})
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.courses.store-participant', ['course' => $course, 'class' => $class]) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Participant Selection -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-medium text-gray-900">Select Participant</h2>
                        
                        <!-- Participant -->
                        <div>
                            <label for="participant_id" class="block text-sm font-medium text-gray-700">Participant</label>
                            <select name="participant_id" id="participant_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                <option value="">Select Participant</option>
                                @foreach($participants as $participant)
                                    <option value="{{ $participant->id }}" {{ old('participant_id') == $participant->id ? 'selected' : '' }}>
                                        {{ $participant->full_name }} ({{ $participant->user->email ?? 'No email' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('participant_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Registration Status -->
                        <div>
                            <label for="reg_status" class="block text-sm font-medium text-gray-700">Registration Status</label>
                            <select name="reg_status" id="reg_status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                <option value="approved" {{ old('reg_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ old('reg_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ old('reg_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('reg_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-medium text-gray-900">Payment Information</h2>
                        
                        <!-- Payment Status -->
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                            <select name="payment_status" id="payment_status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ old('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            @error('payment_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Amount -->
                        <div>
                            <label for="payment" class="block text-sm font-medium text-gray-700">Payment Amount</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="payment" id="payment" value="{{ old('payment', $class->price) }}" min="0" step="0.01"
                                       class="block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Default is the class price ({{ number_format($class->price, 2) }})</p>
                            @error('payment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.courses.show-class', ['course' => $course, 'class' => $class]) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Add Participant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($class->isFull())
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const regStatusSelect = document.getElementById('reg_status');
            const approvedOption = regStatusSelect.querySelector('option[value="approved"]');
            
            // Disable the approved option if class is full
            approvedOption.disabled = true;
            
            // Set default to pending if class is full
            regStatusSelect.value = 'pending';
            
            // Show warning
            const formCard = document.querySelector('.card-body');
            const warningDiv = document.createElement('div');
            warningDiv.className = 'bg-red-50 border-l-4 border-red-400 p-4 mb-6';
            warningDiv.innerHTML = `
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            This class is already full. New participants can only be added with 'Pending' or 'Rejected' status.
                        </p>
                    </div>
                </div>
            `;
            formCard.insertBefore(warningDiv, formCard.firstChild);
        });
    </script>
    @endpush
@endif
@endsection
