@extends('layouts.app')
@section('title', 'Scan Meja - Grand Santhi')
@section('css')
<style>
    /* Paksa elemen video dari library agar memenuhi kotak (cover) */
    #reader video {
        object-fit: cover !important;
        width: 100% !important;
        height: 100% !important;
        /* Opsional: Membalik kamera jika terlihat seperti cermin (mirror) */
        /* transform: scaleX(-1); */ 
    }
</style>
@endsection
@section('content')
<div class="min-h-screen bg-gray-900 flex flex-col items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute top-0 left-0 w-full h-1/2 bg-[#014421] rounded-b-[3rem] z-0"></div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">

        <div class="p-6 text-center">
            <h1 class="text-xl font-bold text-gray-800">Scan QR Code Meja</h1>
            <p class="text-sm text-gray-500 mt-1">Arahkan kamera ke QR Code yang ada di meja.</p>
        </div>

        <div class="relative w-full max-w-sm mx-auto aspect-square bg-black rounded-xl overflow-hidden shadow-inner border border-gray-700">

            <div id="reader" class="w-full h-full"></div>

            <div class="absolute inset-0 z-10 pointer-events-none p-8">
                <div class="w-full h-full border-2 border-[#014421]/60 rounded-lg relative">
                    <div class="absolute top-0 left-0 w-6 h-6 border-t-4 border-l-4 border-[#014421] -mt-1 -ml-1"></div>
                    <div class="absolute top-0 right-0 w-6 h-6 border-t-4 border-r-4 border-[#014421] -mt-1 -mr-1"></div>
                    <div class="absolute bottom-0 left-0 w-6 h-6 border-b-4 border-l-4 border-[#014421] -mb-1 -ml-1"></div>
                    <div class="absolute bottom-0 right-0 w-6 h-6 border-b-4 border-r-4 border-[#014421] -mb-1 -mr-1"></div>
                </div>
            </div>

            <div id="loadingMessage" class="absolute inset-0 flex items-center justify-center z-0">
                <span class="text-white text-sm font-medium animate-pulse">Menyiapkan Kamera...</span>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div id="feedbackArea" class="hidden p-3 rounded-lg text-sm text-center font-bold"></div>

            <div class="text-center">
                <p class="text-xs text-gray-400 mb-2">Kamera bermasalah? Masukkan nomor meja manual</p>
                <form onsubmit="handleManualSubmit(event)" class="flex gap-2">
                    <input type="number" id="manualTableInput" placeholder="No. Meja (misal: 12)" 
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-[#014421] focus:border-[#014421]">
                    <button type="submit" class="!bg-[#014421] text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-green-900 transition-colors">
                        Masuk
                    </button>
                </form>
            </div>
            
            <a href="{{ route('dashboard') }}" class="block text-center text-sm text-gray-500 hover:text-[#014421] mt-4">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let html5QrCode; 

    // 1. Fungsi saat Scan Berhasil
    function onScanSuccess(decodedText, decodedResult) {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                console.log("Camera stopped");
                showFeedback('Memvalidasi Meja...', 'blue');
                validateTable(decodedText);
            }).catch(err => {
                console.log("Stop failed: ", err);
            });
        }
    }

    // 2. Fungsi saat Scan Gagal (Opsional, biarkan kosong agar console bersih)
    function onScanFailure(error) {
        // console.warn(`Code scan error = ${error}`);
    }

    // 3. Inisialisasi Kamera saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        
        const loadingMsg = document.getElementById('loadingMessage');
        
        // Gunakan Html5Qrcode (Core Library) agar sesuai dengan UI Custom Anda
        // JANGAN pakai Html5QrcodeScanner
        html5QrCode = new Html5Qrcode("reader");

        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 }
            // aspectRatio jangan dipasang, biar CSS yang handle (object-fit: cover)
        };
        
        // Mulai Kamera Belakang (Environment)
        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
        .then(() => {
            // Jika kamera berhasil nyala, sembunyikan loading text
            if(loadingMsg) loadingMsg.style.display = 'none';
        })
        .catch(err => {
            // Jika gagal (izin ditolak atau tidak ada kamera)
            if(loadingMsg) loadingMsg.innerHTML = '<span class="text-red-500">Kamera tidak dapat diakses</span>';
            console.error("Error starting camera", err);
            
            Swal.fire({
                icon: 'error',
                title: 'Akses Kamera Ditolak',
                text: 'Pastikan Anda mengizinkan akses kamera dan mengakses web via HTTPS (atau Localhost).',
            });
        });
    });

    // 4. Fungsi Validasi ke Backend (AJAX)
    async function validateTable(qrData) {
        try {
            const response = await fetch("{{ route('customer.scan.validate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ qr_data: qrData })
            });

            const result = await response.json();

            if (response.ok) {
                showFeedback(`Meja ${result.table_number} Terkonfirmasi!`, 'green');

                // Simpan nomor meja di session browser (opsional)
                sessionStorage.setItem('table_number', result.table_number);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: `Anda berada di Meja ${result.table_number}`,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "{{ route('dashboard') }}";
                });

            } else {
                throw new Error(result.message);
            }

        } catch (error) {
            showFeedback(error.message, 'red');
            
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message,
                confirmButtonText: 'Scan Ulang',
                confirmButtonColor: '#014421'
            }).then(() => {
                // Reload halaman untuk merestart kamera
                window.location.reload();
            });
        }
    }

    // 5. Helper: Menampilkan Feedback Text
    function showFeedback(message, type) {
        const el = document.getElementById('feedbackArea');
        el.classList.remove('hidden', 'bg-red-100', 'text-red-700', 'bg-green-100', 'text-green-700', 'bg-blue-100', 'text-blue-700');

        if(type === 'red') el.classList.add('bg-red-100', 'text-red-700');
        if(type === 'green') el.classList.add('bg-green-100', 'text-green-700');
        if(type === 'blue') el.classList.add('bg-blue-100', 'text-blue-700');

        el.innerText = message;
        el.classList.remove('hidden');
    }

    // 6. Handle Manual Input Form
    function handleManualSubmit(e) {
        e.preventDefault();
        const val = document.getElementById('manualTableInput').value;
        if(val) {
            validateTable(val);
        }
    }
</script>
@endsection