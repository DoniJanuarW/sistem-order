@extends('layouts.app')
@section('title', 'Keranjang - Grand Santhi Coffee')

@section('css')
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 pb-40 relative">

    <div class="bg-white sticky top-0 z-20 shadow-sm border-b border-gray-100">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between">

            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="p-2 -ml-2 rounded-full text-gray-600 hover:bg-gray-50 hover:text-[#014421] transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>

                <h1 class="text-xl font-bold text-[#014421]">Keranjang Saya</h1>
            </div>
            
            @if(count($cartItems) > 0)
            <button onclick="confirmClearCart()" class="text-xs font-semibold text-red-500 hover:text-red-700 bg-red-50 px-3 py-1.5 rounded-full transition-colors">
                Hapus Semua
            </button>
            @endif
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 mt-4">

        @if(count($cartItems) == 0)
        <div class="flex flex-col items-center justify-center pt-20 pb-10 text-center">
            <div class="bg-green-50 p-6 rounded-full mb-4">
                <svg class="w-16 h-16 text-[#014421] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Keranjang Masih Kosong</h3>
            <p class="text-sm text-gray-500 mb-6 px-10">
                Sepertinya Anda belum memesan menu apapun. Yuk, cari menu favoritmu!
            </p>
            <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-[#014421] text-white rounded-full font-bold shadow-lg shadow-green-900/20 active:scale-95 transition-transform text-sm">
                Lihat Menu
            </a>
        </div>
        
        @else
        <div class="space-y-4" id="cartList">
            @foreach($cartItems as $item)
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 flex gap-3 transition-all" id="item-{{ $item['id'] }}">

                <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 relative">
                    <div class="absolute inset-0 flex items-center justify-center text-3xl">
                        <img src="{{ $item['menu']['image_url']}}" class="w-full h-full object-cover rounded-lg"> 
                    </div>
                </div>

                <div class="flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $item['menu']['name'] }}</h3>
                            <button onclick="deleteItem({{ $item['id'] }})" class="text-gray-400 hover:text-red-500 p-1 -mr-2 -mt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        <p class="text-[#014421] font-bold text-sm">Rp {{ number_format($item['menu']['price'], 0, ',', '.') }}</p>
                        
                        <div class="mt-2 relative">
                            <input type="text" 
                            id="note-{{ $item['id'] }}"
                            value="{{ $item['note'] }}" 
                            onchange="updateCartNote({{ $item['id'] }}, this.value)"
                            class="w-full text-xs bg-gray-50 border-b border-gray-200 focus:border-[#014421] rounded-t px-2 py-1.5 outline-none text-gray-600 placeholder-gray-400 transition-colors"
                            placeholder="Tambah catatan..."
                            >
                            <div class="absolute right-2 top-1.5 pointer-events-none">
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-2">
                        <div class="flex items-center bg-gray-50 rounded-full border border-gray-200 h-7">
                            <button onclick="updateCartQty({{ $item['id'] }}, -1)" class="w-7 h-full flex items-center justify-center text-gray-500 hover:text-[#014421] active:scale-90 transition-transform">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                            </button>
                            <span id="qty-{{ $item['id'] }}" class="w-6 text-center text-xs font-bold text-gray-800">{{ $item['qty'] }}</span>
                            <button onclick="updateCartQty({{ $item['id'] }}, 1)" class="w-7 h-full flex items-center justify-center text-gray-500 hover:text-[#014421] active:scale-90 transition-transform">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#014421]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Tipe Pesanan <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer relative">
                    <input type="radio" name="order_type" value="dine_in" class="peer" onchange="toggleOrderType(this.value)" checked>
                    <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 transition-all h-full peer-checked:border-[#014421] peer-checked:bg-green-50 peer-checked:text-[#014421]">
                        <span class="text-xs font-bold text-center">🍽 Makan di Tempat</span>
                    </div>
                </label>
                <label class="cursor-pointer relative">
                    <input type="radio" name="order_type" value="takeaway" class="peer" onchange="toggleOrderType(this.value)">
                    <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 transition-all h-full peer-checked:border-[#014421] peer-checked:bg-green-50 peer-checked:text-[#014421]">
                        <span class="text-xs font-bold text-center">🛍 Bawa Pulang</span>
                    </div>
                </label>
            </div>
        </div>

        <div id="tableNumberSection" class="mt-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <label for="table_number" class="block text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#014421]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Nomor Meja <span class="text-red-500">*</span>
            </label>
            <input type="number" 
            id="table_number" 
            name="table_number"
            min="1"
            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all font-semibold text-center text-lg"
            placeholder="Masukkan No. Meja"
            oninput="saveTableNumber(this.value)"
            >
            <p class="text-[10px] text-gray-400 mt-1 text-center">Pastikan nomor meja sesuai dengan tempat duduk Anda</p>
        </div>

        <div class="mt-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#014421]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Metode Pembayaran <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-2 gap-3">

                <label class="cursor-pointer relative">
                    <input type="radio" name="payment_method" value="cash" class="peer" onchange="savePaymentMethod(this.value)">
                    <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 transition-all h-full peer-checked:border-[#014421] peer-checked:bg-green-50 peer-checked:text-[#014421]">
                        <svg class="w-6 h-6 mb-1 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-xs font-bold text-center">Bayar di Kasir<br><span class="text-[9px] font-normal">(Tunai)</span></span>
                    </div>
                </label>

                <label class="cursor-pointer relative">
                    <input type="radio" name="payment_method" value="online" class="peer" onchange="savePaymentMethod(this.value)">
                    <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 transition-all h-full peer-checked:border-[#014421] peer-checked:bg-green-50 peer-checked:text-[#014421]">
                        <svg class="w-6 h-6 mb-1 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM5 4h4v4H5V4zm0 12h4v4H5v-4zm10-8h4v4h-4V8z"></path>
                        </svg>
                        <span class="text-xs font-bold text-center">Bayar Online<br><span class="text-[9px] font-normal">(QRIS, VA, E-Wallet)</span></span>
                    </div>
                </label>

            </div>
            <br>
            <p class="text-[10px] text-gray-400 mt-2 ml-1">*Metode pembayaran tidak dapat diubah setelah checkout</p>
        </div>

        <div class="mt-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 space-y-3">
            <h3 class="font-bold text-gray-800 text-sm">Ringkasan Pembayaran</h3>
            
            <div class="flex justify-between text-xs text-gray-600">
                <span>Subtotal</span>
                <span id="summary-subtotal">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
            </div>

            <div class="border-t border-dashed border-gray-200 my-2"></div>

            <div class="flex justify-between items-center">
                <span class="font-bold text-gray-800 text-sm">Total Tagihan</span>
                <span id="summary-total" class="font-bold text-[#014421] text-lg">
                    Rp {{ number_format($cartTotal, 0, ',', '.') }}
                </span>
            </div>
        </div>
        @endif
        
    </div>

    @if(count($cartItems) > 0)
    <div class="fixed bottom-20 left-0 right-0 z-40">
        <div class="max-w-md mx-auto px-4">
            <button onclick="processCheckout()" id="btnCheckout" class="w-full !bg-[#014421] hover:bg-green-900 text-white py-3.5 rounded-full font-bold shadow-lg shadow-green-900/20 active:scale-[0.98] transition-all flex justify-center items-center gap-2">
                <span>Checkout Sekarang</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </div>
    @endif

</div>
@endsection

@section('js')
<script src="{{ env('MIDTRANS_SNAP_URL', 'https://app.sandbox.midtrans.com/snap/snap.js') }}" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Init saved Order Type (Makan di Tempat / Takeaway)
        const savedOrderType = localStorage.getItem('grandSanthi_orderType') || 'dine_in';
        const orderTypeRadio = document.querySelector(`input[name="order_type"][value="${savedOrderType}"]`);
        if(orderTypeRadio) {
            orderTypeRadio.checked = true;
            toggleOrderType(savedOrderType); // Panggil fungsi untuk set display kolom nomor meja
        }

        const savedTable = sessionStorage.getItem('table_number') || localStorage.getItem('grandSanthi_tableNumber');
        if(savedTable && document.getElementById('table_number')) {
            document.getElementById('table_number').value = savedTable;
        }

        const savedPayment = localStorage.getItem('grandSanthi_paymentMethod');
        if(savedPayment) {
            const radioBtn = document.querySelector(`input[name="payment_method"][value="${savedPayment}"]`);
            if(radioBtn) radioBtn.checked = true;
        }
    });

    // Fungsi untuk menyembunyikan/menampilkan kolom Nomor Meja
    function toggleOrderType(val) {
        localStorage.setItem('grandSanthi_orderType', val);
        const tableSection = document.getElementById('tableNumberSection');
        if(val === 'takeaway') {
            tableSection.style.display = 'none'; // Sembunyikan jika Bawa Pulang
        } else {
            tableSection.style.display = 'block'; // Tampilkan jika Dine In
        }
    }

    function saveTableNumber(val) {
        localStorage.setItem('grandSanthi_tableNumber', val);
    }
    function savePaymentMethod(val) {
        localStorage.setItem('grandSanthi_paymentMethod', val);
    }

    function formatRupiah(number) {
        return 'Rp ' + parseInt(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    async function updateCartQty(id, change) {
        const qtySpan = document.getElementById(`qty-${id}`);
        let currentQty = parseInt(qtySpan.innerText);
        let newQty = currentQty + change;
        if (newQty < 1) return; 

        qtySpan.innerText = newQty;

        try {
            const response = await fetch("{{ url('/cart/update-qty') }}/" + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                body: JSON.stringify({ qty: newQty })
            });
            const result = await response.json();

            if (response.ok) {
                updateSummary(result.cart_total);
            } else {
                qtySpan.innerText = currentQty; 
                Swal.fire('Error', result.message, 'error');
            }
        } catch (error) {
            qtySpan.innerText = currentQty;
        }
    }

    async function updateCartNote(id, note) {
        try {
            const response = await fetch("{{ url('/cart/update-note') }}/" + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                body: JSON.stringify({ note: note })
            });

            if (!response.ok) {
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'error',
                    title: 'Gagal menyimpan catatan', showConfirmButton: false, timer: 1500
                });
            }
        } catch (error) {
            console.error('Error updating note:', error);
        }
    }

    function deleteItem(id) {
        Swal.fire({
            title: 'Hapus Menu?',
            text: "Menu ini akan dihapus dari keranjang.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch("{{ url('/cart/') }}/" + id, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
                    });
                    
                    if (response.ok) {
                        const itemEl = document.getElementById(`item-${id}`);
                        itemEl.style.opacity = '0';
                        itemEl.style.transform = 'scale(0.9)';
                        setTimeout(() => window.location.reload(), 300);
                    }
                } catch (error) {
                    Swal.fire('Error', 'Gagal menghapus item', 'error');
                }
            }
        });
    }

    function confirmClearCart() {
        Swal.fire({
            title: 'Kosongkan Keranjang?',
            text: "Semua menu akan dihapus.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Kosongkan'
        }).then(async (result) => {
            if (result.isConfirmed) {
             try {
                await fetch("{{ url('/cart/clear') }}", {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
                });
                window.location.reload();
            } catch (e) {
             window.location.reload();
         }
     }
 });
    }

    async function processCheckout() {
        const paymentMethodEl = document.querySelector('input[name="payment_method"]:checked');
        const paymentMethod = paymentMethodEl ? paymentMethodEl.value : null;
        
        const orderTypeEl = document.querySelector('input[name="order_type"]:checked');
        const orderType = orderTypeEl ? orderTypeEl.value : 'dine_in';
        
        let tableNumber = document.getElementById('table_number').value;

        // Logika Validasi Table Number
        if (orderType === 'dine_in' && !tableNumber) {
            Swal.fire({ icon: 'warning', title: 'Nomor Meja Kosong', text: 'Silakan masukkan nomor meja Anda terlebih dahulu.', confirmButtonColor: '#014421' });
            document.getElementById('tableNumberSection').scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => document.getElementById('table_number').focus(), 500);
            return;
        }

        // Jika tipe pesanan adalah takeaway, ubah table number menjadi null
        if (orderType === 'takeaway') {
            tableNumber = null;
        }

        if (!paymentMethod) {
            Swal.fire({ icon: 'warning', title: 'Pilih Pembayaran', text: 'Silakan pilih metode pembayaran (Tunai/Bayar Online).', confirmButtonColor: '#014421' });
            return;
        }

        const btn = document.getElementById('btnCheckout');
        const originalContent = btn.innerHTML;
        
        btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"/></svg> Memproses...`;
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');

        try {
            const response = await fetch("{{ route('customer.order.checkout') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    order_type: orderType, // Kirim order_type agar database tahu ini takeaway
                    table_number: tableNumber, // tableNumber sudah null jika takeaway
                    payment_method: paymentMethod
                })
            });

            const result = await response.json();

            if (response.ok) {
                localStorage.removeItem('grandSanthi_tableNumber');
                localStorage.removeItem('grandSanthi_paymentMethod');
                // localStorage.removeItem('grandSanthi_orderType'); // Opsional: Hapus atau simpan untuk preferensi selanjutnya

                if (paymentMethod === 'online' && result.snap_token) {
                    async function sendPaymentResult(paymentResult, title, message, iconType) {
                        try {
                            const response = await fetch("{{ url('/midtrans-callback') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}" 
                                },
                                body: JSON.stringify(paymentResult),
                            });

                            if (!response.ok) {
                                throw new Error('Gagal update status di server');
                            }

                            Swal.fire(title, message, iconType).then(() => {
                                window.location.href = result.redirect_url || "/order/history";
                            });

                        } catch (error) {
                            console.error('Error updating payment status:', error);
                            Swal.fire('Error', 'Terjadi kesalahan sinkronisasi data dengan server.', 'error').then(() => {
                                window.location.href = result.redirect_url || "/order/history";
                            });
                        }
                    }

                    window.snap.pay(result.snap_token, {
                        onSuccess: function(midtransResult) {
                            midtransResult.transaction_status = 'settlement'; 
                            sendPaymentResult(midtransResult, 'Berhasil!', 'Pembayaran telah berhasil!', 'success');
                        },
                        onPending: function(midtransResult) {
                            sendPaymentResult(midtransResult, 'Tertunda', 'Selesaikan pembayaran Anda.', 'info');
                        },
                        onError: function(midtransResult) {
                            Swal.fire('Gagal', 'Pembayaran gagal diproses.', 'error');
                            btn.innerHTML = originalContent;
                            btn.disabled = false;
                            btn.classList.remove('opacity-75', 'cursor-not-allowed');
                            window.location.href = "/order/history";
                        },
                        onClose: function() {
                            Swal.fire('Perhatian', 'Anda menutup pop-up sebelum menyelesaikan pembayaran.', 'warning');
                            window.location.href = "/order/history";
                        }
                    });
                } else {
                    window.location.href = result.redirect_url;
                }
            } else {
                throw new Error(result.message || 'Gagal memproses checkout.');
            }

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message,
                confirmButtonColor: '#d33'
            });

            btn.innerHTML = originalContent;
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    }

    function updateSummary(subtotal) {
        document.getElementById('summary-subtotal').innerText = formatRupiah(subtotal);
        document.getElementById('summary-total').innerText = formatRupiah(subtotal);
    }
</script>
@endsection