<div class="relative inline-block text-left">
    <form method="GET" action="{{ request()->url() }}" class="inline">
        @foreach(request()->query() as $key => $value)
            @if($key !== 'lang')
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach
        
        <select name="lang" 
                onchange="this.form.submit()" 
                class="language-select inline-flex items-center px-3 py-1 border shadow-sm text-sm leading-4 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2">
            <option value="bn" {{ app()->getLocale() === 'bn' ? 'selected' : '' }}>🇧🇩 বাংলা</option>
            <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>🇺🇸 English</option>
        </select>
    </form>
</div>

<style>
/* Language switcher for dark backgrounds (like top blue bar) */
.bg-blue-900 .language-select,
.bg-gray-900 .language-select,
.bg-black .language-select {
    border-color: rgba(255, 255, 255, 0.3);
    color: white;
    background-color: transparent;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
}

.bg-blue-900 .language-select:hover,
.bg-gray-900 .language-select:hover,
.bg-black .language-select:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Language switcher for light backgrounds (like dashboard) */
.language-select {
    border-color: #d1d5db;
    color: #374151;
    background-color: white;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23374151' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

.language-select:hover {
    background-color: #f9fafb;
    border-color: #9ca3af;
}

.language-select:focus {
    border-color: #3b82f6;
    ring-color: #3b82f6;
}

.language-select option {
    background-color: white;
    color: #374151;
}
</style>
