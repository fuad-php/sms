<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('app.edit_committee_member') }}: {{ $managingCommittee->name }}
            </h2>
            <a href="{{ route('managing-committees.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('app.back_to_list') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('managing-committees.update', $managingCommittee) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('app.name') }} <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $managingCommittee->name) }}" 
                                   required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Designation -->
                        <div>
                            <label for="designation" class="block text-sm font-medium text-gray-700">{{ __('app.designation') }} <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="designation" 
                                   id="designation" 
                                   value="{{ old('designation', $managingCommittee->designation) }}" 
                                   required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('designation') border-red-500 @enderror">
                            @error('designation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Position -->
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700">{{ __('app.position') }}</label>
                            <select name="position" 
                                    id="position" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('position') border-red-500 @enderror">
                                <option value="">{{ __('app.select_position') }}</option>
                                @foreach($positions as $key => $value)
                                    <option value="{{ $key }}" {{ old('position', $managingCommittee->position) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('app.email') }}</label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $managingCommittee->email) }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('app.phone') }}</label>
                            <input type="text" 
                                   name="phone" 
                                   id="phone" 
                                   value="{{ old('phone', $managingCommittee->phone) }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Image -->
                        @if($managingCommittee->image)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('app.current_image') }}</label>
                                <div class="mt-2">
                                    <img src="{{ $managingCommittee->image_url }}" alt="{{ $managingCommittee->name }}" class="h-24 w-24 rounded-full object-cover">
                                </div>
                            </div>
                        @endif

                        <!-- Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">
                                {{ $managingCommittee->image ? __('app.change_image') : __('app.upload_image') }}
                            </label>
                            <input type="file" 
                                   name="image" 
                                   id="image" 
                                   accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">{{ __('app.image_requirements') }}</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bio -->
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700">{{ __('app.bio') }}</label>
                            <textarea name="bio" 
                                      id="bio" 
                                      rows="4" 
                                      placeholder="{{ __('app.committee_member_bio_placeholder') }}"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('bio') border-red-500 @enderror">{{ old('bio', $managingCommittee->bio) }}</textarea>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Term Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="term_start" class="block text-sm font-medium text-gray-700">{{ __('app.term_start') }}</label>
                                <input type="date" 
                                       name="term_start" 
                                       id="term_start" 
                                       value="{{ old('term_start', $managingCommittee->term_start?->format('Y-m-d')) }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('term_start') border-red-500 @enderror">
                                <p class="mt-1 text-sm text-gray-500">{{ __('app.term_dates_help') }}</p>
                                @error('term_start')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="term_end" class="block text-sm font-medium text-gray-700">{{ __('app.term_end') }}</label>
                                <input type="date" 
                                       name="term_end" 
                                       id="term_end" 
                                       value="{{ old('term_end', $managingCommittee->term_end?->format('Y-m-d')) }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('term_end') border-red-500 @enderror">
                                <p class="mt-1 text-sm text-gray-500">{{ __('app.term_dates_help') }}</p>
                                @error('term_end')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700">{{ __('app.sort_order') }}</label>
                            <input type="number" 
                                   name="sort_order" 
                                   id="sort_order" 
                                   value="{{ old('sort_order', $managingCommittee->sort_order) }}" 
                                   min="0"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">{{ __('app.sort_order_help') }}</p>
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Options -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', $managingCommittee->is_active) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('app.is_active') }}</span>
                                </label>
                                <p class="mt-1 text-sm text-gray-500">{{ __('app.active_help') }}</p>
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_featured" 
                                           value="1" 
                                           {{ old('is_featured', $managingCommittee->is_featured) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('app.is_featured') }}</span>
                                </label>
                                <p class="mt-1 text-sm text-gray-500">{{ __('app.featured_help') }}</p>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('managing-committees.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('app.cancel') }}
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('app.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
