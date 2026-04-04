@extends('layouts.app')

@section('title', 'Grand Santhi Coffee Shop - Table')

@section('css')
<x-datatable-style/>
@endsection

@section('content')
<x-card-breadcrumb title="Table Management" :isCreate="true" createUrl="admin.table.create" />
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.table.pdf') }}" 
       target="_blank" 
       class="bg-[#014421] text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-green-900 inline-flex items-center gap-2 shadow-lg transition-transform active:scale-95">
        
        <i class="ti ti-printer text-lg"></i>
        
        <span>Cetak Semua QR Code (PDF)</span>
    </a>
</div>
<!-- DataTable -->
<x-datatable id="datatables">
	<thead>
		<tr>
			<th class="w-16">No</th>
			<th>Table Number</th>
			<th>Status</th>
			<th class="w-32 text-center">Action</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</x-datatable>


<div id="detailModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
	<div class="bg-white rounded-xl w-full max-w-md p-6">
		<h2 class="text-lg font-semibold mb-4">Detail Meja</h2>

		<div id="modalContent" class="space-y-2 text-sm">
			Loading...
		</div>

		<div class="mt-4 text-right">
			<button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-200 rounded-lg">
				Tutup
			</button>
		</div>
	</div>
</div>

@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets/js/action/crud-manager.js') }}"></script>

<script>
	$(document).ready(function() {
		window.tableManager = new CrudManager({
			entity: 'table',
			modalId: 'create-modal',
			formId: 'table-form',
			apiUrl: '/admin/table',
			dataTableUrl: '{{ route('admin.table.tableTable') }}',
			formFields: ['table_number', 'status'],
			columns: [
				{ 
					data: 'no', 
					name: 'no',
					className: 'text-center font-bold text-gray-900',
					width: '5%'
				},
				{ 
					data: 'table_number', 
					name: 'table_number',
					render: function(data) {
						return `
              <div class="flex items-center gap-3">
                <span class="font-semibold text-gray-900">${data}</span>
              </div>
						`;
					}
				},
				{ 
					data: 'status', 
					name: 'status',
					orderable: false,
					render: function(data, type, row) {
						const isAvailable = data === 'active';
						const statusConfig = {
							'active': {
								color: 'bg-green-500',
								hoverColor: 'hover:bg-green-600',
								icon: 'ti-check',
								text: 'Tersedia'
							},
							'inactive': {
								color: 'bg-red-500',
								hoverColor: 'hover:bg-red-600',
								icon: 'ti-x',
								text: 'Tidak Tersedia'
							}
						};

						const config = statusConfig[data] || statusConfig['inactive'];

						return `
      <button 
        onclick="toggleStatus(${row.id}, '${data}', this)"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white ${config.color} ${config.hoverColor} border shadow-sm transition-all duration-200 hover:shadow-md active:scale-95"
        title="Klik untuk mengubah status"
      >
        <i class="ti ${config.icon}"></i>
        <span>${config.text}</span>
      </button>
						`;
					}
				},
				{ 
					data: 'action', 
					name: 'action', 
					orderable: false, 
					searchable: false,
					className: 'text-center',
					width: '15%'
				}
			],
			language: {
				search: "Cari:",
				lengthMenu: "Tampilkan _MENU_ data",
				info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
				infoEmpty: "Tidak ada data",
				infoFiltered: "(difilter dari _MAX_ total data)",
				zeroRecords: "Tidak ada data yang cocok",
				emptyTable: "Tidak ada data tersedia",
				paginate: {
					first: "Pertama",
					last: "Terakhir",
					next: "Selanjutnya",
					previous: "Sebelumnya"
				}
			}
		});

	});

	function confirmDelete(button, id) {
		window.tableManager.delete(id, button);
	}
	function showDetailModal(id) {
		document.getElementById('detailModal').classList.remove('hidden');
		document.getElementById('modalContent').innerHTML = `
  <div class="animate-pulse space-y-4 p-5">
    <div class="h-4 bg-gray-200 rounded w-1/3"></div>
    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
    <div class="h-48 bg-gray-200 rounded"></div>
  </div>
		`;


		fetch(`http://127.0.0.1:8000/admin/table/${id}`)
		.then(res => res.json())
		.then(data => {
			document.getElementById('modalContent').innerHTML = `
  <div class="bg-white rounded-lg overflow-hidden">

    <!-- Content -->
    <div class="p-5 space-y-5 text-sm">

      <!-- Info -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <p class="text-gray-500">No Meja</p>
          <p class="font-medium text-gray-800">${data.table_number}</p>
        </div>

        <div>
          <p class="text-gray-500">Status</p>
          <span class="inline-block px-2 py-1 rounded text-xs
            ${data.status === 'active'
            ? 'bg-green-100 text-green-700'
            : 'bg-red-100 text-red-700'}">
            ${data.status === 'active' ? 'Tersedia' : 'Tidak tersedia'}
          </span>
        </div>

        <div>
          <p class="text-gray-500">Dibuat</p>
          <p class="font-medium text-gray-800">
            ${new Date(data.created_at).toLocaleDateString('id-ID')}
          </p>
        </div>
      </div>

      <!-- QR -->
      <div class="border-t pt-4">
        <p class="text-gray-500 mb-3">QR Code Meja</p>
        <div class="flex justify-center">
          <img
            src="${data.qr_image}"
            alt="QR Code Meja ${data.table_number}"
            class="w-48 h-48 object-contain border rounded-md p-3 bg-gray-50"
          >
        </div>
      </div>

    </div>
  </div>
         `;

      })
		.catch(error => {
			document.getElementById('modalContent').innerHTML = `
        <div class="text-red-600">Error loading data: ${error.message}</div>
			`;
		});
	}

	function closeDetailModal() {
		document.getElementById('detailModal').classList.add('hidden');
	}


	function toggleStatus(id, currentStatus, button) {
		button.disabled = true;
		button.classList.add('opacity-50', 'cursor-not-allowed');

		const originalHTML = button.innerHTML;
		button.innerHTML = `
    <i class="ti ti-loader animate-spin"></i>
    <span>Loading...</span>
		`;

		fetch(`/table/${id}/toggle-status`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			}
		})
		.then(res => res.json())
		.then(data => {
			if (data.status) {

				const newStatus = data.status;
				const statusConfig = {
					'active': {
						color: 'bg-green-500 hover:bg-green-600',
						icon: 'ti-check',
						text: 'Tersedia'
					},
					'inactive': {
						color: 'bg-red-500 hover:bg-red-600',
						icon: 'ti-x',
						text: 'Tidak Tersedia'
					}
				};

				const config = statusConfig[newStatus];

				button.className = button.className.replace(/bg-\w+-\d+/g, '').replace(/hover:bg-\w+-\d+/g, '');
				button.classList.add(...config.color.split(' '));

				button.innerHTML = `
        <i class="ti ${config.icon}"></i>
        <span>${config.text}</span>
				`;
				button.setAttribute('onclick', `toggleStatus(${id}, '${newStatus}', this)`);
				showToast('success', data.message || 'Status berhasil diubah!');
			}
		})
		.catch(error => {
			console.error('Error:', error);
			button.innerHTML = originalHTML;
			showToast('error', 'Gagal mengubah status. Silakan coba lagi.');
		})
		.finally(() => {
			button.disabled = false;
			button.classList.remove('opacity-50', 'cursor-not-allowed');
		});
	}

	function showToast(type, message) {
		const toast = document.createElement('div');
		const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';

		toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2 animate-slide-in`;
		toast.innerHTML = `
    <i class="ti ti-${type === 'success' ? 'check' : 'alert-circle'}"></i>
    <span>${message}</span>
		`;

		document.body.appendChild(toast);

		setTimeout(() => {
			toast.classList.add('animate-slide-out');
			setTimeout(() => toast.remove(), 300);
		}, 3000);
	}
</script>

@endsection