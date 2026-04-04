@extends('layouts.app')

@section('title', 'Grand Santhi Coffee Shop - User')

@section('css')
<x-datatable-style/>
@endsection

@section('content')
<x-card-breadcrumb title="User Management" :isCreate="true" createUrl="admin.user.create" />

<!-- DataTable -->
<x-datatable id="datatables">
  <thead>
    <tr>
      <th class="w-16">No</th>
      <th>Nama User</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Full Name</th>
      <th>Role</th>
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
    window.userManager = new CrudManager({
      entity: 'user',
      modalId: 'create-modal',
      formId: 'user-form',
      apiUrl: '/admin/user',
      dataTableUrl: '{{ route('admin.user.tableUser') }}',
      formFields: ['name', 'email', 'phone', 'full_name', 'role'],
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
          data: 'email', 
          name: 'email',
          render: function(data) {
            return `
              <a href="mailto:${data}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors duration-200 group">
                <i class="ti ti-mail text-sm"></i>
                <span class="group-hover:underline">${data}</span>
              </a>
            `;
          }
        },
        { 
          data: 'phone', 
          name: 'phone',
          render: function(data) {
            return data ? `
              <div class="inline-flex items-center gap-2 text-gray-700">
                <i class="ti ti-phone text-green-600"></i>
                <span>${data}</span>
              </div>
            ` : '<span class="text-gray-400 italic">-</span>';
          }
        },
        { 
          data: 'full_name', 
          name: 'full_name',
          render: function(data) {
            return data ? `<span class="text-gray-700">${data}</span>` : '<span class="text-gray-400 italic">-</span>';
          }
        },
        { 
          data: 'role', 
          name: 'role',
          render: function(data) {
            const roles = {
              'admin': {
                color: 'text-purple-800 bg-purple-200'
              },
              'cashier': {
                color: 'text-green-800 bg-green-200'
              },
              'customer': {
                color: 'text-blue-800 bg-blue-200'
              }
            };
            const role = roles[data.toLowerCase()] || {
              color: 'bg-gray-100 text-gray-800 border-gray-200',
            };
            return `
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold ${role.color} border shadow-sm">
                ${data.charAt(0).toUpperCase() + data.slice(1)}
              </span>
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
    window.userManager.delete(id, button);
  }
</script> 
@endsection