@extends('layouts.app')
@section('title', 'Daftar - Grand Santhi Coffee')

@section('content')
<div class="flex items-center justify-center min-h-[80vh]">
  <div class="w-full max-w-[450px] bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">

    <div class="bg-[#014421] h-2"></div>

    <div class="p-8">
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-50 text-[#014421] mb-3">
          <i class="ti ti-user-plus text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Lengkapi data diri Anda untuk bergabung</p>
      </div>

      <form id="registerForm" onsubmit="handleRegister(event)" class="space-y-5">
        <div class="input-group">
          <label class="block text-xs font-bold text-gray-600 uppercase mb-1 ml-1">Nama</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="ti ti-user input-icon text-gray-400 transition-colors"></i>
            </div>
            <input type="text" name="name" id="name" required class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none transition-all text-sm"  placeholder="Contoh: Budi">
          </div>
        </div>

        <div class="input-group">
          <label class="block text-xs font-bold text-gray-600 uppercase mb-1 ml-1">Nama Lengkap</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="ti ti-id input-icon text-gray-400 transition-colors"></i>
            </div>
            <input type="text" name="full_name" id="full_name" required class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none transition-all text-sm"  placeholder="Contoh: Budi Santoso">
          </div>
        </div>

        <div class="input-group">
          <label class="block text-xs font-bold text-gray-600 uppercase mb-1 ml-1">No. Telpon</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="ti ti-phone input-icon text-gray-400 transition-colors"></i>
            </div>
            <input type="text" maxlength="14" name="phone" id="phone" required class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none transition-all text-sm"  placeholder="08899009112">
          </div>
        </div>

        <div class="input-group">
          <label class="block text-xs font-bold text-gray-600 uppercase mb-1 ml-1">Alamat Email</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="ti ti-mail input-icon text-gray-400 transition-colors"></i>
            </div>
            <input type="email" name="email" id="email" required class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none transition-all text-sm" placeholder="nama@email.com">
          </div>
        </div>

        <div class="input-group">
          <label class="block text-xs font-bold text-gray-600 uppercase mb-1 ml-1">Password</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="ti ti-lock input-icon text-gray-400 transition-colors"></i>
            </div>
            <input type="password" name="password" id="password" required class="w-full pl-10 pr-12 py-3 border border-gray-200 rounded-xl focus:outline-none transition-all text-sm" placeholder="••••••••">

            <button type="button" onclick="togglePassword('password', 'eyeIcon1')" class="absolute inset-y-0 right-4 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
              <i id="eyeIcon1" class="ti ti-eye"></i>
            </button>
          </div>
        </div>

        <div class="input-group">
          <label class="block text-xs font-bold text-gray-600 uppercase mb-1 ml-1">Konfirmasi Password</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="ti ti-lock-check input-icon text-gray-400 transition-colors"></i>
            </div>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full pl-10 pr-12 py-3 border border-gray-200 rounded-xl focus:outline-none transition-all text-sm" placeholder="••••••••">

            <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')" class="absolute inset-y-0 right-4 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
              <i id="eyeIcon2" class="ti ti-eye"></i>
            </button>
          </div>
        </div>

        <button type="submit" id="btnSubmit" class="w-full !bg-[#014421] hover:bg-[#016b34] text-white py-3.5 rounded-xl font-bold transition-all shadow-lg shadow-green-900/20 active:scale-[0.98] flex items-center justify-center gap-2 mt-2">
          <span>Daftar Sekarang</span>
          {{-- <i class="ti ti-arrow-right"></i> --}}
        </button>

      </form>

      <p class="text-center text-sm text-gray-500 mt-8">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="!text-[#014421] font-bold hover:underline ml-1">
          Login disini
        </a>
      </p>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
  function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("ti-eye");
      icon.classList.add("ti-eye-off");
    } else {
      input.type = "password";
      icon.classList.remove("ti-eye-off");
      icon.classList.add("ti-eye");
    }
  }

  async function handleRegister(e) {
    e.preventDefault();

    const btn = document.getElementById('btnSubmit');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

    const formData = new FormData(document.getElementById('registerForm'));
    const data = Object.fromEntries(formData.entries());

    try {
      const response = await fetch("{{ route('register') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
      });

      const result = await response.json();

      if (response.ok) {
        Swal.fire({
          icon: 'success',
          title: 'Registrasi Berhasil!',
          text: 'Akun Anda telah dibuat. Mengalihkan...',
          timer: 1500,
          showConfirmButton: false
        }).then(() => {
          window.location.href = "{{ route('dashboard') }}";
        });
      } else {
        let errorMessage = result.message || 'Terjadi kesalahan pada server.';
        if (result.errors) {
          errorMessage = Object.values(result.errors).flat().join('\n');
        }
        throw new Error(errorMessage);
      }

    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal Mendaftar',
        text: error.message,
        confirmButtonColor: '#014421'
      });

      btn.disabled = false;
      btn.innerHTML = originalText;
    }
  }
</script>
@endsection