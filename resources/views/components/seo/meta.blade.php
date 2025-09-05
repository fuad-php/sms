@php
    $routeName = request()->route()?->getName() ?? 'unknown';
    $locale = app()->getLocale();
    $meta = \App\Helpers\SeoHelper::for($routeName, $locale);
@endphp

@if(!empty($meta['title']))
    <title>{{ $meta['title'] }}</title>
@endif

@if(!empty($meta['description']))
    <meta name="description" content="{{ $meta['description'] }}">
@endif

@if(!empty($meta['keywords']))
    <meta name="keywords" content="{{ $meta['keywords'] }}">
@endif

<meta property="og:type" content="website">
@if(!empty($meta['og_title']))
    <meta property="og:title" content="{{ $meta['og_title'] }}">
@endif
@if(!empty($meta['og_description']))
    <meta property="og:description" content="{{ $meta['og_description'] }}">
@endif
@if(!empty($meta['og_image']))
    <meta property="og:image" content="{{ $meta['og_image'] }}">
@endif

@if(!empty($meta['canonical_url']))
    <link rel="canonical" href="{{ $meta['canonical_url'] }}">
@endif


