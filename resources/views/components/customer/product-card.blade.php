@props([
    'id', 
    'name', 
    'price', 
    'category', 
    'image' => '☕', // Default emoji jika tidak ada gambar
    'description' => '',
    'status' => 'available',
    // 'originalPrice' => null
])

@php
    $productData = json_encode([
        'id' => $id,
        'name' => $name,
        'price' => $price,
        // 'originalPrice' => $originalPrice,
        'category' => $category,
        'description' => $description,
        'image' => $image,
        'status' => $status,
    ]);
@endphp

<div {{ $attributes->merge(['class' => 'product-card group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 cursor-pointer']) }} 
     @if($status !== 'available') 
     style="opacity: 0.5; pointer-events: none;" 
     @else
     onclick='openProductModal({!! $productData !!})'
     @endif
     data-category="{{ $category }}" 
     data-name="{{ $name }}">
    
    <div class="relative h-36 bg-gray-100 overflow-hidden">
        <div class="absolute inset-0 flex items-center justify-center bg-gray-200 text-4xl group-hover:scale-110 transition-transform duration-500">
            <img src="{{ $image }}" alt="{{ $name }}" class="object-cover w-full h-full">
        </div>
        
        {{-- <button onclick="toggleFavorite({{ $id }})" 
                class="absolute top-2 right-2 bg-white/80 backdrop-blur rounded-full p-1.5 shadow-sm active:scale-90 transition-transform" 
                data-id="{{ $id }}">
            <svg class="w-4 h-4 text-gray-400 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
        </button> --}}
    </div>
    
    <div class="p-3">
        <div class="flex items-start justify-between mb-1">
            <h3 class="font-bold text-gray-800 text-sm leading-tight line-clamp-2">{{ $name }}</h3>
        </div>
        
        <p class="text-[10px] text-gray-500 mb-3 line-clamp-1">{{ $description }}</p>
        
        <div class="flex items-center justify-between">
            <div class="flex flex-col">
                {{-- @if($originalPrice)
                    <span class="text-[10px] text-gray-400 line-through">Rp {{ number_format($originalPrice, 0, ',', '.') }}</span>
                @endif --}}
                <span class="text-[#014421] font-bold text-sm">Rp {{ number_format($price, 0, ',', '.') }}</span>
            </div>
            
            <button class="bg-[#014421] text-[#014421] w-8 h-8 rounded-full flex items-center justify-center hover:bg-green-800 hover:text-white shadow-green-200 shadow-lg active:scale-90 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </button>
        </div>
    </div>
</div>