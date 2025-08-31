# Unified Design System

This document outlines the unified design system implemented across all layouts in the School Management System to ensure consistency and maintainability.

## 🎯 **Overview**

All layouts now follow a unified design pattern that provides:
- **Consistent visual hierarchy**
- **Unified color palette**
- **Standardized spacing system**
- **Responsive design patterns**
- **Accessible component states**

## 🏗️ **Layout Architecture**

### **Base Layout Component** (`components/base-layout.blade.php`)
A flexible base component that can be configured for different use cases:

```blade
<x-base-layout 
    :show-header="true"
    :show-footer="true"
    :show-flash-messages="true"
    :show-mobile-menu="true"
    title="Page Title"
    logo-link="{{ route('home') }}"
    main-class="py-8">
    
    <x-slot name="navigation">
        <!-- Navigation links -->
    </x-slot>
    
    <x-slot name="actions">
        <!-- Action buttons -->
    </x-slot>
    
    <x-slot name="mobile-menu">
        <!-- Mobile menu content -->
    </x-slot>
    
    <!-- Page content -->
</x-base-layout>
```

### **Layout Types**

#### 1. **Public Layout** (`components/public-layout.blade.php`)
- Used for public-facing pages (home, public announcements)
- Full header with navigation
- Comprehensive footer
- Mobile-responsive design

#### 2. **App Layout** (`layouts/app.blade.php`)
- Used for authenticated user pages
- Role-based navigation
- Flash message support
- Minimal footer

#### 3. **Guest Layout** (`layouts/guest.blade.php`)
- Used for authentication pages
- Centered design
- Logo and branding
- Quick navigation links

## 🎨 **Design Tokens**

### **Color Palette**
```css
/* Primary Colors */
bg-gray-50      /* Background */
bg-white        /* Cards, Headers */
bg-blue-600     /* Primary Actions */
text-gray-900   /* Primary Text */
text-gray-600   /* Secondary Text */
text-gray-500   /* Muted Text */

/* Status Colors */
bg-green-50     /* Success backgrounds */
bg-red-50       /* Error backgrounds */
bg-yellow-50    /* Warning backgrounds */
bg-blue-50      /* Info backgrounds */
```

### **Typography Scale**
```css
/* Headings */
text-3xl font-bold      /* Page titles */
text-2xl font-semibold  /* Section headings */
text-xl font-bold       /* Subsection headings */

/* Body Text */
text-base text-gray-900 /* Regular content */
text-sm text-gray-600   /* Secondary content */
text-xs text-gray-500   /* Captions */
```

### **Spacing System**
```css
/* Main Content */
py-8           /* Main content padding */
mb-12          /* Section spacing */
mb-6           /* Subsection spacing */
mb-4           /* Element spacing */

/* Components */
p-6            /* Card padding */
px-4 py-2     /* Button padding */
gap-6          /* Grid gaps */
space-y-4      /* Vertical spacing */
```

## 🧩 **Component Patterns**

### **Cards**
```blade
<div class="bg-white rounded-lg shadow-sm border p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-2">Title</h3>
    <p class="text-gray-600 text-sm">Content</p>
</div>
```

### **Buttons**
```blade
<!-- Primary Button -->
<button class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
    Primary Action
</button>

<!-- Secondary Button -->
<button class="bg-white text-gray-700 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
    Secondary Action
</button>

<!-- Text Button -->
<button class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors">
    Text Action
</button>
```

### **Form Elements**
```blade
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
    <input type="text" 
           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
</div>
```

### **Navigation Links**
```blade
<a href="{{ route('home') }}" 
   class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('home') ? 'text-blue-600' : '' }}">
    Navigation Link
</a>
```

## 📱 **Responsive Patterns**

### **Container Pattern**
```blade
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Content with consistent width and responsive padding -->
</div>
```

### **Grid System**
```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Responsive grid with consistent gaps -->
</div>
```

### **Mobile-First Approach**
- Base styles for mobile
- `md:` breakpoint for tablets
- `lg:` breakpoint for desktop

## 🔧 **Implementation Guidelines**

### **1. Always Use Design Tokens**
❌ **Don't:**
```blade
<div class="bg-gray-100 p-8">
    <h1 class="text-2xl text-black">Title</h1>
</div>
```

✅ **Do:**
```blade
<div class="bg-gray-50 py-8">
    <h1 class="text-3xl font-bold text-gray-900">Title</h1>
</div>
```

### **2. Maintain Spacing Hierarchy**
```blade
<main class="py-8">                    <!-- Main content -->
    <section class="mb-12">            <!-- Section -->
        <div class="mb-6">             <!-- Subsection -->
            <h2 class="mb-4">Title</h2> <!-- Element -->
            <p class="mb-4">Content</p>  <!-- Element -->
        </div>
    </section>
</main>
```

### **3. Include Interactive States**
```blade
<a href="#" 
   class="text-gray-600 hover:text-gray-900 transition-colors">
    Link with hover state
</a>

<button class="bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-colors">
    Button with hover and focus
</button>
```

### **4. Use Semantic HTML**
```blade
<!-- Good -->
<header class="bg-white shadow-sm border-b">
    <nav class="hidden md:flex space-x-8">
        <a href="#">Link</a>
    </nav>
</header>

<main class="py-8">
    <section class="mb-12">
        <h1 class="text-3xl font-bold text-gray-900">Page Title</h1>
    </section>
</main>

<footer class="bg-white border-t">
    <p>Footer content</p>
</footer>
```

## 📋 **Layout Selection Guide**

### **Choose Layout Based On:**

| Layout | Use Case | Features |
|--------|----------|----------|
| `public-layout` | Public pages | Full header, navigation, footer |
| `app-layout` | Authenticated pages | Role-based nav, flash messages |
| `guest-layout` | Auth pages | Centered, minimal design |
| `base-layout` | Custom layouts | Configurable, flexible |

### **Examples:**
```blade
<!-- Public page -->
<x-public-layout>
    <!-- Content -->
</x-public-layout>

<!-- Admin dashboard -->
@extends('layouts.app')
@section('content')
    <!-- Content -->
@endsection

<!-- Login page -->
<x-guest-layout>
    <!-- Auth form -->
</x-guest-layout>
```

## 🚀 **Getting Started**

### **1. For New Pages**
1. Determine the appropriate layout type
2. Use the design tokens and patterns
3. Follow the spacing hierarchy
4. Include interactive states

### **2. For Existing Pages**
1. Update to use the new layout system
2. Replace custom CSS with design tokens
3. Ensure responsive behavior
4. Test accessibility

### **3. For Components**
1. Use the established patterns
2. Maintain consistency with existing components
3. Include proper states (hover, focus, active)
4. Test across different screen sizes

## 🔍 **Design System Reference**

To see all design patterns in action, include the design system component:

```blade
<x-design-system />
```

This will display a comprehensive reference of all design tokens, components, and patterns.

## 📚 **Additional Resources**

- **Tailwind CSS Documentation**: https://tailwindcss.com/docs
- **Heroicons**: https://heroicons.com/
- **Color Accessibility**: Ensure sufficient contrast ratios
- **Mobile Testing**: Test on various devices and screen sizes

## 🤝 **Contributing**

When adding new components or modifying existing ones:

1. **Follow the established patterns**
2. **Use design tokens consistently**
3. **Maintain responsive behavior**
4. **Include proper accessibility features**
5. **Update this documentation**

---

**Remember**: Consistency is key. Always refer to this design system when creating or modifying layouts and components.
