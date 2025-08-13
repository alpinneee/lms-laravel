@extends('layouts.admin')

@section('title', 'Certificate Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Certificate Details</h1>
            <p class="mt-1 text-sm text-gray-500">View certificate information and details</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.certificates.edit', $certificate) }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                Edit Certificate
            </a>
            <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary">
                Back to List
            </a>
        </div>
    </div>

    <!-- Certificate Status Banner -->
    @if($certificate->isExpired())
        <div class="bg-red-50 border-l-4 border-red-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        This certificate has expired on {{ $certificate->expiry_date->format('F j, Y') }}.
                    </p>
                </div>
            </div>
        </div>
    @elseif($certificate->status === 'revoked')
        <div class="bg-gray-50 border-l-4 border-gray-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-700">
                        This certificate has been revoked.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Certificate Information -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">Certificate Information</h2>
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Certificate Number</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $certificate->certificate_number }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @if($certificate->isValid())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Valid
                                    </span>
                                @elseif($certificate->isExpired())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                @elseif($certificate->status === 'revoked')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Revoked
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Recipient Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Course</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->course->course_name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Issue Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->issue_date->format('F j, Y') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Expiry Date</dt>
                            <dd class="mt-1 text-sm {{ $certificate->isExpired() ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                {{ $certificate->expiry_date->format('F j, Y') }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Issued By</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->instructure->full_name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->created_at->format('F j, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Certificate Actions -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">Actions</h2>
                </div>
                <div class="card-body space-y-4">
                    @if($certificate->getRawOriginal('pdf_url'))
                        <a href="{{ route('admin.certificates.download', $certificate) }}" class="btn btn-primary w-full flex justify-center" target="_blank">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download Certificate
                        </a>
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        No PDF file attached to this certificate.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <a href="{{ route('admin.certificates.edit', $certificate) }}" class="btn btn-secondary w-full flex justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit Certificate
                    </a>
                    
                    <form action="{{ route('admin.certificates.destroy', $certificate) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this certificate?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-full flex justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Certificate
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Recipient Information -->
            <div class="card mt-6">
                <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">Recipient Information</h2>
                </div>
                <div class="card-body">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if($certificate->participant->photo)
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($certificate->participant->photo) }}" alt="{{ $certificate->participant->full_name }}">
                            @else
                                <div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold">
                                    {{ substr($certificate->participant->full_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">{{ $certificate->participant->full_name }}</h3>
                            <p class="text-sm text-gray-500">{{ $certificate->participant->user->email ?? 'No email' }}</p>
                        </div>
                    </div>
                    
                    <dl class="grid grid-cols-1 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->participant->phone_number }}</dd>
                        </div>
                        
                        @if($certificate->participant->job_title || $certificate->participant->company)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Employment</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($certificate->participant->job_title && $certificate->participant->company)
                                        {{ $certificate->participant->job_title }} at {{ $certificate->participant->company }}
                                    @elseif($certificate->participant->job_title)
                                        {{ $certificate->participant->job_title }}
                                    @elseif($certificate->participant->company)
                                        {{ $certificate->participant->company }}
                                    @endif
                                </dd>
                            </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Certificates</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $certificate->participant->certificates->count() }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
