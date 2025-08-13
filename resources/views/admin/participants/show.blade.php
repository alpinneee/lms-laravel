@extends('layouts.admin')

@section('title', 'Participant Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Participant Details</h1>
            <p class="mt-1 text-sm text-gray-500">View participant information and course registrations</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.participants.edit', $participant) }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">
                Back to List
            </a>
        </div>
    </div>

    <!-- Participant Profile Card -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Participant Profile</h2>
        </div>
        <div class="card-body">
            <div class="flex flex-col md:flex-row">
                <!-- Participant Photo -->
                <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                    @if($participant->photo)
                        <img src="{{ Storage::url($participant->photo) }}" alt="{{ $participant->full_name }}" class="h-40 w-40 rounded-lg object-cover">
                    @else
                        <div class="h-40 w-40 rounded-lg bg-primary-600 flex items-center justify-center text-white text-4xl font-bold">
                            {{ substr($participant->full_name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <!-- Participant Details -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $participant->full_name }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $participant->phone_number }}</dd>
                    </div>
                    
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $participant->address }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $participant->birth_date ? $participant->birth_date->format('M d, Y') : 'N/A' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($participant->gender) }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Job Title</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $participant->job_title ?: 'N/A' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Company</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $participant->company ?: 'N/A' }}</dd>
                    </div>
                    
                    @if($participant->user)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $participant->user->email }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $participant->user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($participant->user->status) }}
                                </span>
                            </dd>
                        </div>
                    @endif
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Joined Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $participant->created_at->format('M d, Y') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $participant->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Courses Card -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Active Courses</h2>
            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-medium">
                {{ $activeRegistrations->count() }} courses
            </span>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Course
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Schedule
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Location
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Progress
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($activeRegistrations as $registration)
                            @php
                                $class = $registration->class;
                                $course = $class->course;
                                $totalDays = $class->duration_day;
                                $daysPassed = now()->diffInDays($class->start_date);
                                $daysPassed = min($daysPassed, $totalDays);
                                $progressPercentage = $totalDays > 0 ? ($daysPassed / $totalDays) * 100 : 0;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $course->course_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Class ID: {{ $class->class_id }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $class->start_date->format('M d, Y') }} - {{ $class->end_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $class->duration_day }} days
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $class->location }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Room: {{ $class->room }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ round($progressPercentage) }}% complete
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No active courses.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Completed Courses Card -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Completed Courses</h2>
            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-medium">
                {{ $completedRegistrations->count() }} courses
            </span>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Course
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Completed On
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Certificate
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($completedRegistrations as $registration)
                            @php
                                $class = $registration->class;
                                $course = $class->course;
                                $certificate = $participant->certificates->where('course_name', $course->course_name)->first();
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $course->course_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Class ID: {{ $class->class_id }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $class->end_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Duration: {{ $class->duration_day }} days
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($certificate)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Certificate Issued
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $certificate->certificate_number }}
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            No Certificate
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No completed courses.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pending Registrations Card -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Pending Registrations</h2>
            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-medium">
                {{ $pendingRegistrations->count() }} registrations
            </span>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Course
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Registration Date
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pendingRegistrations as $registration)
                            @php
                                $class = $registration->class;
                                $course = $class->course;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $course->course_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Class ID: {{ $class->class_id }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $registration->created_at->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($registration->payment_status === 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Paid
                                        </span>
                                    @elseif($registration->payment_status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Unpaid
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No pending registrations.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Certificates Card -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Certificates</h2>
            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-medium">
                {{ $participant->certificates->count() }} certificates
            </span>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Certificate Number
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Course
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Issue Date
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Expiry Date
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($participant->certificates->sortByDesc('created_at') as $certificate)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $certificate->certificate_number }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $certificate->course_name }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $certificate->created_at->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm {{ $certificate->expiry_date < now() ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                        {{ $certificate->expiry_date->format('M d, Y') }}
                                    </div>
                                    @if($certificate->expiry_date < now())
                                        <div class="text-xs text-red-500">
                                            Expired
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No certificates issued.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
