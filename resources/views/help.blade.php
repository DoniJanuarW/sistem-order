@extends('layouts.app')
@section('title', 'Pusat Bantuan - Grand Santhi Coffee')

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
                <h1 class="text-xl font-bold text-[#014421]">Pusat Bantuan</h1>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 mt-4 space-y-4">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-base font-bold text-gray-800 mb-2">Selamat Datang di Pusat Bantuan</h2>
            <p class="text-gray-500 text-xs leading-relaxed">
                Temukan jawaban atas pertanyaan Anda seputar penggunaan aplikasi Grand Santhi Coffee.
            </p>
        </div>

        <div class="space-y-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors" data-target="faq1">
                    <span class="text-sm font-semibold text-gray-800 pr-4">Bagaimana cara melakukan pemesanan?</span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-300 faq-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="faq1" class="faq-content hidden px-4 pb-4">
                    <div class="border-t border-gray-100 pt-3 text-xs text-gray-500 leading-relaxed space-y-1.5">
                        <p><strong class="text-gray-700">1. Scan QR Code</strong> yang tersedia di meja restoran.</p>
                        <p><strong class="text-gray-700">2. Pilih menu</strong> yang Anda inginkan dari katalog.</p>
                        <p><strong class="text-gray-700">3. Atur jumlah & catatan</strong> khusus jika diperlukan.</p>
                        <p><strong class="text-gray-700">4. Klik "Tambah ke Keranjang"</strong> dan lanjutkan memilih menu lain.</p>
                        <p><strong class="text-gray-700">5. Buka keranjang</strong> dan klik <strong class="text-gray-700">"Checkout"</strong> untuk menyelesaikan pesanan.</p>
                        <p><strong class="text-gray-700">6. Pilih metode pembayaran</strong> dan selesaikan transaksi.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors" data-target="faq2">
                    <span class="text-sm font-semibold text-gray-800 pr-4">Metode pembayaran apa saja yang tersedia?</span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-300 faq-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="faq2" class="faq-content hidden px-4 pb-4">
                    <div class="border-t border-gray-100 pt-3 text-xs text-gray-500 leading-relaxed">
                        <p>Kami menerima pembayaran melalui:</p>
                        <ul class="list-disc list-inside mt-1.5 space-y-1">
                            <li>Transfer Bank (BCA, BNI, BRI, Mandiri)</li>
                            <li>QRIS (GoPay, OVO, Dana, ShopeePay)</li>
                            <li>Kartu Debit/Kredit</li>
                            <li>Gerai Retail (Alfamart, Indomaret)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors" data-target="faq3">
                    <span class="text-sm font-semibold text-gray-800 pr-4">Bagaimana cara membatalkan pesanan?</span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-300 faq-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="faq3" class="faq-content hidden px-4 pb-4">
                    <div class="border-t border-gray-100 pt-3 text-xs text-gray-500 leading-relaxed">
                        <p>Pesanan dapat dibatalkan selama statusnya masih <strong class="text-gray-700">"Pending"</strong> atau <strong class="text-gray-700">"Menunggu Pembayaran"</strong>. Buka halaman pembayaran dan klik tombol <strong class="text-gray-700">"Batalkan Pesanan"</strong>. Jika pesanan sudah diproses, silakan hubungi kasir atau staff kami.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors" data-target="faq4">
                    <span class="text-sm font-semibold text-gray-800 pr-4">Berapa lama pesanan saya diproses?</span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-300 faq-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="faq4" class="faq-content hidden px-4 pb-4">
                    <div class="border-t border-gray-100 pt-3 text-xs text-gray-500 leading-relaxed">
                        <p>Estimasi waktu penyajian adalah <strong class="text-gray-700">10-20 menit</strong> setelah pesanan dikonfirmasi, tergantung pada jenis menu dan tingkat keramaian restoran. Anda dapat memantau status pesanan melalui halaman <strong class="text-gray-700">Riwayat Pesanan</strong>.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors" data-target="faq5">
                    <span class="text-sm font-semibold text-gray-800 pr-4">Bagaimana jika pembayaran gagal?</span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-300 faq-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="faq5" class="faq-content hidden px-4 pb-4">
                    <div class="border-t border-gray-100 pt-3 text-xs text-gray-500 leading-relaxed">
                        <p>Jika pembayaran gagal, Anda dapat:</p>
                        <ul class="list-disc list-inside mt-1.5 space-y-1">
                            <li>Coba kembali melakukan pembayaran dari halaman pembayaran</li>
                            <li>Pilih metode pembayaran lain yang tersedia</li>
                            <li>Hubungi kasir untuk bantuan lebih lanjut</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors" data-target="faq6">
                    <span class="text-sm font-semibold text-gray-800 pr-4">Bagaimana cara menghubungi customer service?</span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-300 faq-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="faq6" class="faq-content hidden px-4 pb-4">
                    <div class="border-t border-gray-100 pt-3 text-xs text-gray-500 leading-relaxed space-y-1.5">
                        <p>Anda dapat menghubungi kami melalui:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong class="text-gray-700">WhatsApp:</strong> +62 895-3891-51830</li>
                            <li><strong class="text-gray-700">Email:</strong> support@grandsanthi.com</li>
                            <li><strong class="text-gray-700">Jam Operasional:</strong> Setiap hari, 08:00 - 22:00 WIB</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-100 rounded-2xl p-5 text-center">
            <svg class="w-10 h-10 text-[#014421] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <h3 class="text-sm font-bold text-[#014421] mb-1">Masih butuh bantuan?</h3>
            <p class="text-xs text-green-700">Tim kami siap membantu Anda kapan saja.</p>
        </div>

        <br>
    </div>

    @if(Auth::check() && Auth::user()->role === 'customer')
    <x-customer.bottom-nav active="profil" />
    @endif

</div>
@endsection

@section('js')
<script>
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const content = document.getElementById(targetId);
            const icon = this.querySelector('.faq-icon');

            document.querySelectorAll('.faq-content').forEach(item => {
                if (item !== content) {
                    item.classList.add('hidden');
                    item.previousElementSibling?.querySelector('.faq-icon')?.classList.remove('rotate-180');
                }
            });

            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });
</script>
@endsection
