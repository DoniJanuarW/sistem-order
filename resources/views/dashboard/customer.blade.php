@extends('layouts.app')
@section('title', 'Home - Grand Santhi Coffee')

@section('css')
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .category-btn.active {
        background-color: #014421; color: white; font-weight: 600;
        box-shadow: 0 4px 6px -1px rgba(1, 68, 33, 0.3);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 pb-24 relative">

    <x-customer.mobile-header>
        <x-customer.search-input />
    </x-customer.mobile-header>

    <div class="bg-gray-50/95 backdrop-blur-sm py-4 border-b border-gray-100/50 shadow-sm transition-all">
        <div class="max-w-md mx-auto px-4">
            <div class="flex gap-3 overflow-x-auto scrollbar-hide py-1">
                <x-customer.category-button value="all" :active="true">Semua</x-customer.category-button>
                @foreach($categories as $category)
                <x-customer.category-button value="{{$category->name}}">{{$category->name}}</x-customer.category-button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 mt-2">
        <div id="productsGrid" class="grid grid-cols-2 gap-3 pb-4">
            @foreach($menus as $menu)
            <x-customer.product-card 
            id="{{$menu->id}}" 
            category="{{$menu->category->name}}"  
            name="{{$menu->name}}" 
            description="{{$menu->description}}"  
            price="{{$menu->price}}"  
            image="{{$menu->image_url}}"
            status="{{$menu->status}}"
            />
            @endforeach
        </div>
    </div>
    <x-customer.product-modal />
    <x-customer.bottom-nav active="beranda" />

</div>
@endsection
@section('js')
<script>
    let currentCategory = 'all';
    let currentProductPrice = 0;
    let currentQty = 1;
    let menu_id = null;
    let buttonAddToCart = document.getElementById('buttonAddToCart');

    // Toggle Favorite Animation
    function toggleFavorite(id) {
        const btn = document.querySelector(`[data-id="${id}"]`);
        const svg = btn.querySelector('svg');
        
        if (favorites.includes(id)) {
            favorites = favorites.filter(fid => fid !== id);
            svg.classList.remove('text-red-500', 'fill-current');
            svg.classList.add('text-gray-400');
        } else {
            favorites.push(id);
            svg.classList.remove('text-gray-400');
            svg.classList.add('text-red-500', 'fill-current');
        }
    }

    function filterCategory(category) {
        currentCategory = category;
        
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-[#014421]', 'text-white', 'shadow-md');
            btn.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-100');
        });

        const activeBtns = document.querySelectorAll(`.category-btn[data-category="${category}"]`);
        activeBtns.forEach(btn => {
            btn.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-100');
            btn.classList.add('active'); 
        });
        
        filterProducts();
    }

    function filterProducts() {
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();
        const products = document.querySelectorAll('.product-card');
        
        products.forEach(product => {
            const category = product.dataset.category;
            const name = product.dataset.name.toLowerCase();
            
            const matchCategory = currentCategory === 'all' || category === currentCategory;
            const matchSearch = name.includes(searchQuery);
            
            if (matchCategory && matchSearch) {
                product.classList.remove('hidden');
                product.animate([
                    { opacity: 0, transform: 'scale(0.95)' },
                    { opacity: 1, transform: 'scale(1)' }
                ], { duration: 300, easing: 'ease-out' });
            } else {
                product.classList.add('hidden');
            }
        });
    }

    document.getElementById('searchInput').addEventListener('input', filterProducts);

 {{--    function changeTab(tab) {
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('text-[#014421]');
            item.classList.add('text-gray-400');
        });
        
        const activeTab = document.getElementById(`tab-${tab}`);
        if(activeTab) {
            activeTab.classList.remove('text-gray-400');
            activeTab.classList.add('text-[#014421]');
        }
    }
 --}}
function openProductModal(data) {
    menu_id = data.id;
    currentQty = 1;
    currentProductPrice = parseInt(data.price);
    updateQtyUI();

    document.getElementById('modalTitle').innerText = data.name;
    document.getElementById('modalDescription').innerText = data.description || 'Tidak ada deskripsi.';
    document.getElementById('modalCategory').innerText = data.category;
    let modalImg = document.getElementById('modalImageDisplay');
    document.getElementById('modalPrice').innerText = formatRupiah(data.price);

    if (data.image != null) {
        modalImg.className = "absolute inset-0 bg-gray-200 overflow-hidden group";
        modalImg.innerHTML = `
            <img src="${data.image}" 
                 alt="${data.name}" 
                 class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
        `;   
    }

    const originalPriceEl = document.getElementById('modalOriginalPrice');
    if(data.originalPrice) {
        originalPriceEl.innerText = formatRupiah(data.originalPrice);
        originalPriceEl.classList.remove('hidden');
    } else {
        originalPriceEl.classList.add('hidden');
    }

    const modal = document.getElementById('productModal');
    const backdrop = document.getElementById('modalBackdrop');
    const panel = document.getElementById('modalPanel');

    modal.classList.remove('hidden');

    // Animasi Masuk
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        // Mobile (Slide Up)
        panel.classList.remove('translate-y-full');
        // Desktop (Zoom In & Fade)
        panel.classList.remove('md:opacity-0', 'md:scale-95');
        panel.classList.add('md:opacity-100', 'md:scale-100');
    }, 10);
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    const backdrop = document.getElementById('modalBackdrop');
    const panel = document.getElementById('modalPanel');
    const notesField = document.getElementById('orderNotes');
    notesField.value = ''; 

    // Animasi Keluar
    backdrop.classList.add('opacity-0');
    // Mobile (Slide Down)
    panel.classList.add('translate-y-full');
    // Desktop (Zoom Out & Fade)
    panel.classList.remove('md:opacity-100', 'md:scale-100');
    panel.classList.add('md:opacity-0', 'md:scale-95');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function updateQty(change) {
    const newQty = currentQty + change;
    if (newQty >= 1) {
        currentQty = newQty;
        updateQtyUI();
    }
}

function updateQtyUI() {
    document.getElementById('qtyDisplay').innerText = currentQty;

    const total = currentProductPrice * currentQty;
    document.getElementById('btnTotalPrice').innerText = formatRupiah(total);
}
function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(number);
}

async function addToCartAction() {

    if (!menu_id) {
        Swal.fire('Error', 'Produk tidak valid', 'error');
        return;
    }

    const note = document.querySelector('textarea').value;

    const payload = {
        menu_id: menu_id,
        quantity: currentQty,
        notes: note
    };

    const btn = buttonAddToCart;
    const originalText = btn.innerHTML;
    btn.innerHTML = 'Menyimpan...';
    btn.disabled = true;

    try {
        const response = await fetch("{{ route('customer.cart.addToCart') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (response.ok) {
            closeProductModal();

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: result.message,
                showConfirmButton: false,
                timer: 1500
            });

            const cartBadge = document.getElementById('sumCart');
            if (cartBadge) {
                cartBadge.innerText = result.data.cart_count; 
                
                if(result.data.cart_count > 0){
                    cartBadge.classList.remove('hidden');
                }
            }

        } else {
            throw new Error(result.message || 'Terjadi kesalahan');
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.message
        });
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

buttonAddToCart.addEventListener('click', function(e) {
    e.preventDefault();
    addToCartAction();
});

</script>
@endsection