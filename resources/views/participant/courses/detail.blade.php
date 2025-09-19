@extends('layouts.participant')

@section('title', 'Course Schedule Detail')

@section('content')
<div class="space-y-3">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-lg font-medium text-gray-900">Course Schedule Detail</h1>
        <a href="{{ route('participant.courses.index') }}" class="bg-gray-600 text-white px-3 py-1.5 rounded text-xs hover:bg-gray-700">
            Back
        </a>
    </div>

    <!-- Course Details -->
    <div class="bg-white shadow rounded border p-4">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-medium text-gray-900">{{ $class->course->course_name }}</h2>
            <a href="{{ route('participant.certificates.request', ['class_id' => $class->id]) }}" class="bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700">
                Request Certificate
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
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($class->registrations as $index => $registration)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-2 text-xs text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-2 py-2 text-xs text-gray-900 font-medium">{{ $registration->participant->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-2 py-4 text-center text-xs text-gray-500">
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
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Specialization</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($class->instructures as $index => $instructor)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-2 text-xs text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-2 py-2 text-xs text-gray-900 font-medium">{{ $instructor->full_name }}</td>
                                <td class="px-2 py-2 text-xs text-gray-600">{{ $instructor->specialization }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-2 py-4 text-center text-xs text-gray-500">
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
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Day</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Download</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($class->materials as $material)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-2 text-xs text-gray-900">Day {{ $material->day }}</td>
                                <td class="px-2 py-2 text-xs text-gray-900 font-medium">{{ $material->title }}</td>
                                <td class="px-2 py-2 text-xs text-gray-600">{{ $material->size }}</td>
                                <td class="px-2 py-2">
                                    <a href="{{ $material->file_url }}" target="_blank" class="text-blue-500 hover:text-blue-700 text-xs">
                                        Download
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-2 py-4 text-center text-xs text-gray-500">
                                    No materials available
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