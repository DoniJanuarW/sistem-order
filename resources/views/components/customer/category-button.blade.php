@props(['value', 'active' => false])

<button onclick="filterCategory('{{ $value }}')" 
        {{ $attributes->merge(['class' => 'category-btn px-5 py-2 rounded-full whitespace-nowrap text-sm font-medium transition-all shadow-sm border ' . ($active ? 'active bg-[#014421] text-white border-transparent' : 'bg-white text-gray-600 border-gray-100')]) }} 
        data-category="{{ $value }}">
    {{ $slot }}
</button>