@extends('layouts.app')
@section('title', 'Dashboard Master - Grand Santhi')

@section('content')
<div class="space-y-8 pb-10">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#014421]">Overview Bisnis</h1>
            <p class="text-gray-500 text-sm">
                Laporan periode: 
                <span class="font-bold text-gray-800">
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $currentDate)->translatedFormat('F Y') }}
                </span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <form action="{{ route('dashboard') }}" method="GET" id="filterForm" class="flex items-center">
                <div class="relative">
                    <input type="month" 
                           name="date" 
                           value="{{ $currentDate }}" 
                           onchange="document.getElementById('filterForm').submit()"
                           class="pl-10 pr-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#014421] focus:border-transparent cursor-pointer">
                    <i class="ti ti-calendar absolute left-3 top-2.5 text-gray-500 text-lg"></i>
                </div>
            </form>

            <a href="{{ route('admin.dashboard.export', ['date' => $currentDate]) }}" 
               target="_blank"
               class="bg-[#014421] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-900 flex items-center gap-2 shadow-lg shadow-green-900/20 active:scale-95 transition-transform">
                <i class="ti ti-download text-lg"></i> 
                Export CSV
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-[#014421]/30 transition-colors">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-2xl font-extrabold text-[#014421] mt-1">
                        Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-[#014421] group-hover:scale-110 transition-transform">
                    <i class="ti ti-wallet text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-blue-300 transition-colors">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pesanan</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
                        {{ number_format($stats['total_orders']) }} <span class="text-sm text-gray-400 font-medium">trx</span>
                    </h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                    <i class="ti ti-shopping-cart text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-purple-300 transition-colors">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Avg. Transaksi</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
                        Rp {{ number_format($stats['avg_transaction'], 0, ',', '.') }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                    <i class="ti ti-receipt-2 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-orange-300 transition-colors">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pelanggan Unik</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
                        {{ number_format($stats['total_customers']) }} <span class="text-sm text-gray-400 font-medium">orang</span>
                    </h3>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                    <i class="ti ti-users text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs relative z-10">
                <span class="text-gray-400 font-medium">Berdasarkan nama tamu/ID</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Tren Pendapatan Harian</h2>
                    <p class="text-xs text-gray-500 mt-1">Pergerakan omzet selama bulan {{ \Carbon\Carbon::createFromFormat('Y-m', $currentDate)->translatedFormat('F Y') }}</p>
                </div>
            </div>
            
            <div class="p-4 flex-1 w-full bg-white relative">
                <div id="revenueChart" class="w-full h-full min-h-[350px]"></div>
            </div>
        </div>

        <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-[480px]">
            <div class="p-6 border-b border-gray-100 bg-white">
                <h2 class="text-lg font-bold text-gray-800">Transaksi Terkini</h2>
                <p class="text-xs text-gray-500 mt-1">Update secara real-time</p>
            </div>

            <div class="p-4 space-y-3 overflow-y-auto flex-1 custom-scrollbar">
                @forelse($recentTransactions as $tx)
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition-all cursor-pointer">
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-[#014421] flex-shrink-0">
                        <i class="ti ti-check text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <p class="text-sm font-bold text-gray-800 truncate">
                                {{ $tx->order->table->table_number ?? 'TA' }} - {{ $tx->order->guest_name ?? 'Walk-In Customer' }}
                            </p>
                            <span class="text-sm font-extrabold text-[#014421]">
                                +{{ number_format($tx->amount/1000, 0) }}k
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-[11px] text-gray-500 font-medium">Inv: #{{ substr($tx->payment_code, -6) }}</p>
                            <p class="text-[11px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($tx->paid_at)->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center h-full text-center text-gray-500">
                    <i class="ti ti-receipt-off text-4xl mb-2 text-gray-300"></i>
                    <p class="text-sm font-medium">Belum ada transaksi</p>
                </div>
                @endforelse
            </div>

            <div class="p-4 border-t border-gray-100 bg-gray-50 mt-auto">
                <a href="{{ route('cashier.transaction.index') }}" class="block w-full text-center text-sm font-bold text-[#014421] hover:underline flex justify-center items-center gap-1">
                    Lihat Semua Riwayat <i class="ti ti-arrow-right"></i>
                </a>
            </div>
        </div>
        
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    // render chart
    document.addEventListener('DOMContentLoaded', function() {
        
        const dates = @json($chartDates ?? []);
        const revenues = @json($chartRevenues ?? []);

        var options = {
            series: [{
                name: 'Pendapatan (Rp)',
                data: revenues.length > 0 ? revenues : [0,0,0,0,0,0,0] // Data dummy jika kosong
            }],
            chart: {
                type: 'area',
                height: 380,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            colors: ['#014421'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            xaxis: {
                categories: dates.length > 0 ? dates : ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'], // Dummy jika kosong
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#9CA3AF', fontSize: '12px' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#9CA3AF', fontSize: '12px' },
                    formatter: function (value) {
                        if(value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                        if(value >= 1000) return (value / 1000).toFixed(0) + 'K';
                        return value;
                    }
                }
            },
            grid: {
                borderColor: '#F3F4F6',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val) {
                        return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#revenueChart"), options);
        chart.render();
    });
</script>
@endsection