@props([
    'color' => 'green',
    'size' => 'md'
])
@php
    $colors = [
        'green' => 'bg-green-100 text-green-800',
        'blue' => 'bg-blue-100 text-blue-800',
        'red' => 'bg-red-100 text-red-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'gray' => 'bg-gray-100 text-gray-800',
    ];
    
    $sizes = [
        'sm' => 'text-xs px-2 py-0.5',
        'md' => 'text-sm px-2.5 py-1',
        'lg' => 'text-base px-3 py-1.5',
    ];
@endphp
<span class="inline-flex items-center rounded-full font-medium
             {{ $colors[$color] ?? $colors['green'] }}
             {{ $sizes[$size] ?? $sizes['md'] }}">
    {{ $slot }}
</span>