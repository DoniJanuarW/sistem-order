@extends('layouts.app')

@section('title', 'Grand Santhi Coffee Shop - Menu')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
  #datatables {
    width: 100% !important;
    min-width: 850px; /* Atur angka ini sesuai kebutuhan kolom Anda */
  }
  /* Container Wrapper */
  .dataTables_wrapper {
    @apply bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6;
  }

  /* Header Controls - Perbaikan Responsive */
  .dataTables_wrapper .dataTables_length, 
  .dataTables_wrapper .dataTables_filter {
    @apply float-none mb-2 text-left sm:text-right sm:float-right;
  }
  
  @screen sm {
    #datatables {
      @apply min-w-full; /* Di layar besar balikkan ke lebar normal */
    }
    .dataTables_wrapper .dataTables_length { @apply float-left; }
    .dataTables_wrapper .dataTables_filter { @apply float-right; }
  }

  .dataTables_wrapper .dataTables_length label,
  .dataTables_wrapper .dataTables_filter label {
    @apply flex flex-col sm:flex-row items-center gap-2 text-sm font-medium text-gray-700;
  }

  .dataTables_filter input {
    /* Lebar penuh di mobile, w-64 di desktop */
    @apply px-4 py-2 border border-gray-300 rounded-lg 
    focus:ring-2 focus:ring-green-500 focus:border-green-500 
    transition-all duration-200 w-full sm:w-64
    placeholder:text-gray-400;
  }

  /* Table Container agar bisa scroll horizontal di mobile */
  .dataTables_wrapper .dataTables_scroll,
  .x-datatable-container {
     @apply overflow-x-auto;
  }

  /* Style untuk Detail Modal agar responsive */
  #detailModal .max-w-md {
    @apply mx-4 sm:mx-auto w-[calc(100%-2rem)] sm:w-full;
  }

  /* Footer Controls (Pagination & Info) */
  .dataTables_info, .dataTables_paginate {
    @apply float-none text-center sm:text-left w-full sm:w-auto;
  }

  @screen sm {
    .dataTables_info { @apply float-left; }
    .dataTables_paginate { @apply float-right; }
  }

   @media (max-width: 640px) {
    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter {
      float: none !important;
      text-align: left !important;
      margin-bottom: 1rem;
    }
    
    .dataTables_filter input {
      width: 100% !important; /* Input pencarian jadi lebar penuh di HP */
      margin-left: 0 !important;
    }
    
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
      float: none !important;
      text-align: center !important;
    }
  }
</style>
<style>
  @keyframes slide-in {
    from {
      transform: translateX(100%);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }

  @keyframes slide-out {
    from {
      transform: translateX(0);
      opacity: 1;
    }
    to {
      transform: translateX(100%);
      opacity: 0;
    }
  }

  .animate-slide-in {
    animation: slide-in 0.3s ease-out;
  }

  .animate-slide-out {
    animation: slide-out 0.3s ease-in;
  }
</style>
@endsection

@section('content')
<x-card-breadcrumb title="Menu Management" :isCreate="true" createUrl="admin.menu.create"/>

<div class="w-full bg-white rounded-xl shadow-sm border border-gray-100 p-2 sm:p-4">
    <div class="overflow-x-auto min-w-full inline-block align-middle">
        <x-datatable id="datatables" class="min-w-[800px]"> <thead>
                <tr>
                    <th class="w-16">No</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th class="w-32 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </x-datatable>
    </div>
</div>


<div id="detailModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
 <div class="bg-white rounded-xl w-full max-w-md p-6">
  <h2 class="text-lg font-semibold mb-4">Detail Menu</h2>

  <div id="modalContent" class="space-y-2 text-sm">
   Loading...
 </div>

 <div class="mt-4 text-right">
   <button onclick="closeDetailModal()"
   class="px-4 py-2 bg-gray-200 rounded-lg">
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
    window.menuManager = new CrudManager({
      entity: 'menu',
      modalId: 'create-modal',
      formId: 'menu-form',
      apiUrl: '/admin/menu',
      responsive: true,
      autoWidth: false,
      dataTableUrl: '{{ route('admin.menu.tableMenu') }}',
      formFields: ['category_id', 'name', 'price', 'description', 'image', 'status'],
      columns: [
        { 
          data: 'no', 
          name: 'no',
          className: 'text-center font-bold text-gray-900',
          width: '5%'
        },
        { 
          data: 'name', 
          name: 'name',
          render: function(data, type, row) {
            return `
              <div class="flex flex-col">
                <span class="font-semibold text-gray-900">${data}</span>
              ${row.description ? `<span class="text-xs text-gray-500 mt-1 line-clamp-2">${row.description}</span>` : ''}
              </div>
            `;
          }
        },
        { 
          data: 'category', 
          name: 'category',
          render: function(data) {
            return `
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-orange-800 bg-orange-200 border border-orange-300 shadow-sm">
                <i class="ti ti-category-2"></i>
                ${data}
              </span>
            `;
          }
        },
        { 
          data: 'price', 
          name: 'price',
          render: function(data) {
            return `
              <div class="inline-flex items-center gap-1 text-green-700 font-bold">
                <span class="text-sm">Rp</span>
                <span>${new Intl.NumberFormat('id-ID').format(data)}</span>
              </div>
            `;
          }
        },
        { 
          data: 'status', 
          name: 'status',
          orderable: false,
          render: function(data, type, row) {
            const isAvailable = data === 'available';
            const statusConfig = {
              'available': {
                color: 'bg-green-500',
                hoverColor: 'hover:bg-green-600',
                icon: 'ti-check',
                text: 'Tersedia'
              },
              'unavailable': {
                color: 'bg-red-500',
                hoverColor: 'hover:bg-red-600',
                icon: 'ti-x',
                text: 'Tidak Tersedia'
              }
            };

            const config = statusConfig[data] || statusConfig['unavailable'];

            return `
      <button 
        onclick="toggleMenuStatus(${row.id}, '${data}', this)"
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
    window.menuManager.delete(id, button);
  }


  function toggleMenuStatus(id, currentStatus, button) {
    let toggleUrl = `{{ route('admin.menu.toggleStatus', ['id' => ':id']) }}`.replace(':id', id);

    button.disabled = true;
    button.classList.add('opacity-50', 'cursor-not-allowed');
    
    const originalHTML = button.innerHTML;
    button.innerHTML = `
    <i class="ti ti-loader animate-spin"></i>
    <span>Loading...</span>
    `;
    
    fetch(toggleUrl, {
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
          'available': {
            color: 'bg-green-500 hover:bg-green-600',
            icon: 'ti-check',
            text: 'Tersedia'
          },
          'unavailable': {
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
        button.setAttribute('onclick', `toggleMenuStatus(${id}, '${newStatus}', this)`);
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


  function showDetailModal(id) {
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('modalContent').innerHTML = `
    <div class="flex items-center justify-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500"></div>
    </div>
    `;

    fetch(`{{ route('admin.menu.get', ['id' => ':id']) }}`.replace(':id', id))
    .then(res => res.json())
    .then(data => {
      let menu = data;
      const baseUrl = window.location.origin;
      const imageUrl = menu.image ?? `${baseUrl}/assets/images/no-image.png`;
      // Format harga
      const formattedPrice = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
      }).format(menu.price);
      
      // Status badge
      const statusConfig = {
        'available': {
          bg: 'bg-green-100',
          text: 'text-green-800',
          border: 'border-green-300',
          icon: 'ti-check-circle',
          label: 'Tersedia'
        },
        'unavailable': {
          bg: 'bg-red-100',
          text: 'text-red-800',
          border: 'border-red-300',
          icon: 'ti-x-circle',
          label: 'Tidak Tersedia'
        }
      };
      
      const status = statusConfig[menu.status] || statusConfig['unavailable'];
      document.getElementById('modalContent').innerHTML = `
  <div class="bg-white rounded-lg overflow-hidden">

    <!-- Image -->
    <div class="aspect-video bg-gray-100 flex items-center justify-center">
  <img 
    src="${imageUrl}" 
    class="w-full h-full object-cover"
    onerror="this.src='${baseUrl}/assets/images/no-image.png'"
  >
</div>

    <!-- Content -->
    <div class="p-5 space-y-5">

      <!-- Header -->
      <div class="border-b pb-3">
        <h3 class="text-xl font-semibold text-gray-800">${menu.name}</h3>
        <p class="text-sm text-gray-500 mt-1">
          ${menu.description || 'Tidak ada deskripsi'}
        </p>
      </div>

      <!-- Info -->
      <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
          <p class="text-gray-500">Kategori</p>
          <p class="font-medium text-gray-800">${menu.category.name}</p>
        </div>

        <div>
          <p class="text-gray-500">Harga</p>
          <p class="font-semibold text-gray-800">${formattedPrice}</p>
        </div>

        <div>
          <p class="text-gray-500">Status</p>
          <span class="inline-block px-2 py-1 text-xs rounded 
            ${menu.status === 'available' 
            ? 'bg-green-100 text-green-700' 
            : 'bg-red-100 text-red-700'}">
            ${menu.status === 'available' ? 'Tersedia' : 'Tidak tersedia'}
          </span>
        </div>

        <div>
          <p class="text-gray-500">ID Menu</p>
          <p class="font-medium text-gray-800">#${menu.id}</p>
        </div>
      </div>

      <!-- Dates -->
      <div class="text-xs text-gray-500 border-t pt-3 space-y-1">
        <div>Dibuat: ${new Date(menu.created_at).toLocaleDateString('id-ID')}</div>
        <div>Diupdate: ${new Date(menu.updated_at).toLocaleDateString('id-ID')}</div>
      </div>

    </div>
  </div>
          `;

        })
    .catch(error => {
      document.getElementById('modalContent').innerHTML = `
        <div class="text-center py-12">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
            <i class="ti ti-alert-circle text-red-600 text-3xl"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-800 mb-2">Gagal Memuat Data</h3>
          <p class="text-red-600 mb-4">${error.message}</p>
          <button 
            onclick="showDetailModal(${id})"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl hover:from-red-600 hover:to-red-700 transition-all duration-300"
          >
            <i class="ti ti-refresh"></i>
            Coba Lagi
          </button>
        </div>
      `;
    });
  }

  function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
  }
</script> 
@endsection