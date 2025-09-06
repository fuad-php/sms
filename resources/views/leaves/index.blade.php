@extends('layouts.app')

@section('title', __('app.leaves'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold">{{ __('app.leaves') }}</h3>
                    <div class="mt-2 inline-flex rounded-md shadow-sm" role="group">
                        <a href="{{ route('leaves.my') }}" class="px-4 py-2 text-sm border {{ ($mode ?? 'my') === 'my' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">{{ __('app.my_leaves') }}</a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('leaves.all') }}" class="px-4 py-2 text-sm border-t border-b border-r {{ ($mode ?? 'my') === 'all' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }}">{{ __('app.all_leaves') }}</a>
                        @endif
                    </div>
                </div>
                <a href="{{ route('leaves.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('app.apply_leave') }}</a>
            </div>
            <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-2">
                @if(($users->count() ?? 0) > 0)
                    <select name="user_id" class="border rounded px-3 py-2">
                        <option value="">{{ __('app.all_users') }}</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                @endif
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">{{ __('app.all_statuses') }}</option>
                    @foreach(['pending','approved','rejected'] as $s)
                        <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <div></div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('app.filter') }}</button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.user') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.leave_type') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.period') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($leaves as $leave)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $leave->user->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 capitalize">{{ $leave->type }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $leave->start_date->format('Y-m-d') }} - {{ $leave->end_date->format('Y-m-d') }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 capitalize">{{ $leave->status }}</td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    <a href="{{ route('leaves.show', $leave) }}" class="text-blue-600 hover:underline">{{ __('app.view') }}</a>
                                    @if(auth()->user()->role === 'admin' && $leave->status === 'pending')
                                        <form method="POST" action="{{ url('/api/leaves/'.$leave->id.'/approve') }}" class="inline">
                                            @csrf
                                            <button class="text-green-600 hover:underline" onclick="return confirm('{{ __('app.are_you_sure') }}')">{{ __('app.approve') }}</button>
                                        </form>
                                        <form method="POST" action="{{ url('/api/leaves/'.$leave->id.'/reject') }}" class="inline">
                                            @csrf
                                            <button class="text-red-600 hover:underline" onclick="return confirm('{{ __('app.are_you_sure') }}')">{{ __('app.reject') }}</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">{{ __('app.no_records_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                @php($pageName = ($mode ?? 'my') === 'all' ? 'all_page' : 'my_page')
                {{ $leaves->withPath(request()->url())->appends(collect(request()->query())->except($pageName)->toArray())->links() }}
            </div>
        </div>
    </div>
@endsection


