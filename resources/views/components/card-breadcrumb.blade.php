@props([
    'title' => 'Dashboard',
    'createUrl' => '/',
    'isCreate' => false
])
<div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl shadow-sm p-6 mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-green-900 mb-2">
                Data {{ $title }}
            </h2>
            <nav class="flex items-center text-sm text-gray-600">
                <a href="/" class="hover:text-green-700 transition-colors flex items-center gap-1">
                    <i class="ti ti-home text-base"></i>
                    Dashboard
                </a>
                <i class="ti ti-chevron-right mx-2 text-gray-400"></i>
                <span class="text-green-700 font-medium">{{ $title }}</span>
            </nav>
        </div>
        @if($isCreate)
        <a
            href="{{route($createUrl)}}"
            class="flex items-center gap-2 px-5 py-1.5
                   bg-[#014421] text-white rounded-lg
                   hover:bg-green-300 active:bg-green-800
                   focus:ring-4 focus:ring-green-300
                   transition-all duration-200 shadow-md hover:shadow-lg
                   text-sm font-medium">
            <i class="ti ti-plus text-lg"></i>
            <span>Buat Data</span>
        </a>
        @endif
    </div>
</div>