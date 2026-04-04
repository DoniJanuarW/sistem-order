@extends('layouts.app')
@section('title', 'Grand Santhi Coffee Shop - Manajemen Pesanan')

@section('css')
<style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(10px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
    
    @keyframes pulse {
        0%, 100% { 
            opacity: 1; 
        }
        50% { 
            opacity: 0.5; 
        }
    }
    
    .fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    .status-badge {
        transition: all 0.2s ease;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
    
    .skeleton {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Manajemen Pesanan</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau dan kelola seluruh pesanan aktif</p>
        </div>
        <button id="refreshBtn"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        Refresh
    </button>
</div>

<!-- Filter Tabs -->
<div class="bg-white border border-gray-100 rounded-xl shadow-sm p-4">
    <div class="flex flex-wrap gap-2">
        @foreach ([
            'all' => ['label' => 'Semua', 'icon' => 'M4 6h16M4 12h16M4 18h16'],
            'pending' => ['label' => 'Pending', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            'cooking' => ['label' => 'Cooking', 'icon' => 'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z'],
            'completed' => ['label' => 'Completed', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            'cancelled' => ['label' => 'Cancelled', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z']
            ] as $key => $item)
            <button 
            data-status="{{ $key }}"
            class="filter-btn px-4 py-2 rounded-lg text-sm font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all duration-200 flex items-center gap-2"
            aria-label="Filter {{ $item['label'] }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
            </svg>
            <span>{{ $item['label'] }}</span>
            <span class="count-badge hidden ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">0</span>
        </button>
        @endforeach
    </div>
</div>

<!-- Stats Summary -->
<div id="statsContainer" class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    <!-- Will be populated by JS -->
</div>

<!-- Loading State -->
<div id="loadingState" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @for ($i = 0; $i < 8; $i++)
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 space-y-4">
        <div class="h-5 bg-gray-200 rounded w-2/3 skeleton"></div>
        <div class="h-4 bg-gray-200 rounded w-1/2 skeleton"></div>
        <div class="space-y-2">
            <div class="h-3 bg-gray-200 rounded skeleton"></div>
            <div class="h-3 bg-gray-200 rounded skeleton"></div>
            <div class="h-3 bg-gray-200 rounded skeleton"></div>
        </div>
        <div class="h-10 bg-gray-200 rounded skeleton"></div>
    </div>
    @endfor
</div>

<!-- Orders Grid -->
<div id="ordersContainer" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    <!-- Will be populated by JS -->
</div>

<!-- Empty State -->
<div id="emptyState" class="hidden text-center py-16">
    <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
    </svg>
    <p class="text-gray-600 text-lg font-medium">Tidak ada pesanan</p>
    <p class="text-gray-500 text-sm mt-2">Pesanan akan muncul di sini setelah dibuat</p>
</div>

<!-- Error State -->
<div id="errorState" class="hidden text-center py-16">
    <svg class="w-24 h-24 mx-auto text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="text-gray-600 text-lg font-medium">Gagal memuat data</p>
    <p class="text-gray-500 text-sm mt-2 mb-4">Terjadi kesalahan saat mengambil data pesanan</p>
    <button onclick="location.reload()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
        Coba Lagi
    </button>
</div>
</div>
@endsection

@section('js')
<script>
    (function() {
        'use strict';

        const CONFIG = {
            api: {
                list: "{{ route('cashier.order.all') }}"
            },
            animationDelay: 50
        };
        const detailRouteTemplate = "{{ route('cashier.order.detail', ':id') }}";
        const state = {
            orders: [],
            filter: 'all',
            loading: false,
            error: false
        };

        // DOM elements cache
        const elements = {
            container: document.getElementById('ordersContainer'),
            empty: document.getElementById('emptyState'),
            error: document.getElementById('errorState'),
            filters: document.querySelectorAll('.filter-btn'),
            loading: document.getElementById('loadingState'),
            stats: document.getElementById('statsContainer'),
            refreshBtn: document.getElementById('refreshBtn') // Element baru
        };

        // Initialize application
        document.addEventListener('DOMContentLoaded', initialize);

        async function initialize() {
            try {
                bindEventListeners();
                await loadOrders(true); 
            } catch (error) {
                console.error('Initialization error:', error);
                showError();
            }
        }

        // Bind all event listeners
        function bindEventListeners() {
            elements.filters.forEach(btn => {
                btn.addEventListener('click', handleFilterClick);
            });

            if (elements.refreshBtn) {
                elements.refreshBtn.addEventListener('click', handleManualRefresh);
            }
        }

        // Handle Manual Refresh Click
        async function handleManualRefresh() {
            if (state.loading) return;

            const btn = elements.refreshBtn;
            const icon = btn.querySelector('svg');
            
            btn.disabled = true;
            icon.classList.add('animate-spin');

            await loadOrders(false);

            setTimeout(() => {
                btn.disabled = false;
                icon.classList.remove('animate-spin');
            }, 500);
        }

        function handleFilterClick(e) {
            const status = e.currentTarget.dataset.status;
            setFilter(status);
        }

        function setFilter(status) {
            state.filter = status;

            elements.filters.forEach(btn => {
                const isActive = btn.dataset.status === status;
                btn.classList.toggle('bg-blue-600', isActive);
                btn.classList.toggle('text-white', isActive);
                btn.classList.toggle('border-blue-600', isActive);
                btn.classList.toggle('text-gray-600', !isActive);
                btn.classList.toggle('hover:bg-gray-50', !isActive);
                btn.classList.toggle('hover:bg-blue-700', isActive);
            });

            render();
        }

        function setLoading(isLoading, showSkeleton = true) {
            state.loading = isLoading;
            
            if (showSkeleton) {
                elements.loading.classList.toggle('hidden', !isLoading);
                if (isLoading) {
                    elements.empty.classList.add('hidden');
                    elements.error.classList.add('hidden');
                    elements.container.classList.add('hidden');
                } else {
                    elements.container.classList.remove('hidden');
                }
            }
        }

        function showError() {
            state.error = true;
            elements.loading.classList.add('hidden');
            elements.container.classList.add('hidden');
            elements.empty.classList.add('hidden');
            elements.error.classList.remove('hidden');
        }

        async function loadOrders(isFullReload = false) {
            if (state.loading) return;

            setLoading(true, isFullReload);
            state.error = false;

            try {
                const response = await fetch(CONFIG.api.list, {
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const data = await response.json();
                state.orders = Array.isArray(data) ? data : [];

            } catch (error) {
                console.error('Load orders error:', error);
                if (isFullReload) {
                    state.orders = [];
                    showError();
                } else {
                    alert('Gagal memperbarui data. Cek koneksi internet.');
                }
            } finally {
                setLoading(false, isFullReload);
                if (!state.error) {
                    updateStats();
                    setFilter(state.filter);
                }
            }
        }

        function updateStats() {
            const stats = {
                all: state.orders.length,
                pending: state.orders.filter(o => o.status === 'pending').length,
                cooking: state.orders.filter(o => o.status === 'cooking').length,
                completed: state.orders.filter(o => o.status === 'completed').length,
                cancelled: state.orders.filter(o => o.status === 'cancelled').length
            };

            elements.filters.forEach(btn => {
                const status = btn.dataset.status;
                const badge = btn.querySelector('.count-badge');
                const count = stats[status] || 0;

                if (badge) {
                    badge.textContent = count;
                    badge.classList.toggle('hidden', count === 0);
                }
            });

            const totalRevenue = state.orders
            .filter(o => o.status === 'completed')
            .reduce((sum, o) => sum + Number(o.total_amount || 0), 0);

            if (elements.stats) {
                elements.stats.innerHTML = `
                    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-4">
                        <p class="text-xs text-gray-500 mb-1">Total Pesanan</p>
                        <p class="text-2xl font-bold text-gray-900">${stats.all}</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-100 rounded-xl shadow-sm p-4">
                        <p class="text-xs text-blue-600 mb-1">Sedang Diproses</p>
                        <p class="text-2xl font-bold text-blue-700">${stats.pending + stats.cooking}</p>
                    </div>
                    <div class="bg-green-50 border border-green-100 rounded-xl shadow-sm p-4">
                        <p class="text-xs text-green-600 mb-1">Selesai</p>
                        <p class="text-2xl font-bold text-green-700">${stats.completed}</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-100 rounded-xl shadow-sm p-4">
                        <p class="text-xs text-gray-500 mb-1">Pendapatan</p>
                        <p class="text-xl font-bold text-gray-900">Rp ${formatCurrency(totalRevenue)}</p>
                    </div>
                `;
            }
        }

        function render() {
            if (state.loading && elements.container.classList.contains('hidden')) return;

            elements.container.classList.remove('hidden');

            let filteredOrders;

            if (state.filter === 'all') {
                filteredOrders = state.orders.filter(o => o.status !== 'completed');
            } else {
                filteredOrders = state.orders.filter(o => o.status === state.filter);
            }

            if (!filteredOrders.length) {
                elements.container.innerHTML = '';
                elements.empty.classList.remove('hidden');
                return;
            }

            elements.empty.classList.add('hidden');

            elements.container.innerHTML = filteredOrders
            .map((order, index) => createOrderCard(order, index))
            .join('');

            animateCards();
        }

        function animateCards() {
            const cards = elements.container.querySelectorAll('.order-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('fade-in');
                }, index * CONFIG.animationDelay);
            });
        }

        function createOrderCard(order, index) {
            const statusConfig = {
                pending: { class: 'bg-yellow-100 text-yellow-700', label: 'PENDING' },
                cooking: { class: 'bg-blue-100 text-blue-700', label: 'COOKING' },
                completed: { class: 'bg-green-100 text-green-700', label: 'COMPLETED' },
                cancelled: { class: 'bg-red-100 text-red-700', label: 'CANCELLED' }
            };

            const status = statusConfig[order.status] || statusConfig.pending;
            const orderCode = escapeHtml(order.order_code || '-');
            const tableNumber = escapeHtml(order.table?.table_number || '-');
            const customerName = escapeHtml(order.guest_name ?? order.customer.name);
            const itemCount = Array.isArray(order.items) ? order.items.length : 0;
            const totalAmount = formatCurrency(order.total_amount || 0);
            const orderDate = order.created_at ? formatDateTime(order.created_at) : '';
            let finalUrl = detailRouteTemplate.replace(':id', order.id);
            return `
                <div class="order-card bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300" 
                     style="opacity: 0" data-order-id="${order.id}">
                    <div class="p-5 space-y-4">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-lg truncate" title="${orderCode}">
                                    ${orderCode}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500">Meja ${tableNumber}</p>
                                </div>
                ${orderDate ? `<p class="text-xs text-gray-400 mt-1">${orderDate}</p>` : ''}
                            </div>
                            <span class="status-badge px-3 py-1.5 text-xs font-semibold rounded-full whitespace-nowrap ${status.class}">
                                ${status.label}
                            </span>
                        </div>

                        <div class="space-y-2 pt-3 border-t border-gray-100">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Pelanggan:</span>
                                <span class="font-medium text-gray-900 truncate ml-2" title="${customerName}">
                                    ${customerName}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Jumlah Item:</span>
                                <span class="font-medium text-gray-900">
                                    ${itemCount} item${itemCount !== 1 ? 's' : ''}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-100">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-bold text-blue-600 text-base">
                                    Rp ${totalAmount}
                                </span>
                            </div>
                        </div>

                        <a href="${finalUrl}"
                           class="block text-center bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            `;
        }

        function escapeHtml(text) {
            if (typeof text !== 'string') return text;
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        function formatCurrency(amount) {
            const num = Number(amount) || 0;
            return num.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        }

        function formatDateTime(dateString) {
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit', month: 'short', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });
            } catch (error) { return ''; }
        }

    })();
</script>
@endsection