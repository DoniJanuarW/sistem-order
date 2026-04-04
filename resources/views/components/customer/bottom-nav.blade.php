@props(['active' => 'beranda'])

<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-25 rounded-t-2xl">
    <div class="max-w-md mx-auto px-6">
        <div class="flex justify-between items-center h-20">
            
            <x-customer.nav-link route="dashboard" tab="beranda" icon="beranda" label="Beranda" :isActive="$active == 'beranda'" />
            <x-customer.nav-link route="customer.scan.scanPage" tab="pindai" icon="heart" label="Pindai" :isActive="$active == 'pindai'" />
            
            <div class="relative -top-5">
                <a href="{{ route('customer.cart.index') }}" class="bg-[#014421] w-14 h-14 rounded-full flex items-center justify-center text-white shadow-lg shadow-green-900/30 ring-4 ring-gray-50 transform transition-transform active:scale-95">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span id="sumCart" class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute top-3 right-3 bg-red-500 border border-white text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>

                </a>
            </div>

            <x-customer.nav-link route="customer.order.history" tab="riwayat" icon="clock" label="Riwayat" :isActive="$active == 'riwayat'" />
            <x-customer.nav-link route="customer.profile" tab="profil" icon="user" label="Profil" :isActive="$active == 'profil'" />

        </div>
    </div>
</div>