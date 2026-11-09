# 🎮 Top Up Game App (Dimstore)

Aplikasi layanan *top-up* game berbasis *website*, dibangun menggunakan *framework* **Laravel** dan **Filament PHP** untuk panel adminnya. Aplikasi ini juga telah terintegrasi dengan **Midtrans** sebagai *Payment Gateway* untuk memproses transaksi secara otomatis.

## 🚀 Fitur Utama
*   **Katalog Game & Denominasi**: Manajemen daftar game, kategori, dan pilihan nominal top-up yang mudah melalui panel admin.
*   **Payment Gateway Terintegrasi**: Pembayaran otomatis dan *real-time* menggunakan Midtrans (QRIS, *Virtual Account* Bank, E-Wallet).
*   **Manajemen Pesanan (Order)**: Pengecekan status pesanan dan pencatatan riwayat transaksi pengguna lengkap dengan pembuatan kode pesanan (Invoice).
*   **Admin Panel (Filament)**: Antarmuka yang modern dan responsif untuk mengelola kategori, produk, metode pembayaran, banner promo, dan pengaturan situs.
*   **Jadwal Gift Skin**: Fitur khusus untuk penjadwalan dan *review* pengiriman *gift skin* (khususnya untuk game MLBB).

## 🛠️ Teknologi yang Digunakan
*   **Backend / Framework**: [Laravel](https://laravel.com/)
*   **Admin Panel**: [Filament v3](https://filamentphp.com/)
*   **Payment Gateway**: [Midtrans](https://midtrans.com/)
*   **Database**: MySQL
*   **Frontend**: Laravel Blade + Tailwind CSS

## 💻 Cara Instalasi (Development Lokal)

Ikuti langkah-langkah berikut untuk menjalankan aplikasi ini di komputer lokal (menggunakan Laragon / XAMPP):

1. **Clone repository ini:**
   ```bash
   git clone [https://github.com/pirangskuy/topupgame.git](https://github.com/pirangskuy/topupgame.git)
   cd topupgame/dimstore
