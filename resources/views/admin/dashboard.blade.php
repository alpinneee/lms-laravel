@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="ml-4 text-sm font-medium text-gray-500">Overview</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h1 class="text-xl leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                <b>Dashboard</b><hr>
            </h1>
        
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Users -->
        <div class="stats-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="stats-icon bg-blue-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</dd>
                    </dl>
                </div>
            </div>
          
        </div>

        <!-- Total Courses -->
        <div class="stats-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="stats-icon bg-indigo-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Courses</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_courses']) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="stats-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="stats-icon bg-green-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                        <dd class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_revenue'], 2) }}</dd>
                    </dl>
                </div>
            </div>
          
        </div>

        <!-- Total Certificates -->
        <div class="stats-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="stats-icon bg-yellow-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Certificates Issued</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_certificates']) }}</dd>
                    </dl>
                </div>
            </div>
          
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Monthly Registrations Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Monthly Registrations</h3>
                <p class="text-sm text-gray-500">Course registration trends over the last 6 months</p>
            </div>
            <div class="card-body">
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-900">Chart will be here</p>
                        <p class="text-sm text-gray-500">Chart.js integration coming soon</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Revenue Overview</h3>
                <p class="text-sm text-gray-500">Monthly revenue trends</p>
            </div>
            <div class="card-body">
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-900">Revenue Chart</p>
                        <p class="text-sm text-gray-500">Chart.js integration coming soon</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Recent Activities & Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activities -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Recent Activities</h3>
                    <p class="text-sm text-gray-500">Latest registrations and payments</p>
                </div>
                <div class="card-body p-0">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @forelse($recentActivities as $index => $activity)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3 px-6 py-4">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-{{ $activity['color'] }}-500 flex items-center justify-center ring-8 ring-white">
                                                    @if($activity['icon'] === 'user-plus')
                                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                        </svg>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-900">{{ $activity['message'] }}</p>
                                                <p class="text-xs text-gray-500">{{ $activity['time']->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="px-6 py-8 text-center">
                                    <p class="text-gray-500">No recent activities</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="space-y-6">
            <!-- Active Registrations -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Active Registrations</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_registrations']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Payments -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pending Payments</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_payments']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Distribution -->
            <div class="card">
                <div class="card-header">
                    <h4 class="text-sm font-medium text-gray-900">User Types</h4>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        @foreach($userDistribution as $distribution)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600 capitalize">{{ $distribution->usertype }}</span>
                                <span class="text-sm font-bold text-gray-900">{{ $distribution->count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificate Expiry Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-medium text-gray-900">Expiring Certificates</h3>
            <a href="{{ route('admin.reports.certificate-expired') }}" class="text-sm text-primary-800 hover:text-primary-900">View all</a>
        </div>
        <div class="card-body p-0">
            <div class="divide-y divide-gray-200">
                @forelse($expiringCertificates as $certificate)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $certificate->name }}</p>
                                <p class="text-xs text-gray-500">{{ $certificate->certificate_number }}</p>
                                <p class="text-xs text-red-500">Expires: {{ $certificate->expiry_date->format('d M Y') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $certificate->expiry_date->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500">
                        No certificates expiring soon.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 