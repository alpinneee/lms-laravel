@extends('layouts.admin')

@section('title', 'Class Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Class Details</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $course->course_name }} - {{ $class->class_id ?? 'CLS-' . $class->id }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.courses.edit-class', ['course' => $course, 'class' => $class]) }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                Edit Class
            </a>
            <a href="{{ route('admin.courses.classes', $course) }}" class="btn btn-secondary">
                Back to Classes
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Class Information -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">Class Information</h2>
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Class ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $class->class_id ?? 'CLS-' . $class->id }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @if($class->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @elseif($class->status === 'inactive')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @elseif($class->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Completed
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Course</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $course->course_name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Price</dt>
                            <dd class="mt-1 text-sm text-gray-900">${{ number_format($class->price, 2) }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registration Period</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $class->start_reg_date->format('M d, Y') }} - {{ $class->end_reg_date->format('M d, Y') }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Class Period</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $class->start_date->format('M d, Y') }} - {{ $class->end_date->format('M d, Y') }}
                                <span class="text-xs text-gray-500 ml-1">({{ $class->duration_day }} days)</span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $class->location }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Room</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $class->room }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Enrollment</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $class->registrations->where('reg_status', 'approved')->count() }} / {{ $class->quota }}
                                <span class="text-xs text-gray-500 ml-1">
                                    ({{ $class->registrations->where('reg_status', 'pending')->count() }} pending)
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $class->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Instructors -->
            <div class="card mt-6">
                <div class="card-header flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900">Instructors</h2>
                </div>
                <div class="card-body p-0">
                    <ul class="divide-y divide-gray-200">
                        @forelse($class->instructures as $instructor)
                            <li class="px-4 py-3 flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($instructor->photo)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($instructor->photo) }}" alt="{{ $instructor->full_name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold">
                                            {{ substr($instructor->full_name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $instructor->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $instructor->email }}</div>
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-3 text-sm text-gray-500">No instructors assigned to this class.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Actions and Stats -->
        <div>
            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">Actions</h2>
                </div>
                <div class="card-body space-y-4">
                    <a href="{{ route('admin.courses.add-participant', ['course' => $course, 'class' => $class]) }}" class="btn btn-primary w-full flex justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Add Participant
                    </a>
                    
                    <a href="{{ route('admin.courses.edit-class', ['course' => $course, 'class' => $class]) }}" class="btn btn-secondary w-full flex justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit Class
                    </a>
                    
                    <form action="{{ route('admin.courses.update-class-status', ['course' => $course, 'class' => $class]) }}" method="POST">
                        @csrf
                        <div class="flex items-center space-x-3">
                            <select name="status" class="form-select flex-grow">
                                <option value="active" {{ $class->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $class->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="completed" {{ $class->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <button type="submit" class="btn btn-secondary">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Class Statistics -->
            <div class="card mt-6">
                <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">Statistics</h2>
                </div>
                <div class="card-body">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Participants</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                {{ $class->registrations->where('reg_status', 'approved')->count() }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Available Spots</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                {{ $class->availableSpotsAttribute }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pending Registrations</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                {{ $class->registrations->where('reg_status', 'pending')->count() }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Revenue</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                ${{ number_format($class->registrations->where('payment_status', 'paid')->sum('payment'), 2) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Participants List -->
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Participants</h2>
            <a href="{{ route('admin.courses.add-participant', ['course' => $course, 'class' => $class]) }}" class="btn btn-sm btn-primary">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Participant
            </a>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Participant
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Registration Date
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider actions-header">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($class->registrations as $registration)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            @if($registration->participant->photo)
                                                <img class="h-8 w-8 rounded-full object-cover" src="{{ Storage::url($registration->participant->photo) }}" alt="{{ $registration->participant->full_name }}">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold">
                                                    {{ substr($registration->participant->full_name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $registration->participant->full_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $registration->participant->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $registration->reg_date->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${{ number_format($registration->payment, 2) }}</div>
                                    <div class="text-xs {{ $registration->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                        {{ ucfirst($registration->payment_status) }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($registration->reg_status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($registration->reg_status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($registration->reg_status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium actions-cell">
                                    <div class="action-buttons">
                                        <a href="#" class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900" title="Edit Registration">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </a>
                                        
                                        <a href="#" class="text-red-600 hover:text-red-900" title="Remove Participant">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No participants registered for this class yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }
    
    .actions-cell {
        width: 120px;
    }
    
    .actions-header {
        text-align: center;
    }
</style>
@endsection
