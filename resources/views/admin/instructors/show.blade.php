@extends('layouts.admin')

@section('title', 'Instructor Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Instructor Details</h1>
            <p class="mt-1 text-sm text-gray-500">View instructor information and assignments</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.instructors.edit', $instructor) }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.instructors.index') }}" class="btn btn-secondary">
                Back to List
            </a>
        </div>
    </div>

    <!-- Instructor Profile Card -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Instructor Profile</h2>
        </div>
        <div class="card-body">
            <div class="flex flex-col md:flex-row">
                <!-- Instructor Photo -->
                <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                    @if($instructor->photo)
                        <img src="{{ Storage::url($instructor->photo) }}" alt="{{ $instructor->full_name }}" class="h-40 w-40 rounded-lg object-cover">
                    @else
                        <div class="h-40 w-40 rounded-lg bg-primary-600 flex items-center justify-center text-white text-4xl font-bold">
                            {{ substr($instructor->full_name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <!-- Instructor Details -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $instructor->full_name }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $instructor->phone_number }}</dd>
                    </div>
                    
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $instructor->address }}</dd>
                    </div>
                    
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Proficiency/Expertise</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $instructor->proficiency }}</dd>
                    </div>
                    
                    @php
                        $user = $instructor->users->first();
                    @endphp
                    
                    @if($user)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </dd>
                        </div>
                    @endif
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Joined Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $instructor->created_at->format('M d, Y') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $instructor->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Classes Card -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Assigned Classes</h2>
            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                {{ $instructor->classes->count() }} classes
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
                                Status
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Students
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($instructor->classes as $class)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $class->course->course_name }}
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
                                    @php
                                        $now = now();
                                        $status = 'upcoming';
                                        $statusColor = 'blue';
                                        
                                        if ($class->start_date <= $now && $class->end_date >= $now) {
                                            $status = 'active';
                                            $statusColor = 'green';
                                        } elseif ($class->end_date < $now) {
                                            $status = 'completed';
                                            $statusColor = 'gray';
                                        }
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $class->registrations->where('reg_status', 'approved')->count() }} students
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No classes assigned to this instructor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Certificates Issued Card -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Certificates Issued</h2>
            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                {{ $instructor->certificates->count() }} certificates
            </span>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Certificate
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Recipient
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
                        @forelse($instructor->certificates->sortByDesc('created_at')->take(5) as $certificate)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $certificate->certificate_number }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $certificate->course_name }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $certificate->name }}
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
                                    No certificates issued by this instructor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                @if($instructor->certificates->count() > 5)
                    <div class="px-4 py-3 bg-gray-50 text-center text-sm text-gray-500">
                        Showing 5 of {{ $instructor->certificates->count() }} certificates
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
