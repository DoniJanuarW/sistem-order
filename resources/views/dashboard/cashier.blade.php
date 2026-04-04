@extends('layouts.app')
@section('title', 'Dashboard Kasir - Grand Santhi')

@section('content')
<div class="space-y-6 pb-10">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#014421]">Halo, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-gray-500 text-sm">Siap melayani pelanggan hari ini?</p>
        </div>
        <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100">
            <i class="ti ti-calendar text-gray-500"></i>
            <span class="text-sm font-medium text-gray-700">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs font-bold text-orange-500 uppercase tracking-wider">Menunggu Bayar</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $stats['unpaid_orders'] }}</h3>
                <p class="text-[10px] text-gray-400 mt-1">Pesanan aktif hari ini</p>
            </div>
            <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                <i class="ti ti-clock-dollar text-2xl"></i>
            </div>
        </div>

        {{-- <div class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs font-bold text-blue-500 uppercase tracking-wider">Meja Terisi</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $stats['active_tables'] }}</h3>
                <p class="text-[10px] text-gray-400 mt-1">Sedang makan</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                <i class="ti ti-armchair text-2xl"></i>
            </div>
        </div> --}}

        <div class="bg-white p-5 rounded-2xl border border-green-100 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs font-bold text-green-600 uppercase tracking-wider">Omset Hari Ini</p>
                <h3 class="text-2xl font-extrabold text-[#014421] mt-1">Rp {{ number_format($stats['today_revenue']/1000, 0, ',', '.') }}k</h3>
                <p class="text-[10px] text-gray-400 mt-1">Total masuk (Cash/Non-Cash)</p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-[#014421] group-hover:scale-110 transition-transform">
                <i class="ti ti-cash text-2xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pesanan Selesai</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $stats['completed_today'] }}</h3>
                <p class="text-[10px] text-gray-400 mt-1">Transaksi sukses</p>
            </div>
            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 group-hover:scale-110 transition-transform">
                <i class="ti ti-checklist text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">💸 Perlu Konfirmasi Pembayaran</h2>
                <a href="{{ route('cashier.order.index') }}" class="text-sm text-[#014421] font-semibold hover:underline">Lihat Semua</a>
            </div>

            @forelse($recentOrders as $order)
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-sm">
                        {{ $order->table_id != null ? "Meja ".$order->table->table_number : 'TA' }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-gray-800">{{ $order->customer_name ?? 'Pelanggan Umum' }}</h3>
                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded">{{ $order->order_code }}</span>
                        </div>
                        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                            <span>{{ $order->items->count() }} Item</span>
                            <span>•</span>
                            <span class="font-bold text-[#014421]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if($order->payment->transfer_proof)
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full animate-pulse">
                            Cek Bukti TF
                        </span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-full">
                            Belum Bayar
                        </span>
                    @endif

                    {{-- <a href="{{ route('cashier.payment.confirm', $order->id) }}" class="bg-[#014421] hover:bg-green-900 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg shadow-green-900/20 transition-all">
                        Proses
                    </a> --}}
                </div>
            </div>
            @empty
            <div class="bg-white p-8 rounded-xl border border-dashed border-gray-200 text-center">
                <div class="w-16 h-16 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="ti ti-check text-3xl"></i>
                </div>
                <h3 class="text-gray-800 font-bold">Semua Aman!</h3>
                <p class="text-gray-500 text-sm">Tidak ada pesanan yang menunggu pembayaran saat ini.</p>
            </div>
            @endforelse
            
            <div class="grid grid-cols-2 gap-4 mt-4">
                <a href="{{ route('cashier.order.create') }}" class="bg-[#014421] text-white p-4 rounded-xl shadow-lg shadow-green-900/20 hover:shadow-xl transition-all flex items-center justify-between group">
                    <div>
                        <p class="font-bold text-lg">Buat Pesanan Baru</p>
                        <p class="text-green-200 text-xs">Input order manual (POS)</p>
                    </div>
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ti ti-plus text-xl"></i>
                    </div>
                </a>
                
                <a href="{{ route('cashier.transaction.index') }}" class="bg-white border border-gray-200 text-gray-700 p-4 rounded-xl hover:bg-gray-50 transition-all flex items-center justify-between group">
                    <div>
                        <p class="font-bold text-lg">Riwayat Transaksi</p>
                        <p class="text-gray-400 text-xs">Cek laporan hari ini</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 group-hover:scale-110 transition-transform">
                        <i class="ti ti-history text-xl"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection