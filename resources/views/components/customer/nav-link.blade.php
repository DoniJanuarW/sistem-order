@props(['tab', 'route', 'label', 'isActive' => false])
<a href="{{route($route)}}" onclick="changeTab('{{ $tab }}'); return false;" id="tab-{{ $tab }}" class="nav-item flex flex-col items-center gap-1 transition-colors {{ $isActive ? 'text-[#014421]' : 'text-gray-400 hover:text-[#014421]' }}">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {{ $slot }} 
        @if($label == 'Beranda') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path> @endif
        @if($label == 'Riwayat') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path> @endif
        @if($label == 'Pindai') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7V5a2 2 0 0 1 2-2h2m10 0h2a2 2 0 0 1 2 2v2m0 10v2a2 2 0 0 1-2 2h-2m-10 0H5a2 2 0 0 1-2-2v-2"></path>@endif
        @if($label == 'Profil') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path> @endif
    </svg>
    <span class="text-[10px] font-semibold">{{ $label }}</span>
</a>