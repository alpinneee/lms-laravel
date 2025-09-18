@extends('layouts.admin')

@section('title', 'Payment Reports')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Reports</h1>
            <p class="mt-1 text-sm text-gray-500">Track and analyze payment data across courses</p>
        </div>
        <div>
            <a href="#" class="btn btn-primary" id="export-report">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export Report
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Payments -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Payments</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_payments'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Payments</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['pending_payments'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('admin.reports.payment-report', ['status' => 'pending']) }}" class="font-medium text-yellow-600 hover:text-yellow-500">
                        View all<span class="sr-only"> pending payments</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Completed Payments -->
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Completed Payments</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['completed_payments'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('admin.reports.payment-report', ['status' => 'completed']) }}" class="font-medium text-green-600 hover:text-green-500">
                        View all<span class="sr-only"> completed payments</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-medium text-gray-900">Monthly Revenue ({{ date('Y') }})</h2>
        </div>
        <div class="card-body">
            <div class="w-full h-80">
                <canvas id="revenueChart"></canvas>
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
        
        <div id="filters-panel" class="card-body border-t border-gray-200 {{ request()->hasAny(['status', 'course_id', 'participant_id', 'payment_method', 'date_range']) ? '' : 'hidden' }}">
            <form method="GET" action="{{ route('admin.reports.payment-report') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="form-select mt-1">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="refunded" {{ $status === 'refunded' ? 'selected' : '' }}>Refunded</option>
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
                
                <!-- Payment Method Filter -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select mt-1">
                        <option value="">All Methods</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method }}" {{ $paymentMethod == $method ? 'selected' : '' }}>
                                {{ ucfirst($method) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Date Range Filter -->
                <div class="md:col-span-2 lg:col-span-4">
                    <label for="date_range" class="block text-sm font-medium text-gray-700">Payment Date Range</label>
                    <input type="text" name="date_range" id="date_range" value="{{ $dateRange }}" 
                           class="form-input mt-1" placeholder="MM/DD/YYYY - MM/DD/YYYY">
                </div>
                
                <!-- Filter Actions -->
                <div class="md:col-span-2 lg:col-span-4 flex justify-end space-x-2">
                    <a href="{{ route('admin.reports.payment-report') }}" class="btn btn-secondary">
                        Clear Filters
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Invoice
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Participant
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Course
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Method
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
                        @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $payment->invoice_number }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $payment->registration->participant->full_name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $payment->registration->participant->user->email ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $payment->registration->class->course->course_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'Not paid' }}
                                    </div>
                                    @if($payment->due_date)
                                        <div class="text-xs {{ $payment->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                            Due: {{ $payment->due_date->format('M d, Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $payment->payment_method ? ucfirst($payment->payment_method) : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($payment->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    @elseif($payment->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($payment->status === 'cancelled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Cancelled
                                        </span>
                                    @elseif($payment->status === 'refunded')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Refunded
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium actions-cell">
                                    <div class="action-buttons">
                                        <button onclick="viewPayment({{ $payment->id }})" class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        
                                        @if($payment->payment_proof)
                                            <a href="{{ asset('storage/' . $payment->payment_proof) }}" class="text-green-600 hover:text-green-900" title="View Proof" target="_blank">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No payments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
        <div class="card">
            <div class="card-body">
                {{ $payments->appends(request()->query())->links() }}
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter toggle
        const filterToggle = document.getElementById('filter-toggle');
        const filtersPanel = document.getElementById('filters-panel');
        
        filterToggle.addEventListener('click', function() {
            filtersPanel.classList.toggle('hidden');
        });
        
        // Auto-submit form when filters change
        const filterSelects = document.querySelectorAll('#status, #course_id, #participant_id, #payment_method');
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
            this.form.submit();
        });
        
        $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            this.form.submit();
        });
        
        // Revenue Chart
        const revenueData = @json($stats['monthly_revenue']);
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Monthly Revenue (Rp)',
                    data: Object.values(revenueData),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
        
        // Export report
        document.getElementById('export-report').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Export functionality will be implemented here.');
        });
    });
    
    function viewPayment(paymentId) {
        const payment = @json($payments->keyBy('id'));
        const data = payment[paymentId];
        
        const modalContent = `
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="paymentModal">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Details</h3>
                    <form id="updatePaymentForm" action="/admin/payments/${paymentId}/update-status" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="space-y-3 text-sm">
                            <div><strong>Course:</strong> ${data.registration.class.course.course_name}</div>
                            <div><strong>Participant:</strong> ${data.registration.participant.full_name}</div>
                            <div><strong>Amount:</strong> Rp ${new Intl.NumberFormat('id-ID').format(data.amount)}</div>
                            <div><strong>Payment Date:</strong> ${data.payment_date}</div>
                            ${data.bank_account ? `<div><strong>Bank:</strong> ${data.bank_account.bank_name} - ${data.bank_account.account_number}</div>` : ''}
                            <div>
                                <label class="block font-medium mb-1">Status:</label>
                                <select name="status" class="w-full border border-gray-300 rounded px-3 py-2">
                                    <option value="pending" ${data.status === 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="verified" ${data.status === 'verified' ? 'selected' : ''}>Verified</option>
                                    <option value="rejected" ${data.status === 'rejected' ? 'selected' : ''}>Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Status</button>
                            <button type="button" onclick="closeModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalContent);
    }
    
    function closeModal() {
        const modal = document.getElementById('paymentModal');
        if (modal) modal.remove();
    }
</script>
@endpush
@endsection
