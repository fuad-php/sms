<?php

namespace App\Helpers;

use App\Models\SeoMeta;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class SeoHelper
{
    /**
     * Resolve SEO metadata for the current request route and locale.
     * Falls back to default locale, then to sensible defaults from settings.
     */
    public static function forCurrent(): array
    {
        $route = request()->route();
        $routeName = $route?->getName() ?? 'unknown';
        $locale = app()->getLocale();

        return self::for($routeName, $locale);
    }

    /**
     * Resolve SEO metadata for given route name and locale.
     */
    public static function for(string $routeName, string $locale): array
    {
        $cacheKey = "seo_meta:{$routeName}:{$locale}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($routeName, $locale) {
            $meta = SeoMeta::where('route_name', $routeName)
                ->where('locale', $locale)
                ->first();

            if (!$meta) {
                // fallback to default locale 'en'
                $meta = SeoMeta::where('route_name', $routeName)
                    ->where('locale', 'en')
                    ->first();
            }

            $schoolName = SettingsHelper::getSchoolName();

            return [
                'title' => $meta?->title ?: $schoolName,
                'description' => $meta?->description ?: (SettingsHelper::get('seo_default_description') ?: __('app.nurturing_minds_building_futures')),
                'keywords' => $meta?->keywords ?: SettingsHelper::get('seo_default_keywords', 'school, education, learning'),
                'og_title' => $meta?->og_title ?: $meta?->title ?: $schoolName,
                'og_description' => $meta?->og_description ?: $meta?->description ?: SettingsHelper::get('seo_default_description', ''),
                'og_image' => $meta?->og_image ?: SettingsHelper::getSchoolLogoUrl(),
                'canonical_url' => $meta?->canonical_url ?: url()->current(),
            ];
        });
    }
}


