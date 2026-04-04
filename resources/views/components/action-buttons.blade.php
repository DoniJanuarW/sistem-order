<div class="flex items-center gap-2 justify-center">

   @if(!empty($showDetail))
   <button
      onclick="showDetailModal({{ $id }})"
      class="px-3 py-1.5 text-xs bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition"
   >
      <i class="ti ti-info-circle"></i>
      Info
   </button>
   @endif

   <a href="{{ route($editRoute, $id) }}"
      class="px-3 py-1.5 text-xs bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
      <i class="ti ti-edit"></i> Edit
   </a>

   <button onclick="confirmDelete(this, {{ $id }})"
      class="btn-delete px-3 py-1.5 text-xs bg-red-100 text-red-700 rounded-lg hover:bg-red-200 flex items-center gap-1">

      <span class="btn-text">
         <i class="ti ti-trash"></i> Hapus
      </span>

      <svg class="btn-spinner hidden animate-spin w-5 h-5 text-white"
         viewBox="0 0 24 24" fill="none">
         <circle class="opacity-25"
            cx="12" cy="12" r="10"
            stroke="currentColor" stroke-width="4" />
         <path class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
      </svg>
   </button>

</div>
