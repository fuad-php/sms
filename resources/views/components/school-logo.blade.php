@php
    $logoUrl = \App\Helpers\SettingsHelper::getSchoolLogoUrl();
    $hasCustomLogo = \App\Helpers\SettingsHelper::hasSchoolLogo();
@endphp

@if($hasCustomLogo && $logoUrl)
    <img src="{{ $logoUrl }}" 
         alt="{{ \App\Helpers\SettingsHelper::getSchoolName() }} Logo" 
         {{ $attributes->merge(['class' => 'school-logo']) }}>
@else
    {{-- Fallback to default application logo --}}
    <x-application-logo {{ $attributes }} />
@endif
