@extends('layouts.admin')

@section('title', 'Course Classes')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $course->course_name }} - Classes</h1>
            <p class="mt-1 text-sm text-gray-500">Manage classes for this course</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.courses.create-class', $course) }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Class
            </a>
            <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-secondary">
                Back to Course
            </a>
        </div>
    </div>

    <!-- Classes List -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">All Classes</h2>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Class ID
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Schedule
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Registration Period
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Location
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Enrollment
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider actions-header">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($classes as $class)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $class->class_id ?? 'CLS-' . $class->id }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $class->instructures->count() }} instructor(s)
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $class->start_date->format('M d, Y') }} - {{ $class->end_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $class->duration_day }} days
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $class->start_reg_date->format('M d, Y') }} - {{ $class->end_reg_date->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $class->location }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Room: {{ $class->room }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $class->registrations->where('reg_status', 'approved')->count() }} / {{ $class->quota }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $class->registrations->where('reg_status', 'pending')->count() }} pending
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        ${{ number_format($class->price, 2) }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.courses.update-class-status', ['course' => $course, 'class' => $class]) }}" method="POST">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="active" {{ $class->status === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $class->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="completed" {{ $class->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium actions-cell">
                                    <div class="action-buttons">
                                        <!-- View Details -->
                                        <a href="{{ route('admin.courses.show-class', ['course' => $course, 'class' => $class]) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        
                                        <!-- Edit Class -->
                                        <a href="{{ route('admin.courses.edit-class', ['course' => $course, 'class' => $class]) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit Class">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </a>
                                        
                                        <!-- Add Participant -->
                                        <a href="{{ route('admin.courses.add-participant', ['course' => $course, 'class' => $class]) }}" class="text-green-600 hover:text-green-900" title="Add Participant">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No classes have been created for this course yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($classes->hasPages())
        <div class="card">
            <div class="card-body">
                {{ $classes->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
