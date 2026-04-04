@extends('layouts.app')
@section('title', 'Laporan Pesanan - Admin Master')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#014421]">Data Transaksi & Audit</h1>
            <p class="text-sm text-gray-500">Pantau seluruh riwayat pesanan, status pembayaran, dan detail item.</p>
        </div>
        
        <a href="{{ route('admin.order.export', request()->all()) }}" target="_blank" 
         class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-50 flex items-center gap-2 shadow-sm active:scale-95 transition-transform">
         <i class="ti ti-file-export text-lg"></i> 
         Export CSV
     </a>
 </div>

 <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
    <form action="{{ route('admin.order.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        
        <div class="md:col-span-4">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Pencarian</label>
            <div class="relative mt-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode Order / Nama Tamu..." 
                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-[#014421] focus:border-[#014421]">
                <i class="ti ti-search absolute left-3 top-2.5 text-gray-400 text-lg"></i>
            </div>
        </div>

        <div class="md:col-span-2">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" 
            class="mt-1 w-full border border-gray-300 rounded-lg text-sm focus:ring-[#014421] focus:border-[#014421]">
        </div>

        <div class="md:col-span-2">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" 
            class="mt-1 w-full border border-gray-300 rounded-lg text-sm focus:ring-[#014421] focus:border-[#014421]">
        </div>

        <div class="md:col-span-2">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Status</label>
            <select name="status" class="mt-1 w-full border border-gray-300 rounded-lg text-sm focus:ring-[#014421] focus:border-[#014421]">
                <option value="all">Semua Status</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <div class="md:col-span-2 flex gap-2">
            <button type="submit" class="w-full !bg-[#014421] text-white px-4 py-2 rounded-lg font-bold hover:bg-green-900 transition-colors shadow-lg shadow-green-900/20">
                Filter
            </button>
            <a href="{{ route('admin.order.index') }}" class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200" title="Reset Filter">
                <i class="ti ti-rotate-clockwise"></i>
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">Waktu Order</th>
                    <th class="px-6 py-4 font-bold">Kode / Pelanggan</th>
                    <th class="px-6 py-4 font-bold text-center">Meja</th>
                    <th class="px-6 py-4 font-bold text-right">Total</th>
                    <th class="px-6 py-4 font-bold text-center">Metode</th>
                    <th class="px-6 py-4 font-bold text-center">Status</th>
                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($orders as $order)
                <tr class="hover:bg-green-50/30 transition-colors group">
                    
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        <span class="font-medium text-gray-800">{{ $order->created_at->format('d M Y') }}</span>
                        <br>
                        <span class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="font-mono font-bold text-[#014421] bg-green-100 px-2 py-0.5 rounded text-xs">{{ $order->order_code }}</span>
                        <br>
                        <span class="text-xs font-semibold text-gray-600 mt-1 block truncate max-w-[150px]">
                            {{ $order->guest_name ?? $order->customer->name ?? 'Pelanggan Umum' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="bg-gray-100 text-gray-700 font-bold px-2.5 py-1 rounded-lg text-xs border border-gray-200">
                            {{ $order->table->table_number ?? 'TA' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-right font-bold text-gray-800">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4 text-center">
                        @php
                        $method = $order->payment ? $order->payment->method : '-';
                        $badgeClass = match($method) {
                            'cash' => 'bg-green-50 text-green-700 border-green-200',
                            'qris' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'transfer' => 'bg-blue-50 text-blue-700 border-blue-200',
                            default => 'bg-gray-50 text-gray-400 border-gray-200'
                        };
                        @endphp
                        <span class="border {{ $badgeClass }} px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide">
                            {{ $method }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">
                        @if($order->status == 'completed')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Selesai
                        </span>
                        @elseif($order->status == 'cancelled')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Batal
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-600"></span> Proses
                        </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-center">
                        <button onclick="openAdminModal({{ json_encode($order) }}, {{ json_encode($order->items) }})" 
                            class="text-gray-400 hover:text-[#014421] hover:bg-green-50 p-2 rounded-lg transition-all group-hover:text-gray-600">
                            <i class="ti ti-eye text-xl"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                <i class="ti ti-database-off text-3xl text-gray-400"></i>
                            </div>
                            <p class="font-medium">Tidak ada data pesanan ditemukan.</p>
                            <p class="text-xs mt-1">Coba ubah filter tanggal atau kata kunci pencarian.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
        <p class="text-xs text-gray-500">
            Menampilkan <span class="font-bold">{{ $orders->firstItem() ?? 0 }}</span> - <span class="font-bold">{{ $orders->lastItem() ?? 0 }}</span> dari <span class="font-bold">{{ $orders->total() }}</span> data
        </p>
        <div>
            {{ $orders->links() }} 
        </div>
    </div>
</div>
</div>

<div id="adminOrderModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300 opacity-0 pointer-events-none" aria-hidden="true">
    
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-all duration-300" id="modalPanel">
        
        <div class="bg-[#014421] px-6 py-4 flex justify-between items-center text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="ti ti-receipt text-6xl"></i>
            </div>
            
            <div class="relative z-10">
                <h3 class="text-lg font-bold">Detail Pesanan & Audit</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="bg-white/20 px-2 py-0.5 rounded text-xs font-mono" id="modalOrderCode">#ORDER-ID</span>
                    <span class="text-xs opacity-80" id="modalStatusBadge">STATUS</span>
                </div>
            </div>
            <button onclick="closeAdminModal()" class="relative z-10 hover:bg-white/20 p-2 rounded-full transition text-white">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>

        <div class="p-6 max-h-[65vh] overflow-y-auto custom-scroll">
            
            <div class="grid grid-cols-2 gap-8 mb-6 border-b border-gray-100 pb-6">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-2">Informasi Pelanggan</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                            <i class="ti ti-user text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-800 font-bold text-base leading-tight" id="modalGuest">Nama Tamu</p>
                            <p class="text-gray-500 text-xs mt-0.5">Meja: <span class="font-bold text-[#014421] bg-green-50 px-1 rounded" id="modalTable">00</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-2">Waktu Transaksi</p>
                    <p class="text-gray-800 font-medium" id="modalDate">DD MMM YYYY</p>
                    <p class="text-gray-500 text-xs font-mono" id="modalTime">HH:MM WIB</p>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-2">Rincian Menu</p>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase">
                            <tr>
                                <th class="px-4 py-2 font-semibold">Menu</th>
                                <th class="px-4 py-2 text-center font-semibold">Qty</th>
                                <th class="px-4 py-2 text-right font-semibold">Harga</th>
                                <th class="px-4 py-2 text-right font-semibold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalItemsBody" class="divide-y divide-gray-100">
                        </tbody>
                        <tfoot class="bg-gray-50/50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-600">Total Akhir</td>
                                <td class="px-4 py-3 text-right text-[#014421] font-extrabold text-lg" id="modalTotal">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex flex-col sm:flex-row justify-between gap-4">
                <div>
                    <h4 class="text-xs font-bold text-gray-800 uppercase mb-2">Log Pembayaran</h4>
                    <div class="space-y-1 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500 w-20">Metode:</span>
                            <span class="font-bold uppercase text-gray-800" id="modalMethod">-</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500 w-20">Status:</span>
                            <span class="font-bold uppercase" id="modalPaymentStatus">-</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                   <h4 class="text-xs font-bold text-gray-800 uppercase mb-2">Waktu Bayar</h4>
                   <p class="font-mono text-gray-600 text-sm" id="modalPaidAt">-</p>
               </div>
           </div>
       </div>

       <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200 gap-2">
        <button onclick="closeAdminModal()" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
            Tutup
        </button>
    </div>
</div>
</div>
@endsection

@section('js')
<script>
    // Format Rupiah
    const fmt = (num) => 'Rp ' + Number(num).toLocaleString('id-ID');

    function openAdminModal(order, items) {
        // 1. Populate Basic Data
        document.getElementById('modalOrderCode').innerText = '#' + order.order_code;
        document.getElementById('modalGuest').innerText = order.guest_name || order.customer?.name || 'Pelanggan Umum';
        document.getElementById('modalTable').innerText = order.table ? order.table.table_number : 'TA';
        document.getElementById('modalStatusBadge').innerText = order.status.toUpperCase();
        
        // Date Time
        const dateObj = new Date(order.created_at);
        document.getElementById('modalDate').innerText = dateObj.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        document.getElementById('modalTime').innerText = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';

        document.getElementById('modalTotal').innerText = fmt(order.total_amount);

        // 2. Payment Data Logic
        const pMethod = document.getElementById('modalMethod');
        const pStatus = document.getElementById('modalPaymentStatus');
        const pTime = document.getElementById('modalPaidAt');

        if (order.payment) {
            pMethod.innerText = order.payment.method;
            pStatus.innerText = order.payment.payment_status;
            pStatus.className = order.payment.payment_status === 'success' ? 'font-bold uppercase text-green-600' : 'font-bold uppercase text-orange-600';
            
            pTime.innerText = order.payment.paid_at 
            ? new Date(order.payment.paid_at).toLocaleString('id-ID') 
            : 'Belum dibayar';
        } else {
            pMethod.innerText = '-';
            pStatus.innerText = 'Unpaid';
            pStatus.className = 'font-bold uppercase text-red-600';
            pTime.innerText = '-';
        }

        // 3. Render Items
        const tbody = document.getElementById('modalItemsBody');
        tbody.innerHTML = '';
        
        items.forEach(item => {
            const menuName = item.menu ? item.menu.name : '(Menu Telah Dihapus)';
            const subtotal = item.qty * item.price;
            
            const row = `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <span class="font-medium text-gray-700">${menuName}</span>
                ${item.note ? `<div class="text-[10px] text-gray-500 bg-gray-100 inline-block px-1.5 rounded mt-1">📝 ${item.note}</div>` : ''}
                    </td>
                    <td class="px-4 py-3 text-center text-gray-600 font-mono text-xs">${item.qty}x</td>
                    <td class="px-4 py-3 text-right text-gray-500 text-xs">${fmt(item.price)}</td>
                    <td class="px-4 py-3 text-right font-bold text-gray-800 text-sm">${fmt(subtotal)}</td>
                </tr>
            `;
            tbody.innerHTML += row;
        });

        // 4. Show Modal Logic (Animation)
        const modal = document.getElementById('adminOrderModal');
        const panel = document.getElementById('modalPanel');

        modal.classList.remove('hidden');
        modal.classList.remove('pointer-events-none');
        
        // Timeout minimal agar browser merender class 'hidden' remove dulu sebelum transisi opacity
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            panel.classList.remove('scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function closeAdminModal() {
        const modal = document.getElementById('adminOrderModal');
        const panel = document.getElementById('modalPanel');

        modal.classList.add('opacity-0');
        panel.classList.remove('scale-100');
        panel.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.add('pointer-events-none');
        }, 300); // Sesuaikan dengan duration-300
    }

    // Close on click outside
    document.getElementById('adminOrderModal').addEventListener('click', (e) => {
        if (e.target.id === 'adminOrderModal') closeAdminModal();
    });
</script>
@endsection