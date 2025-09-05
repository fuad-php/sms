<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('app.managing_committees') }}
            </h2>
            @can('create', App\Models\ManagingCommittee::class)
                <a href="{{ route('managing-committees.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('app.add_committee_member') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search and Filter Section -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('managing-committees.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-center md:space-x-4">
                            <!-- Search -->
                            <div class="flex-1">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="{{ __('app.search_committee_members') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Position Filter -->
                            <div>
                                <select name="position" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('app.all_positions') }}</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position }}" {{ request('position') == $position ? 'selected' : '' }}>
                                            {{ $position }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('app.all_statuses') }}</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('app.active') }}</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('app.inactive') }}</option>
                                </select>
                            </div>

                            <!-- Featured Filter -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="featured" value="1" {{ request('featured') ? 'checked' : '' }} class="mr-2">
                                    {{ __('app.featured') }}
                                </label>
                            </div>

                            <!-- Search Button -->
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('app.search') }}
                            </button>

                            <!-- Clear Filters -->
                            <a href="{{ route('managing-committees.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('app.clear') }}
                            </a>
                        </form>
                    </div>

                    <!-- Bulk Actions -->
                    @can('manageCommittees', App\Models\ManagingCommittee::class)
                        <form id="bulk-action-form" method="POST" action="{{ route('managing-committees.bulk-action') }}" class="mb-4">
                            @csrf
                            <div class="flex items-center space-x-4">
                                <select name="action" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('app.bulk_actions') }}</option>
                                    <option value="activate">{{ __('app.activate') }}</option>
                                    <option value="deactivate">{{ __('app.deactivate') }}</option>
                                    <option value="feature">{{ __('app.feature') }}</option>
                                    <option value="unfeature">{{ __('app.unfeature') }}</option>
                                    <option value="delete">{{ __('app.delete') }}</option>
                                </select>
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('app.apply_to_selected') }}
                                </button>
                                <button type="button" id="select-all" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('app.select_all') }}
                                </button>
                                <button type="button" id="deselect-all" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('app.deselect_all') }}
                                </button>
                            </div>
                        </form>
                    @endcan

                    <!-- Committee Members Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @can('manageCommittees', App\Models\ManagingCommittee::class)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <input type="checkbox" id="select-all-checkbox">
                                        </th>
                                    @endcan
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('app.image') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('app.name') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('app.position') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('app.designation') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('app.term_duration') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('app.status') }}
                                    </th>
                                    @can('manageCommittees', App\Models\ManagingCommittee::class)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('app.actions') }}
                                        </th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($committees as $committee)
                                    <tr class="hover:bg-gray-50">
                                        @can('manageCommittees', App\Models\ManagingCommittee::class)
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="ids[]" value="{{ $committee->id }}" class="committee-checkbox" form="bulk-action-form">
                                            </td>
                                        @endcan
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="{{ $committee->image_url }}" alt="{{ $committee->name }}" class="h-12 w-12 rounded-full object-cover">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $committee->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $committee->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $committee->position == 'Chairman' ? 'bg-red-100 text-red-800' : 
                                                   ($committee->position == 'Vice-Chairman' ? 'bg-orange-100 text-orange-800' : 
                                                   ($committee->position == 'Secretary' ? 'bg-blue-100 text-blue-800' : 
                                                   ($committee->position == 'Treasurer' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'))) }}">
                                                {{ $committee->position_display }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $committee->designation }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $committee->term_duration ?? __('app.not_available') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                @if($committee->is_active)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ __('app.active') }}
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        {{ __('app.inactive') }}
                                                    </span>
                                                @endif
                                                @if($committee->is_featured)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        {{ __('app.featured') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        @can('manageCommittees', App\Models\ManagingCommittee::class)
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('managing-committees.show', $committee) }}" class="text-blue-600 hover:text-blue-900">
                                                        {{ __('app.view') }}
                                                    </a>
                                                    <a href="{{ route('managing-committees.edit', $committee) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        {{ __('app.edit') }}
                                                    </a>
                                                    <form method="POST" action="{{ route('managing-committees.toggle-status', $committee) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                                            {{ $committee->is_active ? __('app.deactivate') : __('app.activate') }}
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('managing-committees.toggle-featured', $committee) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-purple-600 hover:text-purple-900">
                                                            {{ $committee->is_featured ? __('app.unfeature') : __('app.feature') }}
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('managing-committees.destroy', $committee) }}" class="inline" onsubmit="return confirm('{{ __('app.confirm_delete_committee') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            {{ __('app.delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->can('manageCommittees', App\Models\ManagingCommittee::class) ? '8' : '6' }}" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            {{ __('app.no_committee_members_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $committees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const committeeCheckboxes = document.querySelectorAll('.committee-checkbox');
            const selectAllBtn = document.getElementById('select-all');
            const deselectAllBtn = document.getElementById('deselect-all');
            const bulkActionForm = document.getElementById('bulk-action-form');

            // Select all checkbox functionality
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    committeeCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }

            // Select all button
            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function() {
                    committeeCheckboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                    if (selectAllCheckbox) selectAllCheckbox.checked = true;
                });
            }

            // Deselect all button
            if (deselectAllBtn) {
                deselectAllBtn.addEventListener('click', function() {
                    committeeCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    if (selectAllCheckbox) selectAllCheckbox.checked = false;
                });
            }

            // Bulk action form submission
            if (bulkActionForm) {
                bulkActionForm.addEventListener('submit', function(e) {
                    const checkedBoxes = document.querySelectorAll('.committee-checkbox:checked');
                    const action = this.querySelector('select[name="action"]').value;
                    
                    if (checkedBoxes.length === 0) {
                        e.preventDefault();
                        alert('{{ __("app.no_committees_selected") }}');
                        return;
                    }

                    if (action === 'delete') {
                        if (!confirm('{{ __("app.confirm_bulk_delete") }}')) {
                            e.preventDefault();
                            return;
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
