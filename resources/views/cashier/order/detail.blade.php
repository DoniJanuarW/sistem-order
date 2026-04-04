@extends('layouts.app')
@section('title', 'Grand Santhi Coffee Shop - Detail Pesanan')

@section('css')
@vite(['resources/js/cashier/form-update.js'])
@endsection

@section('content')
<div class="container mx-auto px-4 py-6 max-w-5xl">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Detail Pesanan</h1>
            <p class="text-sm text-gray-500">{{ $order->order_code }} • {{ $order->created_at->format('d M Y H:i') }}</p>
        </div>
        
        <a href="{{ route('cashier.order.pdf', $order->id) }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-50 hover:text-[#014421] transition-colors shadow-sm active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Unduh Nota PDF
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6 md:gap-4 items-start">
            
            <div>
                <p class="text-[10px] text-gray-400 mb-1 uppercase tracking-wider font-bold">Customer</p>
                <p class="font-bold text-gray-900 text-sm">{{ $order->customer_id == null ? ($order->guest_name ?? 'Guest') : $order->customer->name }}</p>
            </div>

            <div>
                <p class="text-[10px] text-gray-400 mb-1 uppercase tracking-wider font-bold">Meja</p>
                <p class="font-bold text-gray-900 text-sm">{{$order->table->table_number  ??  "-" }}</p>
            </div>

            <div>
                <p class="text-[10px] text-gray-400 mb-1 uppercase tracking-wider font-bold">Pesanan</p>
                <p class="font-bold text-gray-900 text-sm">{{ $order->table_id != null ? "Dine In" : "Takeaway" }}</p>
            </div>
            
            <div>
                <p class="text-[10px] text-gray-400 mb-1 uppercase tracking-wider font-bold">Status Pesanan</p>
                @php
                    $osColor = match($order->status) {
                        'pending'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        'cooking', 'process' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'completed' => 'bg-green-100 text-green-700 border-green-200',
                        'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                        default     => 'bg-gray-100 text-gray-700 border-gray-200'
                    };
                @endphp
                <span class="inline-block px-3 py-1 text-[10px] rounded-full font-bold whitespace-nowrap border {{ $osColor }}">
                    {{ strtoupper($order->status) }}
                </span>
            </div>

            <div>
                <p class="text-[10px] text-gray-400 mb-1 uppercase tracking-wider font-bold">Metode Bayar</p>
                <span class="inline-block px-3 py-1 text-[10px] rounded-full font-bold whitespace-nowrap bg-gray-100 text-gray-700 border border-gray-200">
                    {{ strtoupper($order->payment->method == 'midtrans' ? 'Online (Midtrans)' : ($order->payment->method ?? '-')) }}
                </span>
            </div>

            <div>
                <p class="text-[10px] text-gray-400 mb-1 uppercase tracking-wider font-bold">Status Bayar</p>
                @php
                    $ps = $order->payment->payment_status ?? 'pending';
                    $psColor = match($ps) {
                        'success' => 'bg-green-100 text-green-700 border-green-200',
                        'pending' => 'bg-orange-100 text-orange-700 border-orange-200',
                        'failed', 'expired' => 'bg-red-100 text-red-700 border-red-200',
                        default => 'bg-gray-100 text-gray-700 border-gray-200'
                    };
                    $psLabel = match($ps) {
                        'success' => 'LUNAS',
                        'pending' => 'BELUM BAYAR',
                        'failed'  => 'GAGAL',
                        'expired' => 'KADALUWARSA',
                        default   => 'UNKNOWN'
                    };
                @endphp
                <span class="inline-block px-3 py-1 text-[10px] rounded-full font-bold whitespace-nowrap border {{ $psColor }}">
                    {{ $psLabel }}
                </span>
            </div>

        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
        <h3 class="font-bold text-lg mb-4 text-gray-900 border-b pb-3">Daftar Menu</h3>

        <div class="space-y-4">
            @foreach($order->items as $item)
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold text-gray-900 text-sm">{{ $item->menu->name ?? 'Menu Dihapus' }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">
                        {{ $item->qty }}x @ Rp {{ number_format($item->menu->price, 0, ',', '.') }}
                    </p>
                    @if($item->note)
                    <p class="text-xs text-orange-600 italic mt-1 bg-orange-50 inline-block px-2 py-0.5 rounded">Catatan: {{ $item->note }}</p>
                    @endif
                </div>
                <p class="font-bold text-[#014421]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>

        <div class="flex justify-between items-center font-bold text-lg pt-4 border-t mt-5">
            <span class="text-gray-900">Total Tagihan</span>
            <span class="text-[#014421] text-xl">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        
        @if($order->status == 'cancelled')
        <div class="bg-red-50 border border-red-100 rounded-xl p-6 flex flex-col items-center justify-center text-center">
            <div class="w-14 h-14 bg-red-500 rounded-full flex items-center justify-center shadow-md mb-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-red-800 mb-1">Pesanan Dibatalkan</h3>
            <p class="text-xs font-medium text-red-600">Dibatalkan pada: {{ $order->updated_at->format('d/m/Y H:i') }}</p>
        </div>
        @elseif($order->status != 'completed')
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <h3 class="font-bold mb-4 text-gray-900">Update Status Pesanan</h3>

            <form action="{{ route('cashier.order.update-status', $order->id) }}" method="POST" class="form-ajax">
                @csrf
                @method('PATCH')

                <label for="orderStatus" class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Status</label>
                <select id="orderStatus" name="status" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach(['pending' => 'Pending (Menunggu)', 'cooking' => 'Cooking (Dimasak)', 'completed' => 'Completed (Selesai)', 'cancelled' => 'Cancelled (Batal)'] as $val => $label)
                    <option value="{{ $val }}" @selected($order->status == $val)>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>

                <button type="submit" class="btn-submit w-full mt-4 bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition-colors font-bold flex justify-center items-center gap-2">
                    <span>Simpan Status Pesanan</span>
                </button>
            </form>
        </div>
        @else
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 flex flex-col items-center justify-center text-center">
            <div class="w-14 h-14 bg-blue-500 rounded-full flex items-center justify-center shadow-md mb-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-blue-800 mb-1">Pesanan Selesai</h3>
            <p class="text-xs font-medium text-blue-600">Diselesaikan pada: {{ $order->updated_at->format('d/m/Y H:i') }}</p>
        </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <h3 class="font-bold mb-4 text-gray-900">Update Pembayaran</h3>

            @if($order->status == 'cancelled')
            <div class="bg-red-50 border border-red-100 rounded-xl p-6 flex flex-col items-center justify-center text-center">
                <div class="w-14 h-14 bg-red-500 rounded-full flex items-center justify-center shadow-md mb-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-red-800 mb-1">Pesanan Dibatalkan</h3>
                <p class="text-xs font-medium text-red-600">Tidak dapat memperbarui pembayaran untuk pesanan yang dibatalkan.</p>
            @elseif($order->payment->payment_status != 'success')
                
                @if($order->payment->method == 'midtrans')
                <div class="mb-4 p-4 bg-orange-50 border border-orange-100 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs text-orange-800 leading-relaxed font-medium">
                        Pembayaran ini menggunakan <strong>Midtrans</strong>. Status akan otomatis berubah menjadi "LUNAS" ketika pelanggan selesai membayar secara online. <br>
                        <em>Gunakan form di bawah hanya jika Anda menerima pembayaran tunai (Override).</em>
                    </p>
                </div>
                @endif

                <form action="{{ route('cashier.payment.update-payment', $order->payment->id) }}" method="POST" class="form-ajax">
                    @csrf
                    @method('PATCH')

                    <label for="paymentStatus" class="block text-xs font-bold text-gray-500 uppercase mb-2">Konfirmasi Pembayaran</label>
                    <select id="paymentStatus" name="payment_status" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#014421] focus:border-transparent">
                        <option value="pending" @selected($order->payment->payment_status == 'pending')>Belum Lunas (Unpaid)</option>
                        <option value="success" @selected($order->payment->payment_status == 'success')>Sudah Lunas (Paid)</option>
                    </select>

                    <button type="submit" class="btn-submit w-full mt-4 !bg-[#014421] text-white py-3 rounded-xl hover:bg-green-900 transition-colors font-bold flex justify-center items-center gap-2 shadow-lg shadow-green-900/20">
                        <span>Verifikasi Pembayaran</span>
                    </button>
                </form>

            @else
                <div class="bg-green-50 border border-green-100 rounded-xl flex flex-col items-center justify-center text-center h-50 p-6">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center shadow-md mb-3">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-green-800 mb-1">Pembayaran Lunas</h3>
                    <p class="text-xs font-medium text-green-600">Dibayar pada: {{ $order->payment->paid_at ? $order->payment->paid_at->format('d/m/Y H:i') : '-' }}</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

@section('js')
@endsection