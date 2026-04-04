@props([
    'icon' => 'ti ti-home',
    'url' => '#'
])
@php
    $active = request()->is($url) || request()->is($url.'/*');
@endphp
<li>
    <a href="{{ url($url) }}"
       class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium
              {{ $active 
                  ? 'bg-green-800 text-white shadow-lg' 
                  : 'text-gray-200 hover:bg-green-700 hover:text-white' }}
              transition-all duration-200 group">
        <i class="{{ $icon }} text-lg {{ $active ? 'text-white' : 'text-gray-300 group-hover:text-white' }}"></i>
        <span>{{ $slot }}</span>
        @if($active)
            <i class="ti ti-chevron-right ml-auto text-white"></i>
        @endif
    </a>
</li>
