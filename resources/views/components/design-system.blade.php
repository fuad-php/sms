{{-- 
    Design System Component
    
    This component documents the unified design patterns used across all layouts.
    It serves as a reference for maintaining consistency.
    
    USAGE:
    Include this component in your documentation or reference it for styling guidelines.
--}}

<div class="design-system-docs p-8 bg-white rounded-lg shadow-sm border max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Unified Design System</h1>
    
    <!-- Color Palette -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Color Palette</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-lg border mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-900">Gray 50</p>
                <p class="text-xs text-gray-600">bg-gray-50</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-white rounded-lg border mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-900">White</p>
                <p class="text-xs text-gray-600">bg-white</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-lg mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-900">Blue 600</p>
                <p class="text-xs text-gray-600">bg-blue-600</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-gray-900 rounded-lg mx-auto mb-2"></div>
                <p class="text-sm font-medium text-white">Gray 900</p>
                <p class="text-xs text-gray-400">bg-gray-900</p>
            </div>
        </div>
    </section>

    <!-- Typography -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Typography</h2>
        <div class="space-y-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Heading 1 (text-3xl font-bold)</h1>
                <p class="text-sm text-gray-600">Used for page titles and main headings</p>
            </div>
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Heading 2 (text-2xl font-semibold)</h2>
                <p class="text-sm text-gray-600">Used for section headings</p>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Heading 3 (text-xl font-bold)</h3>
                <p class="text-sm text-gray-600">Used for subsection headings</p>
            </div>
            <div>
                <p class="text-base text-gray-900">Body text (text-base text-gray-900)</p>
                <p class="text-sm text-gray-600">Used for regular content</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Small text (text-sm text-gray-600)</p>
                <p class="text-xs text-gray-500">Used for captions and secondary information</p>
            </div>
        </div>
    </section>

    <!-- Spacing -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Spacing System</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="w-4 h-4 bg-blue-200 rounded mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-900">4px</p>
                <p class="text-xs text-gray-600">p-1, m-1</p>
            </div>
            <div class="text-center">
                <div class="w-6 h-6 bg-blue-200 rounded mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-900">6px</p>
                <p class="text-xs text-gray-600">p-1.5, m-1.5</p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 bg-blue-200 rounded mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-900">8px</p>
                <p class="text-xs text-gray-600">p-2, m-2</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-200 rounded mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-900">12px</p>
                <p class="text-xs text-gray-600">p-3, m-3</p>
            </div>
        </div>
    </section>

    <!-- Components -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Component Patterns</h2>
        
        <!-- Buttons -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Buttons</h3>
            <div class="flex flex-wrap gap-4">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Primary Button
                </button>
                <button class="bg-white text-gray-700 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Secondary Button
                </button>
                <button class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                    Text Button
                </button>
            </div>
        </div>

        <!-- Cards -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cards</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Card Title</h4>
                    <p class="text-gray-600 text-sm">Card content goes here with consistent spacing and typography.</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Card Title</h4>
                    <p class="text-gray-600 text-sm">Card content goes here with consistent spacing and typography.</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Card Title</h4>
                    <p class="text-gray-600 text-sm">Card content goes here with consistent spacing and typography.</p>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Form Elements</h3>
            <div class="space-y-4 max-w-md">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Input Label</label>
                    <input type="text" placeholder="Placeholder text" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Label</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Layout Patterns -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Layout Patterns</h2>
        
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Container Pattern</h3>
                <div class="bg-gray-100 p-4 rounded">
                    <code class="text-sm">max-w-7xl mx-auto px-4 sm:px-6 lg:px-8</code>
                    <p class="text-sm text-gray-600 mt-2">Used for consistent content width and responsive padding</p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Grid Pattern</h3>
                <div class="bg-gray-100 p-4 rounded">
                    <code class="text-sm">grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6</code>
                    <p class="text-sm text-gray-600 mt-2">Responsive grid system with consistent gaps</p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Spacing Pattern</h3>
                <div class="bg-gray-100 p-4 rounded">
                    <code class="text-sm">py-8 (main), mb-6 (sections), mb-4 (elements)</code>
                    <p class="text-sm text-gray-600 mt-2">Consistent vertical spacing hierarchy</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Best Practices -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Best Practices</h2>
        <div class="space-y-4">
            <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Use consistent color classes</p>
                    <p class="text-xs text-gray-600">Always use the predefined color palette (gray-50, white, blue-600, etc.)</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Maintain spacing consistency</p>
                    <p class="text-xs text-gray-600">Use the spacing system (py-8, mb-6, mb-4) for consistent layouts</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Use semantic HTML</p>
                    <p class="text-xs text-gray-600">Always use proper heading hierarchy and semantic elements</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Include hover and focus states</p>
                    <p class="text-xs text-gray-600">Always add hover:text-gray-900 and focus:ring-2 for interactive elements</p>
                </div>
            </div>
        </div>
    </section>
</div>
