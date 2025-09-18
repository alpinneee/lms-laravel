@extends('layouts.admin')

@section('title', 'Bank Accounts')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-900">Bank Accounts</h1>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-700">
            + Add Bank Account
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-sm border">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bank Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Account Number</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Account Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bankAccounts as $bank)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $bank->bank_name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $bank->account_number }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $bank->account_name }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-0.5 text-xs rounded {{ $bank->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $bank->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('admin.reports.toggle-bank-account', $bank->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs {{ $bank->is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }}">
                                        {{ $bank->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No bank accounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Add Bank Account</h3>
        <form method="POST" action="{{ route('admin.reports.store-bank-account') }}">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                <input type="text" name="bank_name" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                <input type="text" name="account_number" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
                <input type="text" name="account_name" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Add</button>
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection