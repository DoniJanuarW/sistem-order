# Grand Santhi - Coffee Shop Ordering System

Sistem manajemen restoran berbasis web yang mencakup fitur POS untuk Kasir, Self-Service Order untuk Pelanggan (QR Code), dan Dashboard Analitik untuk Admin/Owner.

## 📋 Fitur Utama

- **Admin/Master:** Dashboard analitik, manajemen menu, laporan keuangan, export CSV, cetak QR Meja (PDF).
- **Cashier (POS):** Terima pesanan, proses pembayaran (Cash/QRIS/Transfer), status meja real-time.
- **Customer:** Scan QR meja, lihat menu, tambah ke keranjang, checkout mandiri.

## 🛠️ Persyaratan Sistem (Prerequisites)

Pastikan komputer Anda sudah terinstal:

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / MariaDB

---

## 🚀 Panduan Instalasi (Step-by-Step)

### 1. Clone & Setup Project

Buka terminal (Git Bash/CMD/Terminal) dan jalankan perintah berikut:

#### 1. Clone repository (jika ada) atau masuk ke folder project

```bash
git clone https://github.com/username/grand-santhi-order.git
cd grand-santhi-order
```

##### 2. Install dependency PHP (Laravel)

```bash
composer install
```

#### 3. Install dependency JavaScript (Tailwind, dll)

```bash
npm install
```

### 2. Konfigurasi Environment (.env)

Duplikat file `.env.example` menjadi `.env`:

```bash
cp .env.example .env

```

Buka file `.env` dan sesuaikan konfigurasi database:

```ini
APP_NAME="Grand Santhi"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_grand_santhi  # Pastikan buat database ini di phpMyAdmin
DB_USERNAME=root
DB_PASSWORD=

```

### 3. Generate Key & Storage Link

```bash
# Generate App Key
php artisan key:generate

# Link folder storage agar gambar menu/QR bisa diakses publik
php artisan storage:link

```

### 4. Database Migration & Seeder

Pastikan database `db_grand_santhi` sudah dibuat di MySQL, lalu jalankan:

```bash
php artisan migrate --seed

```

---

## ▶️ Cara Menjalankan Aplikasi

Anda perlu membuka **dua terminal** berbeda agar aplikasi berjalan lancar.

**Terminal 1 (Menjalankan Server Laravel):**

```bash
php artisan serve

```

_Akses di browser: `http://localhost:8000_`

**Terminal 2 (Compile Asset / Tailwind):**

```bash
npm run dev

```

---

## 🔑 Akun Default (Seeder)

Gunakan akun berikut untuk masuk ke sistem (jika sudah di-seed):

| Role         | Email                   | Password         |
| ------------ | ----------------------- | ---------------- |
| **Admin**    | `admin@grandsanthi.com` | `password`       |
| **Cashier**  | `kasir@grandsanthi.com` | `password`       |
| **Customer** | _(Register Sendiri)_    | _(Sesuai input)_ |

---

## 🐛 Troubleshooting Umum

**1. Error "DomPDF / Vendor does not exist" di Windows**
Biasanya karena folder terkunci.

- Matikan `php artisan serve`.
- Tutup VS Code sebentar.
- Jalankan `composer install` lagi.

**2. Gambar tidak muncul**
Pastikan Anda sudah menjalankan `php artisan storage:link`.

**3. Tampilan berantakan (CSS tidak load)**
Pastikan `npm run dev` sedang berjalan di terminal terpisah.
