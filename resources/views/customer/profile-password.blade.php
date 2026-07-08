@extends('layouts.app')
@section('title', 'Ganti Password - Grand Santhi Coffee')

@section('content')
<div class="min-h-screen bg-gray-50 pb-28 relative">

    {{-- Ganti Password Header --}}
    <div class="bg-white sticky top-0 z-20 shadow-sm border-b border-gray-100">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('customer.profile') }}" class="p-2 -ml-2 rounded-full text-gray-600 hover:bg-gray-50 hover:text-[#014421] transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-xl font-bold text-[#014421]">Ganti Password</h1>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 mt-4 space-y-4">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-5 p-3 bg-orange-50 rounded-xl border border-orange-100">
                <svg class="w-8 h-8 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <p class="text-xs text-orange-700 leading-relaxed">Gunakan password yang kuat dan tidak mudah ditebak. Minimal 8 karakter dengan kombinasi huruf & angka.</p>
            </div>

            <form id="passwordForm" class="space-y-4">
                <div>
                    <label for="current_password" class="block text-xs font-bold text-gray-700 mb-1.5">Password Saat Ini <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all"
                               placeholder="Masukkan password saat ini">
                        <button type="button" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" data-target="current_password">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    <p class="text-[10px] text-red-500 mt-1 hidden" id="currentPasswordError"></p>
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-bold text-gray-700 mb-1.5">Password Baru <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="new_password" name="new_password"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all"
                               placeholder="Masukkan password baru">
                        <button type="button" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" data-target="new_password">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    <p class="text-[10px] text-red-500 mt-1 hidden" id="newPasswordError"></p>
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-xs font-bold text-gray-700 mb-1.5">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all"
                               placeholder="Ulangi password baru">
                        <button type="button" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" data-target="new_password_confirmation">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    <p class="text-[10px] text-red-500 mt-1 hidden" id="confirmPasswordError"></p>
                </div>

                <button type="submit" id="btnSave" class="w-full !bg-[#014421] hover:bg-green-900 text-white py-3.5 rounded-full font-bold shadow-lg shadow-green-900/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed text-sm">
                    <span id="btnSaveText">Simpan Password Baru</span>
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
    $('.toggle-password').on('click', function() {
        const targetId = $(this).data('target');
        const input = $('#' + targetId);
        const type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
    });

    $('#passwordForm').on('submit', function(e) {
        e.preventDefault();

        $('#currentPasswordError').addClass('hidden').text('');
        $('#newPasswordError').addClass('hidden').text('');
        $('#confirmPasswordError').addClass('hidden').text('');

        const btn = $('#btnSave');
        const btnText = $('#btnSaveText');
        const btnSpinner = $('#btnSaveSpinner');

        btn.prop('disabled', true);
        btnText.text('Menyimpan...');
        btnSpinner.removeClass('hidden');

        const data = {
            current_password: $('#current_password').val(),
            new_password: $('#new_password').val(),
            new_password_confirmation: $('#new_password_confirmation').val(),
        };

        $.ajax({
            url: '{{ route("customer.profile.password") }}',
            method: 'PUT',
            data: JSON.stringify(data),
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(res) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: res.message || 'Password berhasil diubah',
                    showConfirmButton: false,
                    timer: 2000
                });
                $('#passwordForm')[0].reset();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.current_password) {
                        $('#currentPasswordError').removeClass('hidden').text(errors.current_password[0]);
                    }
                    if (errors.new_password) {
                        $('#newPasswordError').removeClass('hidden').text(errors.new_password[0]);
                    }
                    if (errors.new_password_confirmation) {
                        $('#confirmPasswordError').removeClass('hidden').text(errors.new_password_confirmation[0]);
                    }
                } else {
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'error',
                        title: xhr.responseJSON?.message || 'Gagal mengubah password',
                        showConfirmButton: false, timer: 3000
                    });
                }
            },
            complete: function() {
                btn.prop('disabled', false);
                btnText.text('Simpan Password Baru');
                btnSpinner.addClass('hidden');
            }
        });
    });
</script>
@endsection
