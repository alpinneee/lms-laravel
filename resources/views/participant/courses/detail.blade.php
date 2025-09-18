@extends('layouts.participant')

@section('title', 'Course Detail')

@section('content')
<div class="space-y-3">
    <div class="flex items-center justify-between">
        <h1 class="text-lg font-semibold text-gray-900">{{ $class->course->course_name }}</h1>
        <a href="{{ route('participant.courses.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">← Back</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border">
        @if($class->course->image)
            <img src="{{ asset('storage/' . $class->course->image) }}" alt="{{ $class->course->course_name }}" class="w-full h-32 object-cover rounded-t-lg">
        @endif
        
        <div class="p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="px-2 py-1 text-xs rounded {{ $registration->reg_status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $registration->reg_status === 'approved' ? 'Approved' : 'Pending' }}
                </span>
                <span class="text-xs text-gray-500">Rp {{ number_format($registration->payment / 1000000, 1) }}M</span>
            </div>
            
            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($class->course->description, 120) }}</p>
            
            <div class="grid grid-cols-2 gap-4 text-xs text-gray-600 mb-4">
                <div>📅 {{ $class->start_date->format('M d') }} - {{ $class->end_date->format('M d, Y') }}</div>
                <div>📍 {{ Str::limit($class->location, 20) }}</div>
                <div>⏱️ {{ $class->duration_day }} days</div>
                <div>👥 {{ $class->quota }} capacity</div>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Instructors ({{ $class->instructures->count() }})</h3>
                    <div class="space-y-1">
                        @foreach($class->instructures as $instructor)
                            <div class="flex items-center space-x-2 text-xs">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">{{ substr($instructor->full_name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ Str::limit($instructor->full_name, 15) }}</div>
                                    <div class="text-gray-500">{{ Str::limit($instructor->specialization, 20) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Participants ({{ $otherParticipants->count() }})</h3>
                    <div class="space-y-1 max-h-32 overflow-y-auto">
                        @forelse($otherParticipants as $reg)
                            <div class="flex items-center space-x-2 text-xs">
                                <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">{{ substr($reg->participant->full_name, 0, 1) }}</span>
                                </div>
                                <div class="truncate">
                                    <div class="font-medium text-gray-900">{{ Str::limit($reg->participant->full_name, 15) }}</div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500">No participants yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection