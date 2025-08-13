@extends('layouts.participant')

@section('title', 'Payment Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="ml-4 text-sm font-medium text-gray-500">Payment Management</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Payment Management
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Manage your course payments and upload payment proofs.
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="{{ route('participant.payment.history') }}" class="btn-secondary btn-sm mr-3">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Payment History
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <!-- Total Registrations -->
        <div class="stats-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="stats-icon bg-blue-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Registrations</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_registrations'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="stats-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="stats-icon bg-yellow-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Pending Payments</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $stats['pending_payments'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Total Paid -->
        <div class="stats-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="stats-icon bg-green-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Paid</dt>
                        <dd class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_paid'], 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Registrations Table -->
    <div class="card">
        <div class="card-header border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Course Registrations</h3>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="table-header">Course</th>
                            <th class="table-header">Registration Date</th>
                            <th class="table-header">Status</th>
                            <th class="table-header">Payment Status</th>
                            <th class="table-header">Total Payment</th>
                            <th class="table-header">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($registrations as $registration)
                            <tr class="hover:bg-gray-50">
                                <td class="table-cell">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ $registration->class->course->image ? asset('storage/' . $registration->class->course->image) : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=100&h=100&fit=crop' }}" 
                                                 alt="{{ $registration->class->course->course_name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $registration->class->course->course_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $registration->class->start_date->format('d M Y') }} - {{ $registration->class->end_date->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="table-cell">
                                    <div class="text-sm text-gray-900">{{ $registration->reg_date->format('d M Y') }}</div>
                                </td>
                                <td class="table-cell">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $registration->reg_status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($registration->reg_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($registration->reg_status) }}
                                    </span>
                                </td>
                                <td class="table-cell">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $registration->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                           ($registration->payment_status === 'partial' ? 'bg-blue-100 text-blue-800' : 
                                            ($registration->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst($registration->payment_status) }}
                                    </span>
                                </td>
                                <td class="table-cell">
                                    <div class="text-sm text-gray-900">${{ number_format($registration->payment, 2) }}</div>
                                    @if($registration->payment_status === 'partial')
                                        <div class="text-xs text-gray-500">Paid: ${{ number_format($registration->payments->where('status', 'verified')->sum('amount'), 2) }}</div>
                                    @endif
                                </td>
                                <td class="table-cell text-right text-sm font-medium">
                                    @if($registration->payment_status !== 'paid')
                                        <a href="{{ route('participant.payment.upload', $registration->id) }}" class="btn-primary btn-xs">
                                            Upload Payment
                                        </a>
                                    @else
                                        <span class="text-green-600">Fully Paid</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No registrations found</h3>
                                        <p class="mt-1 text-sm text-gray-500">You haven't registered for any courses yet.</p>
                                        <div class="mt-6">
                                            <a href="{{ route('participant.courses.browse') }}" class="btn-primary">
                                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                                Browse Courses
                                            </a>
                                        </div>
                                    </div>
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