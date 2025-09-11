@extends('layouts.app')

@section('title', __('app.leave_details'))

@section('content')
<div class="min-h-screen bg-gray-50">
    @if(session('success'))
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <x-page-header>
        <x-slot name="actions">
            <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('app.back') }}
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow rounded-xl overflow-hidden">

            <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="text-xs uppercase text-gray-500">{{ __('app.user') }}</div>
                    <div class="mt-1 text-gray-900">{{ $leave->user->name }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase text-gray-500">{{ __('app.leave_type') }}</div>
                    <div class="mt-1 text-gray-900">{{ ucfirst($leave->type ?? $leave->leave_type) }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase text-gray-500">{{ __('app.start_date') }}</div>
                    <div class="mt-1 text-gray-900">{{ $leave->start_date?->format('Y-m-d') }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase text-gray-500">{{ __('app.end_date') }}</div>
                    <div class="mt-1 text-gray-900">{{ $leave->end_date?->format('Y-m-d') }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase text-gray-500">{{ __('app.status') }}</div>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                            @if($leave->status === 'approved') bg-green-100 text-green-800
                            @elseif($leave->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </div>
                </div>
                <div>
                    <div class="text-xs uppercase text-gray-500">{{ __('app.created_at') }}</div>
                    <div class="mt-1 text-gray-900">{{ $leave->created_at->format('Y-m-d H:i') }}</div>
                </div>

                @if($leave->approved_by)
                    <div>
                        <div class="text-xs uppercase text-gray-500">{{ __('app.approved_by') }}</div>
                        <div class="mt-1 text-gray-900">{{ $leave->approver?->name }}</div>
                    </div>
                    <div>
                        <div class="text-xs uppercase text-gray-500">{{ __('app.approval_date') }}</div>
                        <div class="mt-1 text-gray-900">{{ optional($leave->approval_date)->format('Y-m-d H:i') }}</div>
                    </div>
                @endif
            </div>

            <div class="px-6 pb-6">
                <div class="text-xs uppercase text-gray-500">{{ __('app.reason') }}</div>
                <div class="mt-2 text-gray-900 whitespace-pre-line bg-gray-50 rounded-lg p-4">{{ $leave->reason ?: __('app.no_remarks') }}</div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection


