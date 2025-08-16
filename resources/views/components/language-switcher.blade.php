<div class="relative inline-block text-left">
    <div>
        <button type="button" 
                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                id="language-menu-button"
                aria-expanded="true"
                aria-haspopup="true">
            @if(app()->getLocale() === 'bn')
                <span class="mr-2">🇧🇩 বাংলা</span>
            @else
                <span class="mr-2">🇺🇸 English</span>
            @endif
            <svg class="-mr-0.5 ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" 
         role="menu" 
         aria-orientation="vertical" 
         aria-labelledby="language-menu-button" 
         tabindex="-1"
         id="language-menu">
        <div class="py-1" role="none">
            <a href="{{ request()->fullUrlWithQuery(['lang' => 'bn']) }}" 
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" 
               role="menuitem" 
               tabindex="-1">
                <span class="mr-3">🇧🇩</span>
                বাংলা
                @if(app()->getLocale() === 'bn')
                    <svg class="ml-auto h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                @endif
            </a>
            <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" 
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" 
               role="menuitem" 
               tabindex="-1">
                <span class="mr-3">🇺🇸</span>
                English
                @if(app()->getLocale() === 'en')
                    <svg class="ml-auto h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                @endif
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementById('language-menu-button');
    const menu = document.getElementById('language-menu');
    
    button.addEventListener('click', function() {
        const isHidden = menu.classList.contains('hidden');
        if (isHidden) {
            menu.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
        }
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!button.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
});
</script>
