{{-- resources/views/front/terms.blade.php --}}
@extends('layouts.app')

@php
  $title = 'Syarat & Ketentuan - Dimstore';
@endphp

@section('content')
  <div class="max-w-7xl mx-auto px-4 py-10">
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
      <div class="p-6 md:p-8 border-b bg-slate-50">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Syarat & Ketentuan</h1>
        <p class="mt-2 text-sm text-slate-600">
          Dengan menggunakan layanan <span class="font-bold">Dimstore</span>, Anda dianggap telah membaca, memahami, dan menyetujui Syarat & Ketentuan di bawah ini.
        </p>
      </div>

      <div class="p-6 md:p-8 prose max-w-none">
        <h2 class="text-lg font-extrabold text-slate-900">1. Definisi</h2>
        <ul class="list-disc pl-5 text-slate-700">
          <li><b>Dimstore</b>: Website penyedia layanan top up game dan produk digital.</li>
          <li><b>Pelanggan</b>: Pengguna yang melakukan pemesanan di Dimstore.</li>
          <li><b>Produk</b>: Item digital seperti diamonds, voucher, pulsa, dan layanan digital lainnya.</li>
        </ul>

        <h2 class="text-lg font-extrabold text-slate-900 mt-6">2. Ketentuan Umum</h2>
        <ul class="list-disc pl-5 text-slate-700">
          <li>Pelanggan wajib mengisi data pesanan dengan benar (User ID/Server/Nickname jika diperlukan).</li>
          <li>Dimstore berhak menolak pesanan apabila terindikasi penipuan atau melanggar ketentuan.</li>
          <li>Harga dapat berubah sewaktu-waktu mengikuti kebijakan dan harga dari penyedia.</li>
        </ul>

        <h2 class="text-lg font-extrabold text-slate-900 mt-6">3. Proses Pesanan</h2>
        <ul class="list-disc pl-5 text-slate-700">
          <li>Pesanan diproses setelah pembayaran terkonfirmasi.</li>
          <li>Estimasi waktu proses bervariasi (umumnya cepat), namun dapat terlambat karena gangguan sistem/provider.</li>
          <li>Jika terjadi kendala, pelanggan dapat menghubungi kontak resmi Dimstore.</li>
        </ul>

        <h2 class="text-lg font-extrabold text-slate-900 mt-6">4. Kesalahan Data Pelanggan</h2>
        <div class="rounded-xl border bg-amber-50 p-4 text-slate-700">
          <p class="m-0">
            Jika pelanggan memasukkan <b>User ID/Server</b> yang salah, maka pesanan yang sudah diproses
            <b>tidak dapat dibatalkan</b> dan <b>tidak dapat direfund</b>.
          </p>
        </div>

        <h2 class="text-lg font-extrabold text-slate-900 mt-6">5. Pembayaran</h2>
        <ul class="list-disc pl-5 text-slate-700">
          <li>Pelanggan wajib membayar sesuai nominal pesanan yang tertera.</li>
          <li>Jika pembayaran tidak sesuai atau tidak lengkap, pesanan dapat tertunda atau dibatalkan.</li>
          <li>Bukti pembayaran dapat diminta apabila diperlukan untuk verifikasi.</li>
        </ul>

        <h2 class="text-lg font-extrabold text-slate-900 mt-6">6. Refund & Pembatalan</h2>
        <ul class="list-disc pl-5 text-slate-700">
          <li>Refund hanya berlaku apabila transaksi gagal dan produk belum masuk/terkirim.</li>
          <li>Jika produk sudah terkirim, tidak dapat dilakukan refund.</li>
          <li>Untuk kasus tertentu, Dimstore dapat menawarkan solusi pengganti sesuai kebijakan.</li>
        </ul>

        <h2 class="text-lg font-extrabold text-slate-900 mt-6">7. Gangguan Sistem</h2>
        <ul class="list-disc pl-5 text-slate-700">
          <li>Dimstore tidak bertanggung jawab atas keterlambatan akibat gangguan provider/maintenance server game.</li>
          <li>Jika terjadi downtime, pesanan akan diproses kembali setelah sistem normal.</li>
        </ul>

        <h2 class="text-lg font-extrabold text-slate-900 mt-6">8. Keamanan Akun</h2>
        <ul class="list-disc pl-5 text-slate-700">
          <li>Jangan pernah membagikan OTP/kode verifikasi kepada siapa pun.</li>
          <li>Dimstore tidak meminta password akun game pelanggan.</li>
        </ul>

        <h2 class="text-lg font-extrabold text-slate-900 mt-6">9. Perubahan Syarat</h2>
        <p class="text-slate-700">
          Dimstore dapat memperbarui Syarat & Ketentuan kapan saja. Perubahan akan berlaku saat ditampilkan pada halaman ini.
        </p>

        <h2 class="text-lg font-extrabold text-slate-900 mt-6">10. Kontak</h2>
        <p class="text-slate-700">
          Jika ada pertanyaan terkait Syarat & Ketentuan, silakan hubungi kami melalui halaman <b>Kontak Kami</b>.
        </p>

        <div class="mt-8 flex flex-wrap gap-3">
          <a href="{{ url('/kontak') }}"
             class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition">
            Ke Halaman Kontak
          </a>
          <a href="{{ url('/') }}"
             class="inline-flex items-center px-4 py-2 rounded-xl border font-bold hover:bg-slate-50 transition">
            Kembali ke Home
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
