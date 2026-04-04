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
    // --- 1. INISIALISASI VARIABEL GLOBAL ---
    // Cek status login dari Laravel Blade
    const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    const loginUrl = "{{ route('login') }}";

    let currentCategory = 'all';
    let currentProductPrice = 0;
    let currentQty = 1;
    let menu_id = null;
    let buttonAddToCart = document.getElementById('buttonAddToCart');

    // --- 2. FILTER & SEARCH LOGIC ---
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
    
    // --- 3. OPEN MODAL (DENGAN PENGECEKAN LOGIN) ---
    function openProductModal(data) {
        // PENGECEKAN: Jika user belum login, hentikan proses dan tampilkan alert
        if (!isLoggedIn) {
            Swal.fire({
                title: 'Akses Dibatasi',
                text: 'Silakan login terlebih dahulu untuk melihat detail dan memesan menu.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#014421',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = loginUrl;
                }
            });
            return; // Stop fungsi di sini
        }

        // Jika sudah login, lanjut buka modal
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

        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('translate-y-full');
        }, 10);
    }

    function closeProductModal() {
        const modal = document.getElementById('productModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        const notesField = document.getElementById('orderNotes');
        if(notesField) notesField.value = ''; 

        backdrop.classList.add('opacity-0');
        panel.classList.add('translate-y-full');

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

    // --- 4. ADD TO CART LOGIC (Backend Protection Check) ---
    async function addToCartAction() {
        // Double Check: Harusnya tidak bisa sampai sini kalau belum login, tapi untuk keamanan
        if (!isLoggedIn) {
            window.location.href = loginUrl;
            return;
        }

        if (!menu_id) {
            Swal.fire('Error', 'Produk tidak valid', 'error');
            return;
        }

        const notesField = document.querySelector('textarea');
        const note = notesField ? notesField.value : '';

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

            // Handle jika session habis di tengah jalan (401 Unauthorized)
            if (response.status === 401) {
                window.location.href = loginUrl;
                return;
            }

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

    if(buttonAddToCart) {
        buttonAddToCart.addEventListener('click', function(e) {
            e.preventDefault();
            addToCartAction();
        });
    }

</script>
@endsection