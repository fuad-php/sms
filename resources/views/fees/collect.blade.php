@extends('layouts.app')

@section('title', __('app.collect_fee_payment'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-page-header>
        <x-slot name="actions">
            <a href="{{ route('fees.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('app.back_to_dashboard') }}
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

        <!-- Student Selection -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.select_student') }}</h3>
            <form method="GET" class="flex items-end space-x-4">
                <div class="flex-1">
                    <label for="student_search" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.search_student') }}</label>
                    <input type="text" 
                           id="student_search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="{{ __('app.enter_student_name_or_id') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    {{ __('app.search') }}
                </button>
            </form>
        </div>

        @if(isset($students) && $students->count() > 0)
            <!-- Student List -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('app.available_students') }}</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($students as $student)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-600">{{ substr($student->user->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $student->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $student->class->name ?? __('app.no_class') }} - {{ __('app.student_id') }}: {{ $student->student_id }}</p>
                                </div>
                            </div>
                            <a href="{{ route('fees.collect', ['student_id' => $student->id]) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                {{ __('app.select') }}
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(isset($student))

            <!-- Student Information -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.student_information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('app.name') }}</p>
                        <p class="text-lg font-medium text-gray-900">{{ $student->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('app.class') }}</p>
                        <p class="text-lg font-medium text-gray-900">{{ $student->class->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('app.student_id') }}</p>
                        <p class="text-lg font-medium text-gray-900">{{ $student->student_id }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">{{ __('app.payment_details') }}</h3>
                
                <form method="POST" action="{{ route('fees.process-payment') }}" id="paymentForm">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Fee Structure Selection -->
                        <div class="md:col-span-2">
                            <label for="fee_structure_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.fee_structure') }} *</label>
                            <select name="fee_structure_id" id="fee_structure_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('app.select_fee_structure') }}</option>
                                @if(isset($feeStructures))
                                    @foreach($feeStructures as $feeStructure)
                                    <option value="{{ $feeStructure->id }}" 
                                            data-amount="{{ $feeStructure->amount }}"
                                            data-frequency="{{ $feeStructure->frequency }}">
                                        {{ $feeStructure->name }} - {{ $feeStructure->feeCategory->name }} (৳{{ number_format($feeStructure->amount, 2) }})
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Installment Selection -->
                        <div class="md:col-span-2">
                            <label for="installment_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.installment') }} ({{ __('app.optional') }})</label>
                            <select name="installment_id" id="installment_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('app.select_installment') }}</option>
                                @if(isset($installments))
                                    @foreach($installments as $installment)
                                    <option value="{{ $installment->id }}" 
                                            data-amount="{{ $installment->amount }}"
                                            data-due-date="{{ $installment->due_date->format('Y-m-d') }}">
                                        {{ $installment->feeStructure->name }} - Installment {{ $installment->installment_number }} 
                                        (৳{{ number_format($installment->amount, 2) }} - Due: {{ $installment->due_date->format('M d, Y') }})
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.amount') }} *</label>
                            <input type="number" 
                                   name="amount" 
                                   id="amount" 
                                   step="0.01" 
                                   min="0.01" 
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.payment_method') }} *</label>
                            <select name="payment_method" id="payment_method" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('app.select_method') }}</option>
                                <option value="cash">{{ __('app.cash') }}</option>
                                <option value="bank_transfer">{{ __('app.bank_transfer') }}</option>
                                <option value="cheque">{{ __('app.cheque') }}</option>
                                <option value="online">{{ __('app.online') }}</option>
                                <option value="card">{{ __('app.card') }}</option>
                            </select>
                        </div>

                        <!-- Discount Amount -->
                        <div>
                            <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.discount_amount') }}</label>
                            <input type="number" 
                                   name="discount_amount" 
                                   id="discount_amount" 
                                   step="0.01" 
                                   min="0" 
                                   value="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Late Fee -->
                        <div>
                            <label for="late_fee" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.late_fee') }}</label>
                            <input type="number" 
                                   name="late_fee" 
                                   id="late_fee" 
                                   step="0.01" 
                                   min="0" 
                                   value="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Payment Date -->
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.payment_date') }} *</label>
                            <input type="date" 
                                   name="payment_date" 
                                   id="payment_date" 
                                   value="{{ date('Y-m-d') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Transaction ID (for non-cash payments) -->
                        <div id="transaction_fields" class="hidden">
                            <div>
                                <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.transaction_id') }}</label>
                                <input type="text" 
                                       name="transaction_id" 
                                       id="transaction_id"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Cheque Details (for cheque payments) -->
                        <div id="cheque_fields" class="hidden">
                            <div>
                                <label for="cheque_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.cheque_number') }}</label>
                                <input type="text" 
                                       name="cheque_number" 
                                       id="cheque_number"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.bank_name') }}</label>
                                <input type="text" 
                                       name="bank_name" 
                                       id="bank_name"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.notes') }}</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Additional notes about this payment..."></textarea>
                        </div>
                    </div>

                    <!-- Total Amount Display -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-medium text-gray-900">{{ __('app.total_amount') }}:</span>
                            <span id="total_amount" class="text-2xl font-bold text-blue-600">৳0.00</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('fees.dashboard') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            {{ __('app.cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            {{ __('app.process_payment') }}
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const feeStructureSelect = document.getElementById('fee_structure_id');
    const installmentSelect = document.getElementById('installment_id');
    const amountInput = document.getElementById('amount');
    const discountInput = document.getElementById('discount_amount');
    const lateFeeInput = document.getElementById('late_fee');
    const totalAmountSpan = document.getElementById('total_amount');
    const paymentMethodSelect = document.getElementById('payment_method');
    const transactionFields = document.getElementById('transaction_fields');
    const chequeFields = document.getElementById('cheque_fields');

    // Only run if the payment form exists (student is selected)
    if (!feeStructureSelect || !amountInput) {
        return;
    }

    // Auto-fill amount when fee structure is selected
    feeStructureSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            amountInput.value = selectedOption.dataset.amount;
            calculateTotal();
        }
    });

    // Auto-fill amount when installment is selected
    installmentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            amountInput.value = selectedOption.dataset.amount;
            calculateTotal();
        }
    });

    // Show/hide additional fields based on payment method
    paymentMethodSelect.addEventListener('change', function() {
        const method = this.value;
        
        // Hide all additional fields
        transactionFields.classList.add('hidden');
        chequeFields.classList.add('hidden');
        
        // Show relevant fields
        if (['bank_transfer', 'online', 'card'].includes(method)) {
            transactionFields.classList.remove('hidden');
        } else if (method === 'cheque') {
            chequeFields.classList.remove('hidden');
        }
    });

    // Calculate total amount
    function calculateTotal() {
        const amount = parseFloat(amountInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const lateFee = parseFloat(lateFeeInput.value) || 0;
        
        const total = amount + lateFee - discount;
        totalAmountSpan.textContent = '৳' + total.toFixed(2);
    }

    // Add event listeners for amount calculations
    [amountInput, discountInput, lateFeeInput].forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    // Initial calculation
    calculateTotal();
});
</script>
@endpush
        </div>
    </div>
</div>
@endsection
