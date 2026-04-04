@extends('layouts.app')

@section('title', 'Grand Santhi Coffee Shop - Menu Management')

@section('css')
<style>
  .image-preview {
    width: 200px;
    height: 200px;
    border: 2px dashed #cbd5e0;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background-color: #f7fafc;
  }
  .image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
</style>
@endsection

@section('content')
@php
// dd($menu);
@endphp
<x-card-breadcrumb title="Menu" :isCreate="false" createUrl='/' />

<form id="menu-form" enctype="multipart/form-data">
  <div class="bg-white border rounded-xl shadow-xl p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      
      <x-form-input 
        name="name" 
        id="name" 
        label="Nama Menu" 
        placeholder="Masukkan nama menu" 
        icon="ti ti-meat" 
        required 
        :value="old('name', $menu->name ?? '')"
      />

      <x-form-select 
        name="category_id" 
        id="category_id" 
        label="Kategori" 
        icon="ti ti-category" 
        required
      >
        @php
        $selectedCategory = old('category_id', $menu->category_id ?? '');
        @endphp
        <option value="">Pilih Kategori</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
            {{ $category->name }}
          </option>
        @endforeach
      </x-form-select>

      <x-form-input 
        type="number" 
        name="price" 
        id="price" 
        label="Harga" 
        placeholder="Masukkan harga menu" 
        icon="ti ti-currency-dollar" 
        required 
        :value="old('price', $menu->price ?? '')"
        step="0.01"
      />

      <x-form-select 
        name="status" 
        id="status" 
        label="Status" 
        icon="ti ti-flag" 
        required
      >
        @php
        $selectedStatus = old('status', $menu->status ?? 'available');
        @endphp
        <option value="available" {{ $selectedStatus === 'available' ? 'selected' : '' }}>Tersedia</option>
        <option value="unavailable" {{ $selectedStatus === 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
      </x-form-select>

    </div>

    <div class="grid grid-cols-1 gap-4 mt-4">
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
          <i class="ti ti-file-text mr-1"></i> Deskripsi
        </label>
        <textarea 
          name="description" 
          id="description" 
          rows="4" 
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
          placeholder="Masukkan deskripsi menu (opsional)"
        >{{ old('description', $menu->description ?? '') }}</textarea>
      </div>

      <div>
        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
          <i class="ti ti-photo mr-1"></i> Gambar Menu
        </label>
        <input 
          type="file" 
          name="image" 
          id="image" 
          accept="image/*"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
          onchange="previewImage(event)"
        />
        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, atau WEBP. Maksimal 2MB</p>
        
        <div class="mt-4">
          <div id="imagePreview" class="image-preview">
            @if(isset($menu) && $menu->image)
              <img src="{{$menu->image}}" alt="Current Image">
            @else
              <span class="text-gray-400"><i class="ti ti-photo text-4xl"></i></span>
            @endif
          </div>
        </div>
      </div>
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
      <x-button-spinner id="btn-save" text="Simpan Menu" loading-text="Menyimpan..." />
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
  // Preview image before upload
  function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
      }
      reader.readAsDataURL(file);
    }
  }

  $(document).ready(function() {
    window.menuManager = new CrudManager({
      entity: 'menu',
      modalId: 'create-modal',
      formId: 'menu-form',
      apiUrl: '/admin/menu',
      dataTableUrl: '{{ route('admin.menu.tableMenu') }}',
      formFields: ['category_id', 'name', 'price', 'description', 'image', 'status'],
      columns: []
    });
    
    window.menuManager.isEditMode = {{ $menu ? 'true' : 'false' }};
    window.menuManager.currentId = {{ $menu->id ?? 'null' }};
  });
</script>
@endsection