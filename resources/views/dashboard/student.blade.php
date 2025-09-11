@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Student Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Today's Timetable</h2>
        @if(count($todayTimetable) > 0)
            <div class="space-y-4">
                @foreach($todayTimetable as $period)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $period->subject->name ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-500">{{ $period->teacher->user->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $period->start_time ? $period->start_time->format('H:i') : 'N/A' }} - {{ $period->end_time ? $period->end_time->format('H:i') : 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Room {{ $period->room ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No classes scheduled for today.</p>
        @endif
    </div>

    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Attendance Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $attendanceStats['this_month'] ?? 0 }}%</p>
                <p class="text-sm text-gray-500">This Month</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ $attendanceStats['last_month'] ?? 0 }}%</p>
                <p class="text-sm text-gray-500">Last Month</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-600">{{ $attendanceStats['overall'] ?? 0 }}%</p>
                <p class="text-sm text-gray-500">Overall</p>
            </div>
        </div>
    </div>

    <!-- Fee Information Section -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">{{ __('app.fee_management') }}</h2>
            <a href="{{ route('fees.student-details', $student) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                {{ __('app.view_details') }} →
            </a>
        </div>
        
        <!-- Fee Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('app.total_fees') }}</h3>
                        <p class="text-2xl font-bold text-blue-600">৳{{ number_format($feeSummary['total_fees'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('app.paid_amount') }}</h3>
                        <p class="text-2xl font-bold text-green-600">৳{{ number_format($feeSummary['total_paid'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('app.pending_amount') }}</h3>
                        <p class="text-2xl font-bold text-yellow-600">৳{{ number_format($feeSummary['total_pending'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('app.payment_progress') }}</h3>
                        <p class="text-2xl font-bold text-purple-600">{{ $feeSummary['payment_percentage'] }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fee Structures -->
        @if($feeStructures->count() > 0)
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-3">{{ __('app.fee_structures') }}</h3>
            <div class="space-y-2">
                @foreach($feeStructures as $feeStructure)
                @php
                    $studentPayments = $payments->where('fee_structure_id', $feeStructure->id);
                    $totalPaid = $studentPayments->sum('total_amount');
                    $isPaid = $totalPaid >= $feeStructure->amount;
                @endphp
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $feeStructure->feeCategory->name }}
                            </span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $feeStructure->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $feeStructure->frequency)) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">৳{{ number_format($feeStructure->amount, 2) }}</p>
                            @if($totalPaid > 0)
                                <p class="text-xs text-gray-500">{{ __('app.paid') }}: ৳{{ number_format($totalPaid, 2) }}</p>
                            @endif
                        </div>
                        <div>
                            @if($isPaid)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('app.paid') }}
                                </span>
                            @elseif($totalPaid > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('app.partial') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('app.pending') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Payments -->
        @if($payments->count() > 0)
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-3">{{ __('app.recent_payments') }}</h3>
            <div class="space-y-2">
                @foreach($payments as $payment)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-sm">{{ $payment->getPaymentMethodIcon() }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $payment->feeStructure->name }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">৳{{ number_format($payment->total_amount, 2) }}</p>
                        <p class="text-xs text-gray-500">{{ $payment->payment_reference }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Upcoming Installments -->
        @if($installments->where('status', 'pending')->count() > 0)
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-3">{{ __('app.upcoming_installments') }}</h3>
            <div class="space-y-2">
                @foreach($installments->where('status', 'pending')->take(3) as $installment)
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $installment->feeStructure->name }}</p>
                            <p class="text-xs text-gray-500">{{ __('app.installment_number') }}{{ $installment->installment_number }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">৳{{ number_format($installment->amount, 2) }}</p>
                        <p class="text-xs text-gray-500">{{ __('app.due') }}: {{ $installment->due_date->format('M d, Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
