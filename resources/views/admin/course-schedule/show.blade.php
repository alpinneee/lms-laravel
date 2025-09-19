@extends('layouts.admin')

@section('title', 'Course Schedule Detail')

@section('content')
<div class="space-y-3">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-lg font-medium text-gray-900">Course Schedule Detail</h1>
        <a href="{{ route('admin.course-schedule.index') }}" class="bg-gray-600 text-white px-3 py-1.5 rounded text-xs hover:bg-gray-700">
            Back
        </a>
    </div>

    <!-- Course Details -->
    <div class="bg-white shadow rounded border p-4">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-medium text-gray-900">{{ $class->course->course_name }}</h2>
            <a href="{{ route('admin.courses.edit-class', [$class->course_id, $class->id]) }}" class="bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700">
                Edit
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="text-gray-500 block">Date</span>
                <span class="text-gray-900">{{ $class->start_date->format('j M') }} - {{ $class->end_date->format('j M Y') }}</span>
            </div>
            <div>
                <span class="text-gray-500 block">Registration</span>
                <span class="text-gray-900">{{ $class->reg_start_date ? $class->reg_start_date->format('j M') : '-' }} - {{ $class->reg_end_date ? $class->reg_end_date->format('j M') : '-' }}</span>
            </div>
            <div>
                <span class="text-gray-500 block">Location</span>
                <span class="text-gray-900">{{ $class->location }}{{ $class->room ? ' - ' . $class->room : '' }}</span>
            </div>
            <div>
                <span class="text-gray-500 block">Price / Quota</span>
                <span class="text-gray-900">Rp {{ number_format($class->course->price ?? 0, 0, ',', '.') }} / {{ $class->quota }}</span>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white shadow rounded border">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-4 px-3" aria-label="Tabs">
                <button class="tab-button active border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs" data-tab="participant">
                    Participants ({{ $class->registrations->count() }})
                </button>
                <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs" data-tab="instructure">
                    Instructors
                </button>
                <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs" data-tab="elearning">
                    Materials
                </button>
            </nav>
        </div>

        <!-- Participant Tab -->
        <div id="participant-tab" class="tab-content p-3">
            <div class="flex items-center justify-end mb-2">
                <a href="{{ route('admin.courses.add-participant', [$class->course_id, $class->id]) }}" class="bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700">
                    Add Participant
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($class->registrations as $index => $registration)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-2 text-xs text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-2 py-2 text-xs text-gray-900 font-medium">{{ $registration->participant->user->name }}</td>
                                <td class="px-2 py-2">
                                    @if($registration->payments->where('payment_status', 'approved')->count() > 0)
                                        <span class="px-1.5 py-0.5 text-xs rounded bg-green-100 text-green-700">
                                            Paid
                                        </span>
                                    @elseif($registration->payments->where('payment_status', 'pending')->count() > 0)
                                        <span class="px-1.5 py-0.5 text-xs rounded bg-yellow-100 text-yellow-700">
                                            Pending
                                        </span>
                                    @else
                                        <span class="px-1.5 py-0.5 text-xs rounded bg-red-100 text-red-700">
                                            Unpaid
                                        </span>
                                    @endif
                                </td>
                                <td class="px-2 py-2">
                                    <div class="flex items-center gap-1">
                                        <button class="text-gray-500 hover:text-gray-700" title="View">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <a href="{{ route('admin.certificates.create', ['participant_id' => $registration->participant_id, 'class_id' => $class->id]) }}" class="text-green-500 hover:text-green-700" title="Issue Certificate">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="#" class="inline" onsubmit="return confirm('Remove participant?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Remove">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-2 py-4 text-center text-xs text-gray-500">
                                    No participants found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Instructure Tab -->
        <div id="instructure-tab" class="tab-content p-3 hidden">
            <div class="flex items-center justify-end mb-2">
                <a href="{{ route('admin.courses.add-instructor', [$class->course_id, $class->id]) }}" class="bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700">
                    Add Instructor
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Specialization</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($class->instructures as $index => $instructor)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-2 text-xs text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-2 py-2 text-xs text-gray-900 font-medium">{{ $instructor->full_name }}</td>
                                <td class="px-2 py-2 text-xs text-gray-600">{{ $instructor->specialization }}</td>
                                <td class="px-2 py-2">
                                    <div class="flex items-center gap-1">
                                        <button class="text-gray-500 hover:text-gray-700" title="View">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <a href="{{ route('admin.certificates.create', ['instructor_id' => $instructor->id, 'class_id' => $class->id]) }}" class="text-green-500 hover:text-green-700" title="Issue Certificate">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.courses.remove-instructor', [$class->course_id, $class->id, $instructor->id]) }}" class="inline" onsubmit="return confirm('Remove this instructor?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Remove">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-2 py-4 text-center text-xs text-gray-500">
                                    No instructors assigned
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- E-Learning Tab -->
        <div id="elearning-tab" class="tab-content p-3 hidden">
            <div class="flex items-center justify-end mb-2">
                <a href="{{ route('admin.courses.add-material', [$class->course_id, $class->id]) }}" class="bg-purple-600 text-white px-2 py-1 rounded text-xs hover:bg-purple-700">
                    Add Material
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Day</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($class->materials as $material)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-2 text-xs text-gray-900">Day {{ $material->day }}</td>
                                <td class="px-2 py-2 text-xs text-gray-900 font-medium">{{ $material->title }}</td>
                                <td class="px-2 py-2 text-xs text-gray-600">{{ $material->size }}</td>
                                <td class="px-2 py-2">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ $material->file_url }}" target="_blank" class="text-blue-500 hover:text-blue-700" title="Download">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.courses.remove-material', [$class->course_id, $class->id, $material->id]) }}" class="inline" onsubmit="return confirm('Remove this material?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Remove">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-2 py-4 text-center text-xs text-gray-500">
                                    No materials uploaded
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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