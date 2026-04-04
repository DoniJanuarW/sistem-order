@props(['title' => 'Grand Santhi Menu', 'subtitle' => 'Mau pesan apa hari ini?'])

<div class="bg-[#014421] pt-6 pb-10 px-4 rounded-b-[2.5rem] shadow-md relative z-10">
    <div class="max-w-md mx-auto">
        <div class="flex justify-between items-center mb-4 px-1">
            <div>
                <p class="text-[#d1fae5] text-sm">{{ $subtitle }}</p>
                <h2 class="text-white text-xl font-bold">{{ $title }}</h2>
            </div>
            
            <div class="relative">
                @guest
                <a href="{{ route('login') }}" class="bg-white/10 p-2 rounded-full backdrop-blur-sm flex items-center justify-center text-white hover:bg-white/20 transition-all cursor-pointer" title="Login Sekarang">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </a>
                @endguest
            </div>
        </div>

        {{ $slot }}
    </div>
</div>