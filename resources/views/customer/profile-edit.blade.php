@extends('layouts.app')
@section('title', 'Edit Profil - Grand Santhi Coffee')

@section('content')
<div class="min-h-screen bg-gray-50 pb-28 relative">

    {{-- Edit Profile Header --}}
    <div class="bg-white sticky top-0 z-20 shadow-sm border-b border-gray-100">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('customer.profile') }}" class="p-2 -ml-2 rounded-full text-gray-600 hover:bg-gray-50 hover:text-[#014421] transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-xl font-bold text-[#014421]">Edit Profil</h1>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 mt-4 space-y-4">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-center mb-5">
                <div class="relative">
                    <img id="avatarPreview" src="{{ Auth::user()->photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=014421&color=fff&size=128' }}" 
                         alt="Avatar" 
                         class="w-20 h-20 rounded-full border-2 border-white shadow-md object-cover">
                    <label for="profilePhotoInput" class="absolute bottom-0 right-0 bg-[#014421] w-7 h-7 rounded-full flex items-center justify-center text-white shadow-md hover:bg-green-800 transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </label>
                    <input type="file" id="profilePhotoInput" name="profile_photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                </div>
            </div>

            <form id="profileForm" class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ Auth::user()->name }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all"
                           placeholder="Masukkan nama lengkap">
                    <p class="text-[10px] text-red-500 mt-1 hidden" id="nameError"></p>
                </div>

                <div>
                    <label for="full_name" class="block text-xs font-bold text-gray-700 mb-1.5">Nama Panggilan</label>
                    <input type="text" id="full_name" name="full_name" value="{{ Auth::user()->full_name }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all"
                           placeholder="Masukkan nama panggilan">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ Auth::user()->email }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all"
                           placeholder="Masukkan email">
                    <p class="text-[10px] text-red-500 mt-1 hidden" id="emailError"></p>
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-gray-700 mb-1.5">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone" value="{{ Auth::user()->phone }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all"
                           placeholder="Contoh: 081234567890">
                </div>

                <button type="submit" id="btnSave" class="w-full !bg-[#014421] hover:bg-green-900 text-white py-3.5 rounded-full font-bold shadow-lg shadow-green-900/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed text-sm">
                    <span id="btnSaveText">Simpan Perubahan</span>
                    <svg id="btnSaveSpinner" class="hidden w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"></path></svg>
                </button>
            </form>
        </div>

        <br>
    </div>

    <x-customer.bottom-nav active="profil" />

</div>
@endsection

@section('js')
<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#avatarPreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#profileForm').on('submit', function(e) {
        e.preventDefault();

        $('#nameError').addClass('hidden').text('');
        $('#emailError').addClass('hidden').text('');

        const btn = $('#btnSave');
        const btnText = $('#btnSaveText');
        const btnSpinner = $('#btnSaveSpinner');

        btn.prop('disabled', true);
        btnText.text('Menyimpan...');
        btnSpinner.removeClass('hidden');

        const formData = new FormData();
        formData.append('name', $('#name').val().trim());
        formData.append('full_name', $('#full_name').val().trim());
        formData.append('email', $('#email').val().trim());
        formData.append('phone', $('#phone').val().trim());

        const photoInput = document.getElementById('profilePhotoInput');
        if (photoInput.files.length > 0) {
            formData.append('profile_photo', photoInput.files[0]);
        }
        formData.append('_method', 'PUT');

        $.ajax({
            url: '{{ route("customer.profile.update") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(res) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: res.message || 'Profil berhasil diperbarui',
                    showConfirmButton: false,
                    timer: 2000
                });
                setTimeout(() => window.location.href = '{{ route("customer.profile") }}', 1500);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.name) {
                        $('#nameError').removeClass('hidden').text(errors.name[0]);
                    }
                    if (errors.email) {
                        $('#emailError').removeClass('hidden').text(errors.email[0]);
                    }
                } else {
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'error',
                        title: xhr.responseJSON?.message || 'Gagal menyimpan',
                        showConfirmButton: false, timer: 3000
                    });
                }
            },
            complete: function() {
                btn.prop('disabled', false);
                btnText.text('Simpan Perubahan');
                btnSpinner.addClass('hidden');
            }
        });
    });
</script>
@endsection
