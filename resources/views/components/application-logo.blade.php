@props([
    'variant' => 'light', // 'light', 'dark', 'monogram', 'monogram-dark'
    'class' => 'h-9 w-auto',
])

@php
    $logoSrc = match($variant) {
        'dark' => asset('brand/logos/dark/HostDevPro-horizontal-gradient.webp'),
        'dark-white' => asset('brand/logos/dark/HostDevPro-horizontal-white.webp'),
        'monogram' => asset('brand/logos/dark/HDP-monogram-gradient.webp'),
        'monogram-light' => asset('brand/logos/light/HDP-monogram-gradient.webp'),
        'light-dark' => asset('brand/logos/light/HostDevPro-horizontal-dark.webp'),
        default => asset('brand/logos/light/HostDevPro-horizontal-gradient.webp'),
    };
@endphp

<img src="{{ $logoSrc }}" alt="HostDevPro" {{ $attributes->merge(['class' => $class]) }}>
