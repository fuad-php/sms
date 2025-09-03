# School Logo Integration

This document explains how the school logo system works in the School Management System.

## Overview

The system now supports dynamic school logos that can be configured through the settings system. The logo will be displayed consistently across all layouts and pages.

## Features

- **Dynamic Logo Loading**: Logo is loaded from settings, not hardcoded
- **Fallback Support**: Falls back to default application logo if no custom logo is set
- **Multiple Layout Support**: Works across all layout files (app, guest, public, legacy)
- **Responsive Design**: Logo adapts to different sizes and contexts
- **Caching**: Logo settings are cached for performance

## How It Works

### 1. Settings Helper Methods

The `SettingsHelper` class provides several methods for logo management:

```php
// Get the raw logo path/URL from settings
\App\Helpers\SettingsHelper::getSchoolLogo()

// Get the full URL for the logo (with proper asset() handling)
\App\Helpers\SettingsHelper::getSchoolLogoUrl()

// Check if a custom logo is set
\App\Helpers\SettingsHelper::hasSchoolLogo()
```

### 2. School Logo Component

The `<x-school-logo>` component automatically handles:
- Loading the custom logo if available
- Falling back to the default application logo
- Proper alt text generation
- CSS class application

```blade
<!-- Basic usage -->
<x-school-logo />

<!-- With custom classes -->
<x-school-logo class="h-10 w-auto" />

<!-- With custom attributes -->
<x-school-logo class="h-10 w-auto" alt="Custom Alt Text" />
```

### 3. Layout Integration

All layout files have been updated to use the school logo:

- `resources/views/layouts/app.blade.php` - Main application layout
- `resources/views/layouts/guest.blade.php` - Guest/auth layout  
- `resources/views/components/public-layout.blade.php` - Public pages layout
- `resources/views/components/layout.blade.php` - Legacy layout

## Configuration

### Setting the Logo

The school logo is stored in the `settings` table with the key `school_logo`. You can set it through:

1. **Admin Settings Panel** (if available)
2. **Database directly**:
   ```sql
   UPDATE settings SET value = '/path/to/logo.png' WHERE key = 'school_logo';
   ```
3. **Programmatically**:
   ```php
   \App\Helpers\SettingsHelper::set('school_logo', '/path/to/logo.png');
   ```

### Logo Path Formats

The system supports multiple logo path formats:

- **Relative paths**: `/images/logo.png` → `http://domain.com/images/logo.png`
- **Storage paths**: `storage/logos/school.png` → `http://domain.com/storage/logos/school.png`
- **Full URLs**: `https://example.com/logo.png` → Used as-is

### Default Logo

If no custom logo is set, the system falls back to the default Laravel application logo (SVG).

## Database Schema

The logo setting is stored in the `settings` table:

```sql
INSERT INTO settings (key, value, type, group, label, description, is_public) 
VALUES ('school_logo', '/images/logo.png', 'string', 'school_info', 'School Logo', 'Path to school logo image', 1);
```

## Seeder

The default logo setting is included in `SettingsSeeder.php`:

```php
[
    'key' => 'school_logo',
    'value' => '/images/logo.png',
    'type' => 'string',
    'group' => 'school_info',
    'label' => 'School Logo',
    'description' => 'Path to school logo image',
    'is_public' => true,
],
```

## Usage Examples

### In Blade Templates

```blade
<!-- Simple logo display -->
<x-school-logo />

<!-- Logo with custom size -->
<x-school-logo class="h-16 w-16" />

<!-- Logo in header -->
<div class="flex items-center">
    <x-school-logo class="h-10 w-auto" />
    <h1 class="ml-3 text-xl font-bold">
        {{ \App\Helpers\SettingsHelper::getSchoolName() }}
    </h1>
</div>
```

### In Controllers

```php
// Check if logo exists
if (\App\Helpers\SettingsHelper::hasSchoolLogo()) {
    $logoUrl = \App\Helpers\SettingsHelper::getSchoolLogoUrl();
    // Use logo in response
}

// Set a new logo
\App\Helpers\SettingsHelper::set('school_logo', '/uploads/new-logo.png');
```

## File Upload Integration

To allow admins to upload logos, you can create a file upload form:

```blade
<form action="{{ route('settings.logo.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="logo" accept="image/*" required>
    <button type="submit">Upload Logo</button>
</form>
```

```php
// In controller
public function uploadLogo(Request $request)
{
    $request->validate([
        'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);
    
    $path = $request->file('logo')->store('logos', 'public');
    \App\Helpers\SettingsHelper::set('school_logo', 'storage/' . $path);
    
    return redirect()->back()->with('success', 'Logo updated successfully!');
}
```

## Performance Considerations

- Logo settings are cached for 1 hour (3600 seconds)
- Cache is automatically cleared when settings are updated
- Use appropriate image sizes for different contexts
- Consider using WebP format for better performance

## Troubleshooting

### Logo Not Showing

1. Check if the setting exists: `\App\Helpers\SettingsHelper::getSchoolLogo()`
2. Verify the file path is correct and accessible
3. Check file permissions
4. Clear cache: `php artisan cache:clear`

### Logo Too Large/Small

Adjust the CSS classes on the `<x-school-logo>` component:

```blade
<!-- Small logo -->
<x-school-logo class="h-6 w-6" />

<!-- Medium logo -->
<x-school-logo class="h-10 w-auto" />

<!-- Large logo -->
<x-school-logo class="h-16 w-auto" />
```

### Cache Issues

Clear the settings cache:

```php
\App\Models\Setting::clearCache();
```

Or run:

```bash
php artisan cache:clear
```

## Future Enhancements

- Multiple logo sizes (favicon, header, footer)
- Logo optimization and resizing
- Logo versioning and rollback
- Integration with CDN
- Logo watermarking for documents
