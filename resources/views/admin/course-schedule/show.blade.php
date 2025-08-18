@extends('layouts.admin')

@section('title', 'Course Schedule Detail')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Course Schedule Detail</h1>
        <a href="{{ route('admin.course-schedule.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded text-sm hover:bg-gray-700">
            Back
        </a>
    </div>

    <!-- Course Details -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-medium text-gray-900 mb-6">{{ $class->course->course_name }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex">
                    <span class="text-gray-600 w-32">Date :</span>
                    <span class="text-gray-900">{{ $class->start_date->format('j M Y') }} - {{ $class->end_date->format('j M Y') }}</span>
                </div>
                
                <div class="flex">
                    <span class="text-gray-600 w-32">Registration Date :</span>
                    <span class="text-gray-900">{{ $class->reg_start_date ? $class->reg_start_date->format('j M Y') : 'Not set' }} - {{ $class->reg_end_date ? $class->reg_end_date->format('j M Y') : 'Not set' }}</span>
                </div>
                
                <div class="flex">
                    <span class="text-gray-600 w-32">Location :</span>
                    <span class="text-gray-900">{{ $class->location }}</span>
                </div>
                
                <div class="flex">
                    <span class="text-gray-600 w-32">Room :</span>
                    <span class="text-gray-900">{{ $class->room }}</span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex">
                    <span class="text-gray-600 w-32">Price :</span>
                    <span class="text-gray-900">Rp {{ number_format($class->course->price ?? 0, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex">
                    <span class="text-gray-600 w-32">Quota :</span>
                    <span class="text-gray-900">{{ $class->quota }} participants</span>
                </div>
                
                <div class="flex">
                    <span class="text-gray-600 w-32">Status :</span>
                    <span class="text-green-600 font-medium">{{ ucfirst($class->status) }}</span>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.courses.edit-class', [$class->course_id, $class->id]) }}" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">
                Edit Schedule
            </a>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white shadow rounded-lg">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button class="tab-button active border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="participant">
                    Participant
                </button>
                <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="instructure">
                    Instructure
                </button>
                <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="elearning">
                    E-Learning
                </button>
            </nav>
        </div>

        <!-- Participant Tab -->
        <div id="participant-tab" class="tab-content p-6">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('admin.courses.add-participant', [$class->course_id, $class->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                    Add Participant
                </a>
                <span class="text-sm text-gray-500">{{ $class->registrations->count() }} Participants</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($class->registrations as $index => $registration)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $registration->participant->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($registration->payments->where('payment_status', 'approved')->count() > 0)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Paid
                                        </span>
                                    @elseif($registration->payments->where('payment_status', 'pending')->count() > 0)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Unpaid
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <button class="text-gray-600 hover:text-gray-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </button>
                                        <button class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </button>
                                        <button class="text-red-600 hover:text-red-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No participants found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Instructure Tab -->
        <div id="instructure-tab" class="tab-content p-6 hidden">
            <div class="text-center text-gray-500">
                Instructure management coming soon
            </div>
        </div>

        <!-- E-Learning Tab -->
        <div id="elearning-tab" class="tab-content p-6 hidden">
            <div class="text-center text-gray-500">
                E-Learning materials coming soon
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Add active class to clicked button
            this.classList.add('active', 'border-blue-500', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.remove('hidden');
        });
    });
});
</script>
@endsection