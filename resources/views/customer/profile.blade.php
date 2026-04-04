@extends('layouts.app')
@section('title', 'Profil Saya - Grand Santhi Coffee')

@section('content')
<div class="min-h-screen bg-gray-50 pb-28 relative">

	<div class="bg-white sticky top-0 z-20 shadow-sm border-b border-gray-100">
		<div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between">
			<h1 class="text-xl font-bold text-[#014421]">Profil Saya</h1>

			{{-- Tombol Notifikasi (Opsional) --}}
			<button class="relative p-2 text-gray-400 hover:text-[#014421] transition-colors">
				<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
				<span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
			</button>
		</div>
	</div>

	<div class="max-w-md mx-auto px-4 mt-6 space-y-6">

		<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 relative overflow-hidden">
			<div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-4 -mt-4 opacity-50"></div>

			<div class="relative">
				<img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=014421&color=fff&size=128" 
				alt="Avatar" 
				class="w-16 h-16 rounded-full border-2 border-white shadow-md object-cover">
				<div class="absolute bottom-0 right-0 bg-green-500 w-4 h-4 rounded-full border-2 border-white"></div>
			</div>

			<div class="flex-1 relative z-10">
				<h2 class="text-lg font-bold text-gray-800 line-clamp-1">{{ Auth::user()->name }}</h2>
				<p class="text-xs text-gray-500 mb-2">{{ Auth::user()->email }}</p>
				<span class="inline-block bg-green-100 text-[#014421] text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wide">
					Customer
				</span>
			</div>

			<a href="#" class="p-2 bg-gray-50 rounded-full text-gray-500 hover:bg-[#014421] hover:text-white transition-all">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
			</a>
		</div>

		<div>
			<h3 class="text-sm font-bold text-gray-400 mb-3 px-1 uppercase tracking-wider">Akun Saya</h3>
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-50">

				{{-- Edit Profile --}}
				<a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors group">
					<div class="flex items-center gap-3">
						<div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
							<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
						</div>
						<span class="text-sm font-semibold text-gray-700">Edit Profil</span>
					</div>
					<svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
				</a>

				{{-- Ubah Password --}}
				<a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors group">
					<div class="flex items-center gap-3">
						<div class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
							<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
						</div>
						<span class="text-sm font-semibold text-gray-700">Ganti Password</span>
					</div>
					<svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
				</a>

				{{-- Alamat Tersimpan --}}
				<a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors group">
					<div class="flex items-center gap-3">
						<div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
							<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
						</div>
						<span class="text-sm font-semibold text-gray-700">Daftar Alamat</span>
					</div>
					<svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
				</a>

			</div>
		</div>

		<div>
			<h3 class="text-sm font-bold text-gray-400 mb-3 px-1 uppercase tracking-wider">Info Lainnya</h3>
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-50">

				<a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors group">
					<div class="flex items-center gap-3">
						<div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-[#014421] group-hover:scale-110 transition-transform">
							<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
						</div>
						<span class="text-sm font-semibold text-gray-700">Pusat Bantuan</span>
					</div>
					<svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
				</a>

				<a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors group">
					<div class="flex items-center gap-3">
						<div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 group-hover:scale-110 transition-transform">
							<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
						</div>
						<span class="text-sm font-semibold text-gray-700">Syarat & Ketentuan</span>
					</div>
					<svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
				</a>

			</div>
		</div>

		<div class="pt-2">
			<button id="btn-logout" onclick="confirmLogout()" class="w-full bg-white border border-red-100 text-red-500 font-bold py-3.5 rounded-xl hover:bg-red-50 active:scale-[0.98] transition-all flex items-center justify-center gap-2 shadow-sm relative overflow-hidden disabled:opacity-70 disabled:cursor-not-allowed">
				
				<div id="text-logout" class="flex items-center gap-2 transition-transform">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
					<span>Keluar Aplikasi</span>
				</div>

				<div id="spinner-logout" class="absolute inset-0 flex items-center justify-center hidden bg-white">
					<svg class="animate-spin h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
						<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
						<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"></path>
					</svg>
					<span class="ml-2 text-sm text-red-500">Memproses...</span>
				</div>

			</button>
		</div>

		<p class="text-center text-[10px] text-gray-300">Grand Santhi Coffee App v1.0.0</p>

	</div>


	<x-customer.bottom-nav active="profil" />

</div>
@endsection

@section('js')
<script>
	function confirmLogout() {
		Swal.fire({
			title: 'Ingin keluar?',
			text: "Sesi Anda akan diakhiri.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#ef4444',
			cancelButtonColor: '#d1d5db',
			confirmButtonText: 'Ya, Keluar',
			cancelButtonText: 'Batal',
			reverseButtons: true
		}).then(async (result) => {
			if (result.isConfirmed) {
				
                // 1. Ambil Element
				const btn = document.getElementById('btn-logout');
				const text = document.getElementById('text-logout');
				const spinner = document.getElementById('spinner-logout');

                // 2. Set Loading State (Disable tombol & Munculkan spinner)
				btn.disabled = true;
                text.classList.add('opacity-0'); // Sembunyikan teks lama
                spinner.classList.remove('hidden'); // Munculkan spinner

                try {
                    // 3. Request AJAX
                	const response = await fetch("{{ route('logout') }}", {
                		method: 'POST',
                		headers: { 
                			'X-CSRF-TOKEN': "{{ csrf_token() }}", 
                			'Accept': 'application/json',
                			'Content-Type': 'application/json'
                		}
                	});

                    // 4. Cek Response
                    // Laravel biasanya return redirect 302 atau 204 No Content saat logout sukses
                	if (response.ok || response.status === 204) {
                        // Redirect manual client-side
                		window.location.href = "{{ route('login') }}"; 
                	} else {
                		throw new Error('Gagal logout');
                	}

                } catch (error) {
                	console.error(error);
                	
                    // 5. Error Handling (Reset Tombol)
                	Swal.fire({ 
                		toast: true, 
                		position: 'top-end', 
                		icon: 'error', 
                		title: 'Gagal logout, silakan coba lagi.', 
                		showConfirmButton: false, 
                		timer: 3000 
                	});

                    // Balikin tampilan tombol
                	btn.disabled = false;
                	text.classList.remove('opacity-0');
                	spinner.classList.add('hidden');
                }
            }
        });
	}
</script>
@endsection