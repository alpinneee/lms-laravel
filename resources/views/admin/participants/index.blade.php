@extends('layouts.admin')

@section('title', 'Participants')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Participants</h1>
            <p class="mt-1 text-sm text-gray-500">Manage participant profiles and course registrations</p>
        </div>
        <div>
            <a href="{{ route('admin.participants.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Participant
            </a>
        </div>
    </div>

    <!-- Participants Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Participant
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Details
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Courses
                            </th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider actions-header">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($participants as $participant)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($participant->photo)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($participant->photo) }}" alt="{{ $participant->full_name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold">
                                                    {{ substr($participant->full_name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $participant->full_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $participant->user->email ?? 'No email' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $participant->phone_number }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Illuminate\Support\Str::limit($participant->address, 30) }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ ucfirst($participant->gender) }}, {{ $participant->birth_date ? $participant->birth_date->format('M d, Y') : 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        @if($participant->job_title && $participant->company)
                                            {{ $participant->job_title }} at {{ $participant->company }}
                                        @elseif($participant->job_title)
                                            {{ $participant->job_title }}
                                        @elseif($participant->company)
                                            {{ $participant->company }}
                                        @else
                                            No job information
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $status = $participant->user ? $participant->user->status : 'inactive';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $participant->registrations->where('reg_status', 'approved')->count() }} enrolled
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $participant->certificates->count() }} certificates
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium actions-cell">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.participants.show', $participant) }}" class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        
                                        <a href="{{ route('admin.participants.edit', $participant) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </a>
                                        
                                        @if($participant->user)
                                            <form action="{{ route('admin.participants.toggle-status', $participant) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="{{ $status === 'active' ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">
                                                    @if($status === 'active')
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @php
                                            $hasActiveRegistrations = $participant->registrations()
                                                ->whereHas('class', function($query) {
                                                    $query->where('end_date', '>=', now());
                                                })
                                                ->where('reg_status', 'approved')
                                                ->exists();
                                        @endphp
                                        
                                        @if(!$hasActiveRegistrations)
                                            <form action="{{ route('admin.participants.destroy', $participant) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this participant?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No participants found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
