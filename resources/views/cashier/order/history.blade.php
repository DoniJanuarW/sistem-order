@extends('layouts.app')
@section('title', 'Grand Santhi Coffee Shop - Riwayat Transaksi')
@section('css')
<style>
    .transaction-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .transaction-card {
        transition: all 0.2s ease;
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl px-4 py-1 space-y-8">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Riwayat Transaksi</h1>
            <p class="text-sm text-gray-500">Rekap semua transaksi yang telah selesai</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div>
                <label class="text-sm text-gray-600">Tanggal Mulai</label>
                <input type="date" id="startDate"
                class="mt-1 w-full rounded-lg border-gray-200 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label class="text-sm text-gray-600">Tanggal Akhir</label>
                <input type="date" id="endDate"
                class="mt-1 w-full rounded-lg border-gray-200 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label class="text-sm text-gray-600">Metode Pembayaran</label>
                <select id="paymentFilter"
                class="mt-1 w-full rounded-lg border-gray-200 focus:ring-purple-500 focus:border-purple-500">
                <option value="">Semua Metode</option>
                <option value="cash">Cash</option>
                <option value="qris">QRIS</option>
                <option value="transfer">Transfer Bank</option>
            </select>
        </div>

        <div class="flex items-end">
            <button onclick="applyFilter()"class="w-full rounded-lg bg-purple-600 hover:bg-purple-700 text-white py-2.5 font-medium transition">
                Terapkan
            </button>
        </div>
    </div>
</div>

<!-- Summary -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <x-stat-card id="statTotal" title="Total Transaksi" value="0" />
    <x-stat-card id="statIncome" title="Pendapatan" value="Rp 0" textColor="text-green-600" />
    <x-stat-card id="statAvg" title="Rata-rata" value="Rp 0" textColor="text-blue-600" />
    <x-stat-card id="statCancelled" title="Dibatalkan" value="0" textColor="text-red-600" />
</div>

<!-- Transactions -->
<div class="bg-white border border-gray-100 rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex justify-between">
        <h2 class="text-lg font-semibold text-gray-900">Daftar Transaksi</h2>
        <button class=" rounded-lg bg-green-600 hover:bg-green-700 text-white py-2.5 px-4 font-medium transition">
            Export 
        </button>
    </div>
    <div id="loadingState" class="hidden py-10 text-center">
        <div class="inline-flex items-center gap-2 text-purple-600">
            <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <span class="font-medium">Memuat data...</span>
        </div>
    </div>
    <div id="transactionList" class="p-6 space-y-3"></div>
    <div id="pagination" class="flex justify-between items-center px-6 pb-6 text-sm text-gray-600"></div>
</div>

</div>
@endsection


@section('js')
<script>
    (() => {
        const API = { filter: "{{ route('cashier.payment.filter') }}" };

        let transactions = [];
        let meta = {};
        let currentParams = {};
        let isLoading = false;

        document.addEventListener('DOMContentLoaded', () => fetchTransactions());

        async function fetchTransactions(params = {}, page = 1) {
            if (isLoading) return;

            isLoading = true;
            setLoading(true);

            currentParams = params;

            try {
                const query = new URLSearchParams({ ...params, page }).toString();
                const res = await fetch(`${API.filter}?${query}`, { headers: { Accept: 'application/json' } });
                const result = await res.json();

                transactions = result.data ?? [];
                meta = result;
                if (result.stats) {
                    updateStats(result.stats);
                }

                renderTransactions();
                renderPagination();
            } catch (e) {
                console.error(e);
                alert('Gagal memuat data');
            } finally {
                isLoading = false;
                setLoading(false);
            }
        }

        function updateStats(stats) {
            const fmt = (num) => 'Rp ' + Number(num).toLocaleString('id-ID');
            console.log(stats)

            document.getElementById('statTotal').innerText = stats.total_count;
            document.getElementById('statIncome').innerText = fmt(stats.total_income);
            document.getElementById('statAvg').innerText = fmt(stats.average_income);
            document.getElementById('statCancelled').innerText = stats.total_cancelled;

            animateValue("statTotal", 0, stats.total_count, 500);
        }

        function animateValue(id, start, end, duration) {
            if (start === end) return;
            const obj = document.getElementById(id);
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerHTML = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        function renderTransactions() {
            const el = document.getElementById('transactionList');
            if (!transactions.length) return el.innerHTML = `<p class="text-center text-gray-500">Tidak ada transaksi</p>`;
            el.innerHTML = transactions.map(createTransactionCard).join('');
        }

        function renderPagination() {
            const p = document.getElementById('pagination');
            if (meta.last_page <= 1) return p.innerHTML = '';

            p.innerHTML = `
            <div>Menampilkan ${meta.from}–${meta.to} dari ${meta.total}</div>
            <div class="flex gap-2">
            <button ${meta.current_page == 1 ? 'disabled' : ''} 
            onclick="goPage(${meta.current_page - 1})"
            class="px-3 py-1 border rounded hover:bg-gray-100">‹</button>
            <span class="px-3 py-1 border rounded bg-purple-600 text-white">${meta.current_page}</span>
            <button ${meta.current_page == meta.last_page ? 'disabled' : ''} 
            onclick="goPage(${meta.current_page + 1})"
            class="px-3 py-1 border rounded hover:bg-gray-100">›</button>
            </div>
            `;
        }

        function setLoading(state) {
            document.getElementById('loadingState').classList.toggle('hidden', !state);
            document.getElementById('transactionList').classList.toggle('hidden', state);
        }

        window.goPage = page => fetchTransactions(currentParams, page);

        window.applyFilter = () => {
            const from = startDate.value;
            const to = endDate.value;
            const method = paymentFilter.value;
            fetchTransactions({ from, to, method });
        };

        function createTransactionCard(tx) {
            let methodIcon = 'ti-credit-card';
            let badgeColor = 'bg-blue-50 text-blue-600 border-blue-100';

            if (tx.method === 'cash') {
                methodIcon = 'ti-cash';
                badgeColor = 'bg-green-50 text-green-600 border-green-100';
            } else if (tx.method === 'qris') {
                methodIcon = 'ti-qrcode';
                badgeColor = 'bg-purple-50 text-purple-600 border-purple-100';
            }
            
            return `
            <div class="transaction-card group bg-white border border-gray-100 rounded-xl p-4 cursor-pointer transition-all duration-200 hover:shadow-lg hover:border-green-500/30 relative overflow-hidden">

            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-transparent to-gray-50 opacity-0 group-hover:opacity-100 transition-opacity"></div>

            <div class="flex items-center gap-4 relative z-10">

            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 ${badgeColor} bg-opacity-50">
            <i class="ti ${methodIcon} text-xl"></i>
            </div>

            <div class="flex-1 min-w-0">
            <div class="flex justify-between items-start">
            <div>
            <h3 class="font-bold text-gray-800 text-md truncate">
            ${tx.order.customer_id != null ? 'Customer' : 'Cashier'}
        </h3>
        <p class="text-sm text-gray-400 font-mono mt-0.5 tracking-wide">
        #${tx.payment_code}
        </p>
    </div>

    <div class="text-right">
    <p class="font-bold text-[#014421] text-base">
    Rp ${Number(tx.amount).toLocaleString('id-ID')}
    </p>
</div>
</div>

<div class="flex items-center justify-between mt-2">
<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-sm font-bold uppercase border ${badgeColor}">
<i class="ti ${methodIcon} text-sm"></i>
                ${tx.method}
</span>

<span class="text-sm text-gray-400">
                ${tx.paid_at 
                    ? new Date(tx.paid_at).toLocaleString('id-ID', { 
                        day: 'numeric', 
                        month: 'short', 
                        year: 'numeric', 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    }) 
                    : '-'}
</span>
</div>
</div>
</div>
</div>
                `;
            }

        })();
    </script>

    @endsection