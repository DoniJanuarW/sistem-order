<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Grand Santhi Coffee Shop')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/logo_grandsanthi.png') }}" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.3/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    @yield('css')

    <style>
        /* Custom scrollbar untuk sidebar agar terlihat lebih rapi */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #013519; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #025c2d; border-radius: 3px; }
    </style>
    <style>
        /* Transisi halus untuk sidebar dan konten */
        #sidebar, .sidebar-text, .logo-text {
            transition: all 0.3s ease-in-out;
        }

        /* === MODE MINI SIDEBAR (Desktop Only) === */
        @media (min-width: 1024px) {
            /* Kecilkan lebar sidebar menjadi 80px (w-20) */
            body.mini-sidebar #sidebar {
                width: 5rem; 
            }

            /* Sembunyikan semua teks label dan judul section */
            body.mini-sidebar .sidebar-text,
            body.mini-sidebar .section-label,
            body.mini-sidebar .logo-text {
                display: none !important;
                opacity: 0;
            }

            /* Tampilkan logo icon (gambar) */
            body.mini-sidebar .logo-icon {
                display: block !important;
                margin: 0 auto;
            }

            /* Pusatkan icon menu */
            body.mini-sidebar .sidebar-link {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }

            /* Pusatkan user avatar di bawah */
            body.mini-sidebar .user-profile {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }
        }
    </style>
</head>

@php
$userRole = Auth::check() ? Auth::user()->role : null;
$rolesWithSidebar = ['admin', 'cashier'];
$showSidebar = in_array($userRole, $rolesWithSidebar);
@endphp

<body class="bg-gray-50 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        {{-- ================= SIDEBAR START ================= --}}
        @if($showSidebar)
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#014421] transition-all duration-300 lg:static lg:translate-x-0 -translate-x-full shadow-xl flex flex-col flex-shrink-0 overflow-hidden">

            <div class="flex items-center justify-between h-16 px-6 border-b border-green-800 transition-all duration-300">
                <a href="{{route('dashboard')}}" class="flex items-center gap-2 text-white font-bold text-xl w-full">
                    <img src="{{ asset('assets/images/logos/logo_grandsanthi.png') }}" class="h-8 w-auto logo-icon" alt="Logo" />

                    {{-- <span class="lg:block hidden logo-text whitespace-nowrap">Grand Coffee</span> --}}
                </a>
                <button id="sidebarClose" class="lg:hidden text-white hover:bg-green-700 rounded p-1">
                    <i class="ti ti-x text-xl"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto p-4 custom-scroll overflow-x-hidden">
                <div class="space-y-6">
                    @foreach (config('sidebar') as $section)
                    @if (in_array($userRole, $section['roles']))
                    <div>
                        <div class="px-3 mb-2 text-xs font-semibold text-white uppercase tracking-wider section-label whitespace-nowrap">
                            {{ $section['section'] }}
                        </div>

                        <ul class="space-y-1">
                            @foreach ($section['items'] as $item)
                            @if (in_array($userRole, $item['roles']))
                            <li>
                                <a href="{{ route($item['route']) }}" title="{{ $item['label'] }}"
                                class="sidebar-link flex items-center gap-3 px-3 py-2 text-sm font-medium text-white rounded-lg hover:bg-green-700 transition-colors {{ request()->routeIs($item['route']) ? 'bg-green-800 shadow-inner' : '' }}">

                                <i class="{{ $item['icon'] }} text-lg flex-shrink-0"></i>

                                <span class="sidebar-text whitespace-nowrap">{{ $item['label'] }}</span>
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>
                @endif
                @endforeach
            </div> 
        </nav>

        <div class="p-4 border-t border-green-800">
            <div class="flex items-center gap-3 user-profile transition-all">
                <img src="{{ asset('assets/images/profile/user-1.jpg') }}" class="w-8 h-8 rounded-full flex-shrink-0" alt="User" />

                <div class="overflow-hidden sidebar-text">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white truncate">{{ ucfirst($userRole) }}</p>
                </div>
            </div>
        </div>
    </aside>
    @endif
    {{-- ================= SIDEBAR END ================= --}}


    {{-- ================= MAIN CONTENT WRAPPER ================= --}}
    {{-- ADDED: lg:ml-64 only if sidebar is shown --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative transition-all duration-300">

        @if($showSidebar)
        <header class="bg-white shadow-sm z-40 sticky top-0 h-16 flex items-center">
            <div class="w-full px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-4">
                        @if($showSidebar)
                        <button id="desktopSidebarToggle" class="hidden lg:block text-gray-500 hover:text-[#014421] focus:outline-none p-2 rounded-md hover:bg-gray-100 mr-3 transition-colors">
                            <i class="ti ti-menu-2 text-2xl"></i>
                        </button>
                        <button id="sidebarToggle" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none p-2 rounded-md hover:bg-gray-100">
                            <i class="ti ti-menu-2 text-2xl"></i>
                        </button>
                        @endif
                        <h1 class="text-lg font-semibold text-gray-800 hidden sm:block">
                            @yield('header_title', 'Grand Santhi Coffee Shop')
                        </h1>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600 hidden md:block">Halo, <b>{{ Auth::user()->name }}</b></span>

                        <div class="relative">
                            <button id="userMenuButton" data-dropdown-toggle="userDropdown" class="flex items-center focus:outline-none">
                                <img src="{{ asset('assets/images/profile/user-1.jpg') }}" class="w-9 h-9 rounded-full border border-gray-200 shadow-sm" alt="User" />
                            </button>

                            <div id="userDropdown" class="hidden z-50 w-64 bg-white divide-y divide-gray-100 rounded-lg shadow-xl border border-gray-100 mt-2">
                                <div class="px-4 py-3">
                                    <p class="text-sm text-gray-900 font-bold">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    <button id="logoutBtn" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="ti ti-logout"></i>
                                        <span id="logoutText">Logout</span>
                                        <svg id="logoutSpinner" class="hidden w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </header>
        @endif

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    @if($showSidebar)
    <div id="sidebarOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden hidden backdrop-blur-sm transition-opacity"></div>
    @endif

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.3/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

<script>
    $(document).ready(function() {
        const sidebar = $('#sidebar');
        const overlay = $('#sidebarOverlay');

        if ($(window).width() >= 1024) {
           sidebar.removeClass('-translate-x-full');
       } else {
           sidebar.addClass('-translate-x-full');
       }

       $('#sidebarToggle, #sidebarClose, #sidebarOverlay').click(function() {
        sidebar.toggleClass('-translate-x-full');
        overlay.toggleClass('hidden');
    });
       $(window).resize(function() {
        if ($(window).width() >= 1024) {
            sidebar.removeClass('-translate-x-full');
            overlay.addClass('hidden');
        } else {
            sidebar.addClass('-translate-x-full'); 
        }
    });
   });

    const logoutBtn = document.getElementById('logoutBtn');
    if(logoutBtn){
        logoutBtn.addEventListener('click', async () => {
            const btn = logoutBtn;
            const spinner = document.getElementById('logoutSpinner');
            const text = document.getElementById('logoutText');

            btn.disabled = true;
            spinner.classList.remove('hidden');
            text.textContent = 'Keluar...';

            try {
                const response = await fetch("{{ route('logout') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Accept': 'application/json' }
                });

                if (response.ok) {
                    window.location.href = "{{ route('login') }}";
                } else { throw await response.json(); }
            } catch (error) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Gagal logout', showConfirmButton: false, timer: 3000 });
                btn.disabled = false;
                spinner.classList.add('hidden');
                text.textContent = 'Logout';
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        if (localStorage.getItem('sidebar-mini') === 'true') {
            $('body').addClass('mini-sidebar');
        }

        $('#desktopSidebarToggle').click(function() {
            $('body').toggleClass('mini-sidebar');
            
            if ($('body').hasClass('mini-sidebar')) {
                localStorage.setItem('sidebar-mini', 'true');
            } else {
                localStorage.setItem('sidebar-mini', 'false');
            }
        });
    });
</script>
@yield('js')
</body>
</html>