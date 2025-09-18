@extends('layouts.participant')

@section('title', 'Payment History')

@section('content')
<div class="space-y-4">
    <h1 class="text-xl font-semibold text-gray-900">Payment History</h1>

    <div class="bg-white rounded-lg shadow-sm border">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bank Account</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proof</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->registration->class->course->course_name }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->registration->class->location }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                @if($payment->bankAccount)
                                    <div>{{ $payment->bankAccount->bank_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $payment->bankAccount->account_number }}</div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $payment->payment_date->format('M d, Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                @if($payment->status === 'pending')
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">Pending Verification</span>
                                @elseif($payment->status === 'verified')
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Verified</span>
                                @elseif($payment->status === 'rejected')
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Rejected</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($payment->payment_proof)
                                    <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs">
                                        View Proof
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                No payment history found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection