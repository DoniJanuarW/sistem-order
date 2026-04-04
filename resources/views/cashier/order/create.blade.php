@extends('layouts.app')
@section('title', 'Grand Santhi Coffee Shop - Pesanan Walk-In')
@section('css')

<meta name="route-table-all" content="{{ route('cashier.table.all') }}">
<meta name="route-menu-all" content="{{ route('cashier.menu.all') }}">
<meta name="route-order-submit" content="{{ route('cashier.order.store') }}">
<style>
    /* Sembunyikan scrollbar untuk kategori menu di mobile tapi tetap bisa di-scroll */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .menu-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .menu-item {
        transition: all 0.2s ease;
    }
    .cart-item {
        animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 pb-10">
    <div class="mb-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Pesanan Walk-In</h1>
            <p class="text-sm md:text-base text-gray-600">Buat pesanan baru untuk customer walk-in</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <div class="lg:col-span-3 order-2 lg:order-1">
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6 h-full">
                <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-4">Pilih Menu</h2>
                
                <div class="flex gap-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
                    <button data-category="all" class="category-btn active bg-blue-600 text-white px-4 py-2 rounded-lg text-sm md:text-base font-medium whitespace-nowrap">
                        Semua
                    </button>
                    <button data-category="makanan" class="category-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm md:text-base font-medium whitespace-nowrap">
                        Makanan
                    </button>
                    <button data-category="minuman" class="category-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm md:text-base font-medium whitespace-nowrap">
                        Minuman
                    </button>
                    <button data-category="snack" class="category-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm md:text-base font-medium whitespace-nowrap">
                        Snack
                    </button>
                </div>

                <div id="menuContainer" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
                    </div>
                
                <div id="loadingState" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
                    @for ($i = 0; $i < 8; $i++)
                    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-4 md:p-5 space-y-4">
                        <div class="h-4 md:h-5 bg-gray-200 rounded w-2/3 skeleton"></div>
                        <div class="h-3 md:h-4 bg-gray-200 rounded w-1/2 skeleton"></div>
                        <div class="space-y-2">
                            <div class="h-2 md:h-3 bg-gray-200 rounded skeleton"></div>
                            <div class="h-2 md:h-3 bg-gray-200 rounded skeleton"></div>
                            <div class="h-2 md:h-3 bg-gray-200 rounded skeleton"></div>
                        </div>
                        <div class="h-8 md:h-10 bg-gray-200 rounded skeleton"></div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 order-1 lg:order-2">
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:sticky lg:top-6">
                <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-4">Informasi Pesanan</h2>
                <div class="mb-6 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Customer</label>
                        <input type="text" id="customerName" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan nama">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Tipe Pesanan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer relative">
                                <input type="radio" name="order_type" value="dine_in" class="peer opacity-0 absolute" onchange="toggleOrderType(this.value)" checked>
                                <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 transition-all h-full peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                    <span class="text-xs font-bold text-center">🍽 Makan di Tempat</span>
                                </div>
                            </label>
                            <label class="cursor-pointer relative">
                                <input type="radio" name="order_type" value="takeaway" class="peer opacity-0 absolute" onchange="toggleOrderType(this.value)">
                                <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 transition-all h-full peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                    <span class="text-xs font-bold text-center">🛍 Bawa Pulang</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="tableSelectionSection" class="transition-all duration-300">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Meja</label>
                        <input type="hidden" id="tableNo" name="table_no">

                        <div id="tableGrid" class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-3 gap-2 md:gap-3 max-h-48 overflow-y-auto p-1">
                            <p class="col-span-full text-sm text-gray-400">Memuat meja...</p>
                        </div>

                        <p id="tableError" class="text-red-500 text-xs mt-1 hidden">Silakan pilih meja terlebih dahulu</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-3">Pesanan</h3>
                    <div id="cartContainer" class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        <p class="text-gray-500 text-sm text-center py-4">Belum ada item</p>
                    </div>
                </div>

                <div class="border-t pt-4 mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm md:text-base text-gray-600">Subtotal</span>
                        <span class="text-sm md:text-base font-semibold text-gray-800">Rp <span id="totalPrice">0</span></span>
                    </div>
                    <div class="flex justify-between items-center text-base md:text-lg font-bold">
                        <span>Total</span>
                        <span class="text-blue-600">Rp <span id="finalTotal">0</span></span>
                    </div>
                </div>

                <button type="button" data-action="open-payment-modal" class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 md:py-3 rounded-lg font-bold text-base md:text-lg transition duration-200">
                    Buat Pesanan
                </button>
            </div>
        </div>
    </div>
</div>

<div id="paymentModal" class="hidden fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl p-4 md:p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-bold mb-4 text-center md:text-left">Pilih Metode Pembayaran</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-3 mb-4">
            <button type="button"
                    data-payment-method="cash" 
                    data-action="set-payment-method"
                    class="border-2 p-3 md:p-4 rounded-lg hover:border-blue-500 focus:outline-none focus:border-blue-500 transition-colors text-sm md:text-base flex flex-col items-center gap-1">
                <span class="text-xl md:text-2xl">💵</span> Cash
            </button>
            <button type="button"
                    data-payment-method="qris" 
                    data-action="set-payment-method"
                    class="border-2 p-3 md:p-4 rounded-lg hover:border-blue-500 focus:outline-none focus:border-blue-500 transition-colors text-sm md:text-base flex flex-col items-center gap-1">
                <span class="text-xl md:text-2xl">📱</span> QRIS
            </button>
            <button type="button"
                    data-payment-method="transfer" 
                    data-action="set-payment-method"
                    class="col-span-2 md:col-span-1 border-2 p-3 md:p-4 rounded-lg hover:border-blue-500 focus:outline-none focus:border-blue-500 transition-colors text-sm md:text-base flex flex-col items-center gap-1">
                <span class="text-xl md:text-2xl">💳</span> Transfer
            </button>
        </div>
        
        <div id="cashInputSection" class="hidden mb-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
            <label class="block text-sm font-medium mb-2 text-gray-700">Jumlah Uang Tunai</label>
            <input type="number" 
                   id="cashAmount" 
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                   placeholder="Masukkan nominal...">
            <div class="mt-2 text-sm text-gray-600 flex justify-between items-center border-t border-gray-200 pt-2">
                <span>Kembalian:</span>
                <span id="changeDisplay" class="font-bold text-green-600 text-base">Rp 0</span>
            </div>
        </div>
        
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
            <div class="flex justify-between items-center text-lg md:text-xl font-bold">
                <span class="text-blue-800">Total Bayar:</span>
                <span class="text-blue-700">Rp <span id="totalPayment">0</span></span>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-2">
            <button type="button" 
                    data-action="close-payment-modal"
                    class="w-full sm:w-1/3 bg-gray-200 text-gray-800 px-4 py-2.5 rounded-lg hover:bg-gray-300 font-medium transition-colors order-2 sm:order-1">
                Batal
            </button>
            <button type="button" 
                    id="processPaymentBtn"
                    data-action="process-payment"
                    class="w-full sm:w-2/3 bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 font-medium transition-colors order-1 sm:order-2 flex justify-center items-center">
                Proses Bayar
            </button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function toggleOrderType(type) {
    const tableSection = document.getElementById('tableSelectionSection');
    const tableInput = document.getElementById('tableNo');
    
    if (type === 'takeaway') {
        tableSection.style.display = 'none'; 
        tableInput.value = null;
    } else {
        tableSection.style.display = 'block';
    }
}
</script>
@vite(['resources/js/cashier/walkin.js'])
@endsection