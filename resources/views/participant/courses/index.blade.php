@extends('layouts.participant')

@section('title', 'Available Courses')

@section('content')
<div class="space-y-4">
    <h1 class="text-xl font-semibold text-gray-900">Available Courses</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($classes as $class)
            <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                @if($class->course->image)
                    <img src="{{ asset('storage/' . $class->course->image) }}" alt="{{ $class->course->course_name }}" class="w-full h-40 object-cover rounded-t-lg">
                @else
                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center rounded-t-lg">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-base mb-2">{{ Str::limit($class->course->course_name, 50) }}</h3>
                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($class->course->description, 100) }}</p>
                    
                    <div class="space-y-1 text-xs text-gray-600 mb-3">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $class->start_date->format('M d') }} - {{ $class->end_date->format('M d, Y') }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ Str::limit($class->location, 20) }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            Rp {{ number_format($class->price / 1000000, 1) }}M
                        </div>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            {{ $class->available_spots }} spots
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">
                            Open
                        </span>
                        @if(in_array($class->id, $registeredClasses))
                            <a href="{{ route('participant.courses.detail', $class->id) }}" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
                                Detail
                            </a>
                        @else
                            <form method="POST" action="{{ route('participant.courses.register', $class->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                    Register
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8">
                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No courses available</h3>
                <p class="mt-1 text-sm text-gray-500">Check back later for new course offerings.</p>
            </div>
        @endforelse
    </div>

    @if($classes->hasPages())
        <div class="mt-4">
            {{ $classes->links() }}
        </div>
    @endif
</div>

<!-- Payment Modal -->
@if(session('payment_modal'))
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Required</h3>
            <form method="POST" action="{{ route('participant.payment.upload') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="registration_id" value="{{ session('payment_modal') }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Payment Proof</label>
                    <input type="file" name="payment_proof" accept="image/*" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Account</label>
                    <select name="bank_account_id" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="">Select Bank Account</option>
                        @foreach(\App\Models\BankAccount::where('is_active', true)->get() as $bank)
                            <option value="{{ $bank->id }}">
                                {{ $bank->bank_name }} - {{ $bank->account_number }} ({{ $bank->account_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                        Upload Payment
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">
                        Later
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function closeModal() {
    document.getElementById('paymentModal').style.display = 'none';
}
</script>
@endif

@endsection