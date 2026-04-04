@extends('layouts.app')
@section('title', 'Login - Grand Santhi Coffee')

@section('content')
<div class="flex items-center justify-center min-h-[80vh]">
    
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-8 transform transition-all hover:shadow-2xl">
        
        <div class="text-center mb-8">
            <img src="{{ asset('assets/images/logos/logo_grandsanthi.png') }}" alt="Logo" class="h-16 mx-auto mb-4 drop-shadow-sm">
            <h1 class="text-2xl font-bold text-[#014421]">Selamat Datang</h1>
            <p class="text-sm text-gray-500 mt-2">
                Silakan masuk untuk melanjutkan
            </p>
        </div>

        <form id="loginForm" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Email Address
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input type="email" name="email" required
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#014421] focus:border-transparent text-sm transition-all bg-gray-50 focus:bg-white"
                        placeholder="nama@email.com">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Password
                    </label>
                    <a href="#" class="!text-[#014421] text-xs font-medium  hover:underline">Lupa Password?</a>
                </div>
                
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    
                    <input type="password" name="password" id="passwordInput" required
                        class="w-full pl-10 pr-12 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#014421] focus:border-transparent text-sm transition-all bg-gray-50 focus:bg-white"
                        placeholder="••••••••">

                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-5 pr-3 flex items-center text-gray-400 hover:text-[#014421] focus:outline-none">
                        <svg id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eyeSlashIcon" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.574-2.59M6 6l12 12" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.88 9.88a3 3 0 104.24 4.24" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" id="loginBtn"
                class="w-full !bg-[#014421] hover:bg-[#013519] active:scale-[0.98] py-3.5 text-white rounded-xl font-bold text-sm tracking-wide transition-all shadow-lg shadow-green-900/20 flex items-center justify-center gap-2 mt-4">
                
                <svg id="spinner" class="hidden w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"/>
                </svg>

                <span id="btnText">Masuk</span>
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="!text-[#014421] font-bold hover:underline transition-colors">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // --- Logic Toggle Password ---
    function togglePassword() {
        const passwordInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeSlashIcon = document.getElementById('eyeSlashIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeSlashIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeSlashIcon.classList.add('hidden');
        }
    }

    // --- Logic Login Process ---
    const form = document.getElementById('loginForm');
    const btn = document.getElementById('loginBtn');
    const spinner = document.getElementById('spinner');
    const btnText = document.getElementById('btnText');

    if(form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Loading state
            btn.disabled = true;
            spinner.classList.remove('hidden');
            btnText.textContent = 'Memproses...';
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            const formData = new FormData(form);

            try {
                const response = await fetch("{{ route('login') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: result.message || 'Login berhasil',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });

                    setTimeout(() => {
                        window.location.href = result.redirect_url; 
                    }, 1000);

                } else {
                    throw result;
                }

            } catch (error) {
                console.error(error);
                let errorMessage = 'Email atau password salah';
                
                // Menangani error validasi Laravel
                if (error.errors) {
                    errorMessage = Object.values(error.errors).flat().join('\n');
                } else if (error.message) {
                    errorMessage = error.message;
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: errorMessage,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            } finally {
                // Reset button state
                btn.disabled = false;
                spinner.classList.add('hidden');
                btnText.textContent = 'MASUK';
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });
    }
</script>
@endsection