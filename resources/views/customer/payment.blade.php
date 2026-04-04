@extends('layouts.app')
@section('title', 'Pembayaran - Grand Santhi Coffee')

@section('content')
<div class="min-h-screen bg-gray-50 pb-20 relative">

    <div class="bg-[#014421] pt-8 pb-20 px-6 rounded-b-[3rem] shadow-lg relative overflow-hidden">
        <div class="absolute top-[-20px] right-[-20px] w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        
        <div class="text-center relative z-10">
            <p class="text-green-100 text-sm mb-1">Total Pembayaran</p>
            <h1 class="text-3xl font-bold text-white mb-2">
                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
            </h1>
            <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs text-white">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Order ID: #{{ $order->order_code }}</span>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 -mt-12 relative z-20">
        <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">

            <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                <span class="text-sm text-gray-500">Nomor Meja</span>
                <span class="text-lg font-bold text-gray-800">
                    {{ $order->table->table_number}}
                </span>
            </div>

            {{-- 1. KONDISI SUKSES / SELESAI --}}
            @if($order->payment->payment_status == 'success' || $order->status == 'completed')

            <div class="text-center py-8">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-[#014421]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#014421] mb-2">Pembayaran Berhasil!</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Terima kasih, pesanan Anda telah lunas dan sedang disiapkan oleh dapur kami.
                </p>

                <a href="{{ route('customer.order.history') }}" class="block w-full text-center bg-[#014421] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-900/20 hover:bg-green-900 transition-all">
                    Lihat Riwayat Pesanan
                </a>
            </div>

            {{-- 2. KONDISI MENUNGGU VERIFIKASI (MANUAL UPLOAD LAMA) --}}
            @elseif($order->status == 'pending' && $order->payment->method != 'cash' && $order->payment->method != 'midtrans' && $order->payment->transfer_proof != null && $order->payment->payment_status != 'success')

            <div class="text-center py-8">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Menunggu Verifikasi</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Bukti pembayaran telah diterima. Mohon tunggu sebentar, kasir kami sedang memverifikasi pembayaran Anda.
                </p>
                <button onclick="window.location.reload()" class="text-[#014421] font-bold text-sm underline">
                    Refresh Status
                </button>
            </div>

            {{-- 3. KONDISI BAYAR ONLINE (MIDTRANS) --}}
            @elseif($order->payment->method == 'midtrans' && $order->payment->payment_status == 'pending')

            <div class="text-center py-6">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Selesaikan Pembayaran</h3>
                <p class="text-sm text-gray-500 leading-relaxed mb-6">
                    Silakan klik tombol di bawah ini untuk melanjutkan proses pembayaran secara online menggunakan QRIS, E-Wallet, atau Virtual Account.
                </p>
                <button id="pay-button" class="w-full !bg-[#014421] hover:bg-green-900 text-white py-3.5 rounded-xl font-bold shadow-lg shadow-green-900/20 active:scale-[0.98] transition-all">
                    Bayar Sekarang
                </button>
            </div>

            {{-- 4. KONDISI CASH (TUNAI) --}}
            @elseif($order->payment->method == 'cash') 

            <div class="text-center py-6">
                <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Bayar di Kasir</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Silakan menuju kasir dan sebutkan nomor meja 
                    <span class="font-bold text-gray-800">{{ $order->table->table_number}}</span> 
                    atau tunjukkan halaman ini untuk melakukan pembayaran tunai.
                </p>
            </div>

            <div class="mt-4">
                <a href="{{ route('customer.order.history') }}" class="block w-full text-center bg-gray-100 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-200 transition-colors text-sm">
                    Cek Status Pesanan
                </a>
            </div>

            {{-- 5. KONDISI TRANSFER MANUAL (LEGACY/LAMA) --}}
            @else

            <div class="mb-6">
                <p class="text-sm font-bold text-gray-700 mb-3">Transfer ke Rekening:</p>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex justify-between items-center text-center">
                    <div>
                        @if($order->payment->method == 'qris')
                        <img src="{{ asset('assets/images/qris-dummy.webp') }}" alt="QRIS" class="h-full mb-2"> 
                        <p class="text-xs text-gray-500">Scan QRIS di atas</p>
                        @else
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-4 mb-2">
                        <p class="font-mono text-lg font-bold text-gray-800 tracking-wider">123 456 7890</p>
                        <p class="text-xs text-gray-500">a.n Grand Santhi Coffee</p>
                        @endif
                    </div>

                    @if($order->payment->method != 'qris')
                    <button onclick="copyToClipboard('1234567890')" class="text-[#014421] text-xs font-bold hover:underline">
                        Salin
                    </button>
                    @endif
                </div>
            </div>

            <form id="paymentForm" onsubmit="submitPayment(event)" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti Transfer</label>

                    <div class="relative">
                        <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        onchange="previewImage(event)">

                        <div class="w-full bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-6 text-center transition-all hover:bg-green-50 hover:border-[#014421]" id="upload-placeholder">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-xs text-gray-500 font-medium">Klik untuk upload foto</p>
                            <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                        </div>

                        <div class="hidden w-full h-48 bg-gray-100 rounded-xl overflow-hidden relative" id="image-preview-container">
                            <img id="image-preview" src="#" class="w-full h-full object-cover">
                            <button type="button" onclick="resetImage()" class="absolute top-2 right-2 !bg-[#eb1313] text-white rounded-full p-1 shadow-md hover:bg-[#eb1313] z-20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="w-full !bg-[#014421] hover:bg-green-900 text-white py-3.5 rounded-xl font-bold shadow-lg shadow-green-900/20 active:scale-[0.98] transition-all flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                    <span id="btnText">Konfirmasi Pembayaran</span>
                    <svg id="btnSpinner" class="hidden w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"/></svg>
                </button>
            </form>

            @endif

        </div>

        <div class="mt-6 text-center">
            <p class="text-xs text-gray-400">
                Butuh bantuan? <a href="#" class="text-[#014421] font-bold underline">Hubungi Pelayan</a>
            </p>
        </div>

    </div>

</div>
@endsection

@section('js')

{{-- SCRIPT KHUSUS MIDTRANS JIKA METODENYA MIDTRANS & MASIH PENDING --}}
@if($order->payment->method == 'midtrans' && $order->payment->payment_status == 'pending')
<script src="{{ env('MIDTRANS_SNAP_URL', 'https://app.sandbox.midtrans.com/snap/snap.js') }}" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    async function syncPaymentStatus(paymentResult, title, message, iconType) {
        try {
            await fetch("{{ url('/midtrans-callback') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify(paymentResult),
            });

            Swal.fire(title, message, iconType).then(() => {
                window.location.reload();
            });
        } catch (error) {
            Swal.fire(title, message, iconType).then(() => {
                window.location.reload();
            });
        }
    }

    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.onclick = function() {
            window.snap.pay('{{ $order->payment->snap_token }}', {
                onSuccess: function(result){
                    result.transaction_status = 'settlement'; // Paksa status settlement untuk AJAX
                    syncPaymentStatus(result, 'Berhasil!', 'Pembayaran telah berhasil!', 'success');
                },
                onPending: function(result){
                    syncPaymentStatus(result, 'Tertunda', 'Silakan selesaikan pembayaran Anda.', 'info');
                },
                onError: function(result){
                    Swal.fire('Gagal', 'Pembayaran gagal diproses.', 'error');
                },
                onClose: function(){
                    Swal.fire('Perhatian', 'Anda menutup pop-up sebelum menyelesaikan pembayaran.', 'warning');
                }
            });
        };
    }
</script>
@endif

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'success',
            title: 'No. Rekening Disalin',
            showConfirmButton: false,
            timer: 1500
        });
    }

    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('upload-placeholder').classList.add('hidden');
                document.getElementById('image-preview-container').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function resetImage() {
        document.getElementById('payment_proof').value = "";
        document.getElementById('upload-placeholder').classList.remove('hidden');
        document.getElementById('image-preview-container').classList.add('hidden');
    }

    // AJAX Submit untuk Upload Bukti Manual (Legacy)
    async function submitPayment(e) {
        e.preventDefault();

        const form = document.getElementById('paymentForm');
        const btn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const formData = new FormData(form);

        btn.disabled = true;
        btnText.textContent = 'Mengupload...';
        btnSpinner.classList.remove('hidden');

        try {
            const response = await fetch("{{ route('customer.payment.confirm', $order->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Bukti pembayaran berhasil diupload.',
                    confirmButtonColor: '#014421'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(result.message || 'Gagal mengupload bukti.');
            }

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message,
                confirmButtonColor: '#d33'
            });
            
            btn.disabled = false;
            btnText.textContent = 'Konfirmasi Pembayaran';
            btnSpinner.classList.add('hidden');
        }
    }

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#014421'
    });
    @endif
</script>
@endsection