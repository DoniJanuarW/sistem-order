@extends('layouts.app')
@section('title', 'Riwayat Pesanan - Grand Santhi Coffee')

@section('css')
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 pb-28 relative">

    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between">
            <h1 class="text-xl font-bold text-[#014421]">Riwayat Pesanan</h1>
            
            <button onclick="openFilterModal()" class="p-2 bg-gray-50 rounded-full text-gray-500 hover:text-[#014421] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </button>
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 mt-4 space-y-4 mb-10">

        @forelse($orders as $order)
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 transition-all hover:shadow-md">

            <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-50">
                <div class="flex flex-col">
                    <span class="text-sm text-gray-400 font-medium">{{ $order->order_code }}</span>
                    <span class="text-xs font-bold text-gray-700">
                        {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                    </span>
                </div>

                @php
                $statusColor = 'bg-blue-100 text-blue-600';
                $statusLabel = 'DIPROSES';

                $payment = $order->payment;
                $pStatus = $payment ? $payment->payment_status : 'pending';

                if ($order->status == 'cancelled') {
                    $statusColor = 'bg-red-100 text-red-600';
                    $statusLabel = 'DIBATALKAN';
                }
                elseif ($order->status == 'completed') {
                    $statusColor = 'bg-green-100 text-green-700';
                    $statusLabel = 'SELESAI';
                }
                // Logika Payment Gateway Baru
                elseif ($pStatus == 'pending') {
                    $statusColor = 'bg-orange-100 text-orange-600';
                    $statusLabel = 'BELUM BAYAR';
                }
                elseif ($pStatus == 'expired' || $pStatus == 'failed') {
                    $statusColor = 'bg-red-100 text-red-600';
                    $statusLabel = 'KADALUWARSA';
                }
                @endphp

                <span class="{{ $statusColor }} px-2.5 py-1 rounded-full text-center capitalize text-xs tracking-wide font-bold">
                    {{ $statusLabel }}
                </span>
            </div>

            <div class="flex gap-3 cursor-pointer" onclick="showOrderDetail({{ json_encode($order) }}, {{ json_encode($order->items) }})">
                <div class="w-16 h-16 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 relative">
                    <div class="absolute inset-0 flex items-center justify-center text-2xl">
                        <img src="{{ $order->items->first()->menu->image_url}}" alt="{{ $order->items->first()->menu->name ?? 'Menu' }}" class="w-full h-full object-cover rounded-xl"/>
                    </div>
                </div>

                <div class="flex-1 flex flex-col justify-center">
                    <h3 class="font-bold text-gray-800 text-sm line-clamp-1">
                        {{ $order->items->first()->menu->name ?? 'Menu Terhapus' }}
                    </h3>

                    @if($order->items->count() > 1)
                    <p class="text-xs text-gray-400 mt-0.5">
                        + {{ $order->items->count() - 1 }} menu lainnya
                    </p>
                    @endif

                    <div class="flex justify-between items-end mt-1">
                        <span class="text-[#014421] font-bold text-sm">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-3 pt-3 flex gap-2">
                {{-- Tombol Bayar Sekarang disesuaikan untuk Midtrans --}}
                @if($pStatus == 'pending' && $order->status != 'cancelled')
                <a href="{{ route('customer.payment.paymentPage', $order->id) }}" class="flex-1 bg-[#014421] text-white py-2 rounded-lg text-xs font-bold text-center shadow-lg shadow-green-900/20 active:scale-95 transition-transform flex items-center justify-center">
                    Bayar Sekarang
                </a>
                <a href="{{ route('customer.payment.cancelOrder', $order->id) }}" class="flex-1 bg-red-500 text-white py-2 rounded-lg text-xs font-bold text-center shadow-lg shadow-red-900/20 active:scale-95 transition-transform flex items-center justify-center">
                    Batal Pesanan
                </a>
                @endif
                <button onclick="showOrderDetail({{ json_encode($order) }}, {{ json_encode($order->items) }})" class="flex-1 bg-gray-50 text-gray-600 py-2 rounded-lg text-xs font-bold border border-gray-100 hover:bg-gray-100 active:scale-95 transition-transform">
                    Lihat Detail
                </button>
            </div>

        </div>
        @empty
        <div class="flex flex-col items-center justify-center pt-20 pb-10 text-center">
            <div class="bg-gray-100 p-6 rounded-full mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Belum Ada Pesanan</h3>
            <p class="text-sm text-gray-500 mb-6 px-10">
                Riwayat pesananmu akan muncul di sini setelah kamu melakukan transaksi.
            </p>
            <a href="{{ route('dashboard') }}" class="px-6 py-2.5 !bg-[#014421] text-white rounded-full font-bold shadow-md active:scale-95 transition-transform text-xs">
                Pesan Sekarang
            </a>
        </div>
        @endforelse

    </div>
    <br><br>
    
    <x-customer.bottom-nav active="riwayat" />
    
    <div id="detailModal" class="fixed inset-0 z-50 hidden " role="dialog" aria-modal="true">

        <div onclick="closeDetailModal()" class="absolute inset-0 bg-black/60 backdrop-blur-[2px] transition-opacity opacity-0" id="detailBackdrop"></div>

        <div class="fixed inset-0 z-[110] flex items-end justify-center pointer-events-none">

            <div class="bg-white w-full max-w-md mx-4 overflow-hidden transform pt-3 translate-y-full transition-transform duration-300 ease-out pointer-events-auto border border-gray-200 rounded-t-2xl shadow" id="detailPanel">

                <div class="w-full flex justify-center pt-3 pb-2 cursor-pointer bg-white" onclick="closeDetailModal()">
                    <div class="w-12 h-1 bg-gray-300 rounded-full"></div>
                </div>

                <div class="px-5 pb-5 max-h-[60vh] overflow-y-auto custom-scrollbar">

                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-sm font-bold text-[#014421]">Detail Pesanan</h2>
                            <p class="text-[10px] text-gray-500 font-medium mt-0.5" id="modalDate">...</p>
                        </div>
                        <span id="modalStatus" class="px-2 py-1 bg-gray-50 rounded text-[10px] font-bold uppercase border border-gray-100 tracking-wider">
                            ...
                        </span>
                    </div>

                    <div class="space-y-3 mb-5" id="modalItemsList">
                    </div>

                    <div class="border-t border-dashed border-gray-200 pt-3 space-y-2">
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>Metode Pembayaran</span>
                            <span class="font-bold text-gray-800 uppercase" id="modalPayment">...</span>
                        </div>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>Meja</span>
                            <span class="font-bold text-gray-800" id="modalTable">...</span>
                        </div>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>Pesanan</span>
                            <span class="font-bold text-gray-800" id="modalOrder">...</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 mt-1 border-t border-gray-100">
                            <span class="text-sm font-bold text-gray-700">Total Bayar</span>
                            <span class="text-base font-extrabold text-[#014421]" id="modalTotal">...</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 border-t border-gray-100 flex gap-3">
                    <button onclick="closeDetailModal()" class="flex-1 bg-white border border-gray-200 text-gray-800 py-3 rounded-xl font-bold text-xs hover:bg-gray-100 transition-colors shadow-sm">
                        Tutup
                    </button>
                    <a href="#" id="btnUnduhNota" target="_blank" class="flex-1 !bg-[#014421] border border-transparent text-white py-3 rounded-xl font-bold text-xs hover:bg-green-900 transition-colors shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Nota
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="filterModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div onclick="closeFilterModal()" class="absolute inset-0 bg-black/60 backdrop-blur-[2px] transition-opacity opacity-0" id="filterBackdrop"></div>

        <div class="fixed inset-0 z-[110] flex items-end justify-center pointer-events-none">
            <div class="bg-white w-full max-w-md mx-4 overflow-hidden transform pt-3 translate-y-full transition-transform duration-300 ease-out pointer-events-auto border border-gray-200 rounded-t-2xl shadow" id="filterPanel">

                <div class="w-full flex justify-center pt-3 pb-2 cursor-pointer bg-white" onclick="closeFilterModal()">
                    <div class="w-12 h-1 bg-gray-300 rounded-full"></div>
                </div>

                <form action="{{ url()->current() }}" method="GET">
                    <div class="px-5 pb-5 max-h-[60vh] overflow-y-auto custom-scrollbar">
                        <h2 class="text-base font-bold text-[#014421] mb-5">Filter Riwayat Pesanan</h2>

                        <div class="mb-5">
                            <label class="block text-xs font-bold text-white uppercase mb-3">Status Pesanan</label>
                            <div class="flex flex-wrap gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="status" value="semua" class="peer hidden" {{ request('status', 'semua') == 'semua' ? 'checked' : '' }}>
                                    <div class="px-4 py-2 rounded-full border border-gray-200 text-sm font-medium text-gray-600 transition-colors peer-checked:bg-[#014421] peer-checked:text-white peer-checked:border-[#014421]">
                                        Semua
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status" value="pending" class="peer hidden" {{ request('status') == 'pending' ? 'checked' : '' }}>
                                    <div class="px-4 py-2 rounded-full border border-gray-200 text-sm font-medium text-gray-600 transition-colors peer-checked:bg-[#014421] peer-checked:text-white peer-checked:border-orange-500">
                                        Belum Bayar
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status" value="proses" class="peer hidden" {{ request('status') == 'proses' ? 'checked' : '' }}>
                                    <div class="px-4 py-2 rounded-full border border-gray-200 text-sm font-medium text-gray-600 transition-colors peer-checked:bg-[#014421] peer-checked:text-white peer-checked:border-blue-500">
                                        Diproses
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status" value="completed" class="peer hidden" {{ request('status') == 'completed' ? 'checked' : '' }}>
                                    <div class="px-4 py-2 rounded-full border border-gray-200 text-sm font-medium text-gray-600 transition-colors peer-checked:bg-[#014421] peer-checked:text-white peer-checked:border-green-600">
                                        Selesai
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status" value="cancelled" class="peer hidden" {{ request('status') == 'cancelled' ? 'checked' : '' }}>
                                    <div class="px-4 py-2 rounded-full border border-gray-200 text-sm font-medium text-gray-600 transition-colors peer-checked:bg-[#014421] peer-checked:text-white peer-checked:border-red-500">
                                        Dibatalkan
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Tipe Pesanan</label>
                            <div class="flex flex-wrap gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipe" value="semua" class="peer hidden" {{ request('tipe', 'semua') == 'semua' ? 'checked' : '' }}>
                                    <div class="px-4 py-2 rounded-full border border-gray-200 text-sm font-medium text-gray-600 transition-colors peer-checked:bg-[#014421] peer-checked:text-white peer-checked:border-[#014421]">
                                        Semua
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipe" value="dine_in" class="peer hidden" {{ request('tipe') == 'dine_in' ? 'checked' : '' }}>
                                    <div class="px-4 py-2 rounded-full border border-gray-200 text-sm font-medium text-gray-600 transition-colors peer-checked:bg-[#014421] peer-checked:text-white peer-checked:border-[#014421]">
                                        Dine In
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipe" value="takeaway" class="peer hidden" {{ request('tipe') == 'takeaway' ? 'checked' : '' }}>
                                    <div class="px-4 py-2 rounded-full border border-gray-200 text-sm font-medium text-gray-600 transition-colors peer-checked:bg-[#014421] peer-checked:text-white peer-checked:border-[#014421]">
                                        Takeaway
                                    </div>
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-100 flex gap-3">
                        <a href="{{ url()->current() }}" class="flex-1 bg-white border border-gray-200 text-gray-800 py-3 rounded-xl font-bold text-xs hover:bg-gray-100 transition-colors shadow-sm flex items-center justify-center">
                            Reset
                        </a>
                        <button type="submit" class="flex-1 !bg-[#014421] border border-transparent text-white py-3 rounded-xl font-bold text-xs hover:bg-green-900 transition-colors shadow-sm flex items-center justify-center">
                            Terapkan Filter
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>     
</div>
@endsection

@section('js')
<script>
    function formatRupiah(number) {
        return 'Rp ' + parseInt(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function showOrderDetail(order, items) {
        // 1. Set Info Umum
        document.getElementById('modalDate').innerText = order.order_code;
        document.getElementById('modalTotal').innerText = formatRupiah(order.total_amount);
        
        let paymentMethod = order.payment ? order.payment.method : '-';
        if(paymentMethod === 'midtrans') paymentMethod = 'Online Payment';
        document.getElementById('modalPayment').innerText = paymentMethod; 
        
        document.getElementById('modalTable').innerText = order.table ? order.table.table_number : '-';
        document.getElementById('modalOrder').innerText = order.table ? 'Dine In' : 'Takeaway';

        const btnNota = document.getElementById('btnUnduhNota');
        btnNota.href = `/order/${order.id}/pdf`; 
        
        if(order.status === 'cancelled') {
            btnNota.classList.add('hidden');
        } else {
            btnNota.classList.remove('hidden');
        }

        // 3. Set Status Badge
        const statusEl = document.getElementById('modalStatus');
        const paymentStatus = order.payment ? order.payment.payment_status : 'pending';
        
        statusEl.className = "px-2 py-1 rounded text-xs font-bold uppercase ";
        
        if(order.status === 'cancelled') {
            statusEl.classList.add('bg-red-100', 'text-red-600');
            statusEl.innerText = 'DIBATALKAN';
        } else if (order.status === 'completed') {
            statusEl.classList.add('bg-green-100', 'text-green-700');
            statusEl.innerText = 'SELESAI';
        } else if (paymentStatus === 'pending') {
            statusEl.classList.add('bg-orange-100', 'text-orange-600');
            statusEl.innerText = 'BELUM BAYAR';
        } else if (paymentStatus === 'expired' || paymentStatus === 'failed') {
            statusEl.classList.add('bg-red-100', 'text-red-600');
            statusEl.innerText = 'KADALUWARSA';
        } else {
            statusEl.classList.add('bg-blue-100', 'text-blue-600');
            statusEl.innerText = 'DIPROSES';
        }

        // 4. Render Items
        const listContainer = document.getElementById('modalItemsList');
        listContainer.innerHTML = ''; 

        items.forEach(item => {
            const menuName = item.menu ? item.menu.name : 'Item dihapus';
            const menuImage = item.menu ? item.menu.image_url : '';
            const html = `
                <div class="flex gap-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0 overflow-hidden">
                        ${menuImage ? `<img src="${menuImage}" alt="${menuName}" class="w-full h-full object-cover"/>` : '🍽️'}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="text-sm font-bold text-gray-700 line-clamp-2">${menuName}</h4>
                            <span class="text-xs font-semibold text-gray-600 whitespace-nowrap ml-2">${formatRupiah(item.menu.price * item.qty)}</span>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-xs text-gray-400">${item.qty}x @ ${formatRupiah(item.menu.price)}</span>
                        </div>
                ${item.note ? `<p class="text-[10px] text-gray-400 italic mt-1">Catatan: ${item.note}</p>` : ''}
                    </div>
                </div>
            `;
            listContainer.innerHTML += html;
        });

        // 5. Show Modal
        const modal = document.getElementById('detailModal');
        const backdrop = document.getElementById('detailBackdrop');
        const panel = document.getElementById('detailPanel');

        modal.classList.remove('hidden');
        void panel.offsetWidth;

        backdrop.classList.remove('opacity-0');
        panel.classList.remove('translate-y-full');
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        const backdrop = document.getElementById('detailBackdrop');
        const panel = document.getElementById('detailPanel');

        backdrop.classList.add('opacity-0');
        panel.classList.add('translate-y-full');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function openFilterModal() {
        const modal = document.getElementById('filterModal');
        const backdrop = document.getElementById('filterBackdrop');
        const panel = document.getElementById('filterPanel');

        modal.classList.remove('hidden');
        // Force reflow
        void panel.offsetWidth;

        backdrop.classList.remove('opacity-0');
        panel.classList.remove('translate-y-full');
    }

    function closeFilterModal() {
        const modal = document.getElementById('filterModal');
        const backdrop = document.getElementById('filterBackdrop');
        const panel = document.getElementById('filterPanel');

        backdrop.classList.add('opacity-0');
        panel.classList.add('translate-y-full');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>

@endsection