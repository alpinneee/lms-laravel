@extends('layouts.admin')

@section('title', 'Certificates Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Certificates Management</h1>
            <p class="mt-1 text-sm text-gray-500">Manage certificate templates, issuance, and tracking</p>
        </div>
        <div>
            <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Issue Certificate
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Certificates -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Certificates</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('admin.certificates.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        View all<span class="sr-only"> certificates</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Valid Certificates -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Valid Certificates</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['valid'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('admin.certificates.index', ['status' => 'valid']) }}" class="font-medium text-green-600 hover:text-green-500">
                        View valid<span class="sr-only"> certificates</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Expired Certificates -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Expired Certificates</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['expired'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('admin.certificates.index', ['status' => 'expired']) }}" class="font-medium text-red-600 hover:text-red-500">
                        View expired<span class="sr-only"> certificates</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Expiring Soon -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Expiring Soon</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['expiring_soon'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('admin.certificates.index', ['status' => 'expiring_soon']) }}" class="font-medium text-yellow-600 hover:text-yellow-500">
                        View expiring<span class="sr-only"> certificates</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Filters</h2>
            <button type="button" id="filter-toggle" class="btn btn-sm btn-secondary">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filters
            </button>
        </div>
        
        <div id="filters-panel" class="card-body border-t border-gray-200 {{ request()->hasAny(['status', 'course_id', 'participant_id', 'instructor_id', 'date_range']) ? '' : 'hidden' }}">
            <form method="GET" action="{{ route('admin.certificates.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="form-select mt-1">
                        <option value="">All Statuses</option>
                        <option value="valid" {{ $status === 'valid' ? 'selected' : '' }}>Valid</option>
                        <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="expiring_soon" {{ $status === 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                    </select>
                </div>
                
                <!-- Course Filter -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
                    <select name="course_id" id="course_id" class="form-select mt-1">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Participant Filter -->
                <div>
                    <label for="participant_id" class="block text-sm font-medium text-gray-700">Participant</label>
                    <select name="participant_id" id="participant_id" class="form-select mt-1">
                        <option value="">All Participants</option>
                        @foreach($participants as $participant)
                            <option value="{{ $participant->id }}" {{ $participantId == $participant->id ? 'selected' : '' }}>
                                {{ $participant->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Instructor Filter -->
                <div>
                    <label for="instructor_id" class="block text-sm font-medium text-gray-700">Instructor</label>
                    <select name="instructor_id" id="instructor_id" class="form-select mt-1">
                        <option value="">All Instructors</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" {{ $instructorId == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Date Range Filter -->
                <div class="md:col-span-2 lg:col-span-4">
                    <label for="date_range" class="block text-sm font-medium text-gray-700">Issue Date Range</label>
                    <input type="text" name="date_range" id="date_range" value="{{ $dateRange }}" 
                           class="form-input mt-1" placeholder="MM/DD/YYYY - MM/DD/YYYY">
                </div>
                
                <!-- Filter Actions -->
                <div class="md:col-span-2 lg:col-span-4 flex justify-end space-x-2">
                    <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary">
                        Clear Filters
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Certificates Table -->
    <div class="card">
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
                                Course
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Issued By
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dates
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
                        @forelse($certificates as $certificate)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $certificate->certificate_number }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $certificate->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $certificate->participant->user->email ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $certificate->course->course_name }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $certificate->instructure->full_name }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        Issued: {{ $certificate->issue_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs {{ $certificate->isExpired() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                        Expires: {{ $certificate->expiry_date->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium actions-cell">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.certificates.show', $certificate) }}" class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        
                                        <a href="{{ route('admin.certificates.edit', $certificate) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </a>
                                        
                                        @if($certificate->getRawOriginal('pdf_url'))
                                            <a href="{{ route('admin.certificates.download', $certificate) }}" class="text-green-600 hover:text-green-900" target="_blank">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        
                                        <form action="{{ route('admin.certificates.destroy', $certificate) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this certificate?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No certificates found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($certificates->hasPages())
        <div class="card">
            <div class="card-body">
                {{ $certificates->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<style>
    .daterangepicker {
        font-family: inherit;
    }
    
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
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter toggle
        const filterToggle = document.getElementById('filter-toggle');
        const filtersPanel = document.getElementById('filters-panel');
        
        filterToggle.addEventListener('click', function() {
            filtersPanel.classList.toggle('hidden');
        });
        
        // Auto-submit form when filters change
        const filterSelects = document.querySelectorAll('#status, #course_id, #participant_id, #instructor_id');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });
        
        // Date range picker
        $('#date_range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'MM/DD/YYYY'
            }
        });
        
        $('#date_range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });
        
        $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
@endpush
@endsection
