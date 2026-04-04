@extends('layouts.app')

@section('title', 'Grand Santhi Coffee Shop - Category')

@section('css')
<x-datatable-style/>
@endsection

@section('content')
<x-card-breadcrumb title="Category Management" :isCreate="true" createUrl="admin.category.create" />

<!-- DataTable -->
<x-datatable id="datatables">
  <thead>
    <tr>
      <th class="w-16">No</th>
      <th>Nama Kategori</th>
      <th>Dibuat Pada</th>
      <th class="w-32 text-center">Aksi</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</x-datatable>

@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets/js/action/crud-manager.js') }}"></script>

<script>
  $(document).ready(function() {
    window.categoryManager = new CrudManager({
      entity: 'category',
      modalId: 'create-modal',
      formId: 'category-form',
      apiUrl: '/admin/category',
      dataTableUrl: '{{ route('admin.category.tableCategory') }}',
      formFields: ['name'],
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
          render: function(data) {
            return `
              <div class="flex items-center gap-3">
                <span class="font-semibold text-gray-900">${data}</span>
              </div>
            `;
          }
        },
        { 
          data: 'created_at', 
          name: 'created_at',
          render: function(data) {
            const date = new Date(data);
            return `
              <div class="inline-flex items-center gap-2 text-gray-700">
                <i class="ti ti-calendar text-blue-600"></i>
                <span>${date.toLocaleDateString('id-ID', { 
                  day: 'numeric', 
                  month: 'long', 
                  year: 'numeric' 
                })}</span>
              </div>
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
    window.categoryManager.delete(id, button);
  }
</script> 
@endsection