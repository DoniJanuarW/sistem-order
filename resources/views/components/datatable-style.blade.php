<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
  #datatables {
    @apply min-w-[800px] w-full;
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
</style>