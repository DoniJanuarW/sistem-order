@extends('layouts.app')
@section('title', 'Syarat & Ketentuan - Grand Santhi Coffee')

@section('content')
<div class="min-h-screen bg-gray-50 pb-28 relative">

    <div class="bg-white sticky top-0 z-20 shadow-sm border-b border-gray-100">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ url()->previous() }}" class="p-2 -ml-2 rounded-full text-gray-600 hover:bg-gray-50 hover:text-[#014421] transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-xl font-bold text-[#014421]">Syarat & Ketentuan</h1>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 mt-4 space-y-4">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-[10px] text-gray-400 mb-2">Terakhir diperbarui: 1 Juli 2026</p>
            <p class="text-gray-500 text-xs leading-relaxed">
                Selamat datang di Grand Santhi Coffee. Dengan mengakses dan menggunakan aplikasi ini, Anda menyetujui syarat dan ketentuan yang berlaku.
            </p>
        </div>

        <div class="space-y-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <h2 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#014421] text-white rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0">1</span>
                    Penggunaan Layanan
                </h2>
                <div class="text-xs text-gray-500 leading-relaxed space-y-1.5 ml-8">
                    <p>Aplikasi Grand Santhi Coffee disediakan untuk memudahkan pelanggan dalam melakukan pemesanan makanan dan minuman secara digital.</p>
                    <p>Anda bertanggung jawab atas keakuratan informasi yang diberikan saat mendaftar dan melakukan pemesanan.</p>
                    <p>Kami berhak menolak atau membatalkan pesanan yang dianggap mencurigakan atau melanggar ketentuan.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <h2 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#014421] text-white rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0">2</span>
                    Akun Pengguna
                </h2>
                <div class="text-xs text-gray-500 leading-relaxed space-y-1.5 ml-8">
                    <p>Anda wajib menjaga kerahasiaan akun dan kata sandi Anda.</p>
                    <p>Satu akun hanya untuk satu pengguna dan tidak boleh dipindahtangankan.</p>
                    <p>Segala aktivitas yang terjadi pada akun Anda menjadi tanggung jawab Anda sepenuhnya.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <h2 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#014421] text-white rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0">3</span>
                    Pemesanan & Pembayaran
                </h2>
                <div class="text-xs text-gray-500 leading-relaxed space-y-1.5 ml-8">
                    <p>Harga yang tercantum sudah termasuk PPN dan dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya.</p>
                    <p>Pembayaran dilakukan melalui Midtrans dengan berbagai metode yang tersedia.</p>
                    <p>Pesanan akan diproses setelah pembayaran berhasil dikonfirmasi oleh sistem.</p>
                    <p>Kami tidak bertanggung jawab atas keterlambatan pemrosesan pembayaran dari pihak bank atau penyedia layanan pembayaran.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <h2 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#014421] text-white rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0">4</span>
                    Pembatalan & Pengembalian Dana
                </h2>
                <div class="text-xs text-gray-500 leading-relaxed space-y-1.5 ml-8">
                    <p>Pembatalan pesanan hanya dapat dilakukan sebelum pesanan diproses oleh dapur.</p>
                    <p>Pengembalian dana (refund) akan diproses dalam 1-7 hari kerja tergantung metode pembayaran yang digunakan.</p>
                    <p>Pesanan yang sudah disajikan tidak dapat dikembalikan kecuali terdapat ketidaksesuaian dengan pesanan.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <h2 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#014421] text-white rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0">5</span>
                    Privasi & Keamanan Data
                </h2>
                <div class="text-xs text-gray-500 leading-relaxed space-y-1.5 ml-8">
                    <p>Data pribadi Anda dikumpulkan dan digunakan sesuai dengan Kebijakan Privasi kami.</p>
                    <p>Kami tidak akan membagikan data pribadi Anda kepada pihak ketiga tanpa persetujuan Anda, kecuali diwajibkan oleh hukum.</p>
                    <p>Kami menerapkan langkah-langkah keamanan yang wajar untuk melindungi data Anda.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <h2 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#014421] text-white rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0">6</span>
                    Hak Kekayaan Intelektual
                </h2>
                <div class="text-xs text-gray-500 leading-relaxed space-y-1.5 ml-8">
                    <p>Seluruh konten, logo, dan merek yang terdapat dalam aplikasi adalah milik Grand Santhi Coffee.</p>
                    <p>Dilarang memperbanyak, mendistribusikan, atau menggunakan konten tanpa izin tertulis dari kami.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <h2 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#014421] text-white rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0">7</span>
                    Perubahan Ketentuan
                </h2>
                <div class="text-xs text-gray-500 leading-relaxed space-y-1.5 ml-8">
                    <p>Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya.</p>
                    <p>Perubahan akan berlaku efektif segera setelah dipublikasikan di aplikasi.</p>
                    <p>Penggunaan aplikasi setelah perubahan ketentuan berarti Anda menyetujui ketentuan yang telah diperbarui.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <h2 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#014421] text-white rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0">8</span>
                    Kontak Kami
                </h2>
                <div class="text-xs text-gray-500 leading-relaxed space-y-1.5 ml-8">
                    <p>Jika Anda memiliki pertanyaan mengenai syarat dan ketentuan ini, silakan hubungi:</p>
                    <div class="bg-gray-50 rounded-xl p-3 mt-2 space-y-1">
                        <p class="font-bold text-gray-700">Grand Santhi Coffee</p>
                        <p>WhatsApp: +62 895-3891-51830</p>
                        <p>Email: support@grandsanthi.com</p>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center text-[10px] text-gray-300 pb-4">Grand Santhi Coffee &copy; {{ date('Y') }}. Seluruh hak cipta dilindungi.</p>

        <br>
    </div>

    @if(Auth::check() && Auth::user()->role === 'customer')
    <x-customer.bottom-nav active="profil" />
    @endif

</div>
@endsection
