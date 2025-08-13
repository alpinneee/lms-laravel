@extends('layouts.instructor')

@section('title', 'Certificate Details')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <a href="{{ route('instructor.certificates.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Certificates</a>
    </li>
    <li class="flex items-center">
        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Certificate Details</span>
    </li>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Certificate #{{ $certificate->certificate_number }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Issued on {{ $certificate->issue_date->format('F d, Y') }}
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="{{ route('instructor.certificates.index') }}" class="btn-secondary btn-sm mr-3">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to List
            </a>
            
            @if($certificate->getRawOriginal('pdf_url'))
                <a href="{{ route('instructor.certificates.download', $certificate) }}" class="btn-primary btn-sm" target="_blank">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Certificate
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <!-- Certificate Details -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white shadow overflow-hidden rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Certificate Information</h3>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Certificate Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->certificate_number }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm">
                                @if($certificate->isExpired())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                @elseif($certificate->isExpiringSoon())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Expiring Soon
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Valid
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Issue Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->issue_date->format('F d, Y') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Expiry Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $certificate->expiry_date ? $certificate->expiry_date->format('F d, Y') : 'No Expiry' }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Course</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->course->course_name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Course Duration</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->course->duration ?? 'N/A' }} {{ Str::plural('hour', $certificate->course->duration ?? 0) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Certificate Preview -->
            @if($certificate->getRawOriginal('pdf_url'))
                <div class="bg-white shadow overflow-hidden rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Certificate Preview</h3>
                    </div>
                    <div class="p-6">
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <iframe src="{{ asset('storage/' . $certificate->getRawOriginal('pdf_url')) }}" class="w-full h-96"></iframe>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white shadow overflow-hidden rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Certificate Preview</h3>
                    </div>
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-900">No PDF Available</p>
                        <p class="mt-1 text-sm text-gray-500">This certificate does not have an attached PDF file.</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Participant Info -->
            <div class="bg-white shadow overflow-hidden rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Participant Information</h3>
                </div>
                <div class="px-6 py-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if($certificate->participant->user && $certificate->participant->user->profile_photo)
                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $certificate->participant->user->profile_photo) }}" alt="{{ $certificate->participant->user->name }}">
                            @else
                                <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-primary-800 font-medium text-sm">
                                        {{ $certificate->participant->user ? substr($certificate->participant->user->name, 0, 2) : 'P' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">{{ $certificate->participant->user->name ?? 'N/A' }}</h4>
                            <p class="text-sm text-gray-500">{{ $certificate->participant->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <dl class="mt-4 space-y-3">
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->participant->phone_number ?? 'N/A' }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->participant->address ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Instructor Info -->
            <div class="bg-white shadow overflow-hidden rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Issued By</h3>
                </div>
                <div class="px-6 py-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if($certificate->instructure->user && $certificate->instructure->user->profile_photo)
                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $certificate->instructure->user->profile_photo) }}" alt="{{ $certificate->instructure->user->name }}">
                            @else
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-800 font-medium text-sm">
                                        {{ $certificate->instructure->user ? substr($certificate->instructure->user->name, 0, 2) : 'I' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">{{ $certificate->instructure->user->name ?? $certificate->instructure->full_name }}</h4>
                            <p class="text-sm text-gray-500">{{ $certificate->instructure->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    @if($certificate->instructure->proficiency)
                        <div class="mt-4">
                            <dt class="text-xs font-medium text-gray-500">Proficiency</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $certificate->instructure->proficiency }}</dd>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
