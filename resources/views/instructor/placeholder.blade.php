@extends('layouts.instructor')

@section('title', $title ?? 'Under Development')

@section('breadcrumb')
    @if(isset($breadcrumbs))
        @foreach($breadcrumbs as $breadcrumb)
            <li>
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-4 text-sm font-medium text-gray-500">{{ $breadcrumb }}</span>
                </div>
            </li>
        @endforeach
    @endif
@endsection

@section('content')
<div class="min-h-96 flex items-center justify-center">
    <div class="text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ $title ?? 'Feature Under Development' }}</h1>
        <p class="mt-2 text-gray-600">{{ $description ?? 'This feature is currently being developed and will be available soon.' }}</p>
        
        @if(isset($features) && count($features) > 0)
            <div class="mt-8 max-w-md mx-auto">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Planned Features:</h3>
                <ul class="text-left space-y-2">
                    @foreach($features as $feature)
                        <li class="flex items-center text-gray-600">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ url()->previous() }}" class="btn-secondary">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Go Back
            </a>
            <a href="{{ route('instructor.dashboard') }}" class="btn-primary">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                </svg>
                Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
