@extends('layouts.app')

@section('title', 'Grand Santhi Coffee Shop - Category Management')

@section('css')
@endsection

@section('content')
<x-card-breadcrumb title="Category" :isCreate="false" createUrl='/' />

<form id="category-form">
  <div class="bg-white border rounded-xl shadow-xl p-6">
    <div class="grid grid-cols-1 gap-4">
      <x-form-input 
      name="name" 
      id="name" 
      label="Nama Kategori" 
      placeholder="Masukkan nama kategori" 
      icon="ti ti-category" 
      required 
      :value="old('name', $category->name ?? '')"
      />
    </div>
    
    <div class="flex gap-2 my-3">
      <button type="reset" class="flex items-center gap-2 px-5 py-2
      !bg-[#dc3545] text-white rounded-lg
      hover:bg-[#F83B3B] active:bg-[#F83B3B]
      focus:ring-4 focus:ring-[#F83B3B]
      transition-all duration-200 shadow-md hover:shadow-lg
      text-sm font-medium">
      Reset
    </button>
    <x-button-spinner id="btn-save" text="Simpan Kategori" loading-text="Menyimpan..." />
  </div>
</div>
</form>

<x-loading-spinner id="loading-spinner" />

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
      columns: []
    });
    
    window.categoryManager.isEditMode = {{ $category ? 'true' : 'false' }};
    window.categoryManager.currentId = {{ $category->id ?? 'null' }};
  });
</script>
@endsection