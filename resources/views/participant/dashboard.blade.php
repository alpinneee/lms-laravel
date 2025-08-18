@extends('layouts.participant')

@section('title', 'Student Dashboard')

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
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Student Dashboard
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Welcome back, {{ auth()->user()->name }}! Track your learning progress and discover new courses.
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <button type="button" class="btn-secondary btn-sm mr-3">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Browse Courses
            </button>
            <button type="button" class="btn-primary btn-sm">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Enroll Now
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Enrolled Courses -->
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
                        <dt class="text-sm font-medium text-gray-500 truncate">Enrolled Courses</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $stats['enrolled_courses'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Completed Courses -->
        <div class="stats-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="stats-icon bg-green-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $stats['completed_courses'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Certificates Earned -->
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
                        <dt class="text-sm font-medium text-gray-500 truncate">Certificates</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $stats['certificates_earned'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="stats-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="stats-icon bg-red-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
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
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Current Courses & Progress -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Current Learning Progress -->
            @if(count($learningProgress) > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Current Learning Progress</h3>
                    <p class="text-sm text-gray-500">Your ongoing courses</p>
                </div>
                <div class="card-body p-0">
                    <div class="space-y-4 p-6">
                        @foreach($learningProgress as $progress)
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $progress['course_name'] }}</h4>
                                    <span class="text-sm text-gray-500">{{ $progress['progress_percentage'] }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                                    <div class="bg-primary-800 h-2 rounded-full" style="width: {{ $progress['progress_percentage'] }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>{{ $progress['start_date']->format('M d, Y') }} - {{ $progress['end_date']->format('M d, Y') }}</span>
                                    <span>{{ $progress['location'] }}, {{ $progress['room'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Available Courses -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Available Courses</h3>
                    <p class="text-sm text-gray-500">Discover new learning opportunities</p>
                </div>
                <div class="card-body p-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                        @forelse($availableCourses as $class)
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="aspect-w-16 aspect-h-9 mb-3">
                                    <img src="{{ $class->course->image ?: 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&h=200&fit=crop' }}" 
                                         alt="{{ $class->course->course_name }}" 
                                         class="w-full h-32 object-cover rounded">
                                </div>
                                <h4 class="font-medium text-gray-900 mb-2">{{ $class->course->course_name }}</h4>
                                <p class="text-sm text-gray-500 mb-3">{{ Str::limit($class->course->description, 80) }}</p>
                                <div class="flex justify-between items-center text-sm text-gray-500 mb-3">
                                    <span>Rp {{ number_format($class->price, 0, ',', '.') }}</span>
                                    <span>{{ $class->getAvailableSpotsAttribute() }}/{{ $class->quota }} spots</span>
                                </div>
                                <button class="btn-primary btn-sm w-full">Enroll Now</button>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No courses available for enrollment</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Certificates & Activities -->
        <div class="space-y-6">
            <!-- My Certificates -->
            @if(count($myCertificates) > 0)
            <div class="card">
                <div class="card-header">
                    <h4 class="text-sm font-medium text-gray-900">My Certificates</h4>
                    <p class="text-xs text-gray-500">Your achievements</p>
                </div>
                <div class="card-body p-0">
                    <div class="space-y-3 p-4">
                        @foreach($myCertificates as $certificate)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $certificate->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $certificate->certificate_number }}</p>
                                    <p class="text-xs text-gray-400">{{ $certificate->issue_date->format('M d, Y') }}</p>
                                </div>
                                <button class="btn-primary btn-sm">
                                    Download
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Payment History -->
            @if(count($paymentHistory) > 0)
            <div class="card">
                <div class="card-header">
                    <h4 class="text-sm font-medium text-gray-900">Payment History</h4>
                </div>
                <div class="card-body p-0">
                    <div class="space-y-3 p-4">
                        @foreach($paymentHistory as $payment)
                            <div class="flex items-center justify-between p-3 border rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500">{{ $payment->registration->class->course->course_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $payment->payment_date->format('M d, Y') }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $payment->status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Activities -->
            <div class="card">
                <div class="card-header">
                    <h4 class="text-sm font-medium text-gray-900">Recent Activities</h4>
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
                                                    @if($activity['icon'] === 'book-open')
                                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                        </svg>
                                                    @elseif($activity['icon'] === 'credit-card')
                                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
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
    </div>
</div>
@endsection 