@extends('layouts.admin')

@section('title', 'User Details')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <a href="{{ route('admin.users.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">User Management</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="ml-4 text-sm font-medium text-gray-500">User Details</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center">
                <div class="h-16 w-16 flex-shrink-0 bg-primary-100 text-primary-800 rounded-full flex items-center justify-center">
                    <span class="text-xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div class="ml-4">
                    <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                        {{ $user->name }}
                    </h1>
                    <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            @{{ $user->username }}
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $user->email }}
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Last login: {{ $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex gap-3 sm:mt-0">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit User
                </a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- User Details Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden lg:col-span-2">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">User Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Personal details and account information.</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Full name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Username</dt>
                        <dd class="mt-1 text-sm text-gray-900">@{{ $user->username }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Email address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Role</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($user->userType)
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-blue-100 text-blue-800',
                                        'instructor' => 'bg-green-100 text-green-800',
                                        'participant' => 'bg-purple-100 text-purple-800',
                                        'unassigned' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $roleColor = $roleColors[$user->userType->usertype] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColor }}">
                                    {{ ucfirst($user->userType->usertype) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Unknown
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($user->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="h-2 w-2 mr-1.5 rounded-full bg-green-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="h-2 w-2 mr-1.5 rounded-full bg-red-500"></span>
                                    Inactive
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Last login</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Account created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Last updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">User Statistics</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Activity and performance metrics.</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                @if($user->isInstructor())
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Total Classes</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_classes'] ?? 0 }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Total Students</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_students'] ?? 0 }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Certificates Issued</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['certificates_issued'] ?? 0 }}</dd>
                        </div>
                    </dl>
                @elseif($user->isParticipant())
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Enrolled Courses</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['enrolled_courses'] ?? 0 }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Completed Courses</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['completed_courses'] ?? 0 }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Certificates Earned</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['certificates_earned'] ?? 0 }}</dd>
                        </div>
                    </dl>
                @elseif($user->isAdmin())
                    <div class="py-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Administrator Account</h3>
                        <p class="mt-1 text-sm text-gray-500">This user has administrative privileges.</p>
                    </div>
                @else
                    <div class="py-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Statistics Available</h3>
                        <p class="mt-1 text-sm text-gray-500">This user doesn't have any activity statistics yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection



