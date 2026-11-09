@extends('layouts.app', ['title' => 'Dimstore - Kontak Kami'])

@section('content')
  @php
    use Illuminate\Support\Str;

    // Ambil setting terbaru
    $site = \App\Models\SiteSetting::query()->latest('id')->first();

    $siteName = $site->site_name ?? 'Dimstore';
    $wa       = $site->whatsapp ?? '089691106601';
    $ig       = $site->instagram ?? null;

    $waDigits = preg_replace('/\D+/', '', (string) $wa);
    // kalau nomor diawali 0, ubah ke 62 (Indonesia)
    if (Str::startsWith($waDigits, '0')) {
      $waDigits = '62' . substr($waDigits, 1);
    }

    $waUrl = $waDigits ? "https://wa.me/{$waDigits}" : null;

    $igUser = $ig ? ltrim(trim($ig), '@') : null;
    $igUrl  = $igUser ? "https://instagram.com/{$igUser}" : null;
  @endphp

  <style>
    /* tombol abu-abu konsisten */
    .btn-gray {
      @apply bg-gray-700 hover:bg-gray-800 text-white;
    }
    .btn-gray-soft {
      @apply bg-gray-600 hover:bg-gray-700 text-white;
    }
  </style>

  <!-- Hero Section with Brand Colors -->
  <section class="bg-gradient-to-br from-red-600 via-black to-red-800 py-16">
    <div class="max-w-7xl mx-auto px-4 text-center">
      <div class="inline-block bg-yellow-400 text-black px-4 py-2 rounded-full text-sm font-bold mb-4">
        💬 Hubungi Kami
      </div>
      <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">
        Kontak <span class="text-yellow-400">{{ $siteName }}</span>
      </h1>
      <p class="text-xl text-gray-200 max-w-2xl mx-auto">
        Kami siap membantu Anda 24/7! Hubungi kami melalui WhatsApp atau Instagram untuk mendapatkan respon cepat.
      </p>
    </div>
  </section>

  <!-- Main Contact Section -->
  <section class="max-w-6xl mx-auto px-4 py-16 -mt-8">
    <!-- Quick Contact Buttons -->
    <div class="bg-white rounded-3xl shadow-2xl p-8 mb-8 border border-gray-100">
      <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-black mb-2">Pilih Platform Favorit Anda</h2>
        <p class="text-gray-600">Klik tombol di bawah untuk langsung terhubung dengan kami</p>
      </div>

      <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-lg mx-auto">
        @if($waUrl)
          <a href="{{ $waUrl }}" target="_blank" rel="noopener"
             class="group flex-1 btn-gray rounded-2xl px-6 py-4 font-bold transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
            <div class="flex items-center justify-center gap-3">
              <div class="bg-white/15 rounded-full p-2 group-hover:bg-white/20 transition-colors">
                {{-- ✅ WhatsApp Logo (lebih akurat) --}}
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M20.52 3.48A11.79 11.79 0 0 0 12.04 0C5.44 0 .1 5.33.1 11.9c0 2.1.55 4.15 1.6 5.97L0 24l6.28-1.65a11.88 11.88 0 0 0 5.76 1.47h.01c6.6 0 11.94-5.33 11.94-11.9 0-3.18-1.24-6.17-3.47-8.44ZM12.04 21.8h-.01a9.87 9.87 0 0 1-5.03-1.38l-.36-.21-3.73.98 1-3.63-.24-.37a9.86 9.86 0 0 1-1.5-5.29c0-5.46 4.46-9.9 9.95-9.9 2.66 0 5.16 1.03 7.04 2.9a9.86 9.86 0 0 1 2.92 7c0 5.46-4.46 9.9-9.95 9.9Zm5.77-7.38c-.32-.16-1.88-.93-2.17-1.04-.29-.11-.5-.16-.72.16-.21.32-.83 1.04-1.02 1.25-.19.21-.37.24-.69.08-.32-.16-1.34-.49-2.55-1.56-.94-.84-1.57-1.88-1.76-2.2-.19-.32-.02-.49.14-.65.14-.14.32-.37.48-.56.16-.19.21-.32.32-.53.11-.21.05-.4-.03-.56-.08-.16-.72-1.74-.98-2.38-.26-.62-.52-.54-.72-.55l-.61-.01c-.21 0-.56.08-.85.4-.29.32-1.12 1.09-1.12 2.65 0 1.56 1.15 3.06 1.31 3.27.16.21 2.26 3.45 5.48 4.83.77.33 1.37.53 1.84.68.77.24 1.47.21 2.03.13.62-.09 1.88-.77 2.14-1.5.26-.74.26-1.37.18-1.5-.08-.13-.29-.21-.61-.37Z"/>
                </svg>
              </div>
              <span class="text-lg">WhatsApp</span>
            </div>
          </a>
        @endif

        @if($igUrl)
          <a href="{{ $igUrl }}" target="_blank" rel="noopener"
             class="group flex-1 btn-gray rounded-2xl px-6 py-4 font-bold transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
            <div class="flex items-center justify-center gap-3">
              <div class="bg-white/15 rounded-full p-2 group-hover:bg-white/20 transition-colors">
                {{-- Instagram icon --}}
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M7 2C4.24 2 2 4.24 2 7v10c0 2.76 2.24 5 5 5h10c2.76 0 5-2.24 5-5V7c0-2.76-2.24-5-5-5H7zm10 2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h10zm-5 3.5A4.5 4.5 0 1 0 16.5 12 4.5 4.5 0 0 0 12 7.5zm0 2A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5zM17.75 6a.75.75 0 1 0 .75.75.75.75 0 0 0-.75-.75z"/>
                </svg>
              </div>
              <span class="text-lg">Instagram</span>
            </div>
          </a>
        @endif
      </div>
    </div>

    <!-- Detailed Contact Info -->
    <div class="grid md:grid-cols-2 gap-6">
      <!-- WhatsApp Card -->
      <div class="bg-white rounded-3xl shadow-xl p-8 border-l-4 border-gray-600 hover:shadow-2xl transition-all duration-300 group">
        <div class="flex items-start gap-4">
          <div class="bg-gray-100 rounded-2xl p-3 group-hover:bg-gray-200 transition-colors">
            {{-- ✅ WhatsApp Logo (lebih akurat) --}}
            <svg class="w-8 h-8 text-gray-800" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M20.52 3.48A11.79 11.79 0 0 0 12.04 0C5.44 0 .1 5.33.1 11.9c0 2.1.55 4.15 1.6 5.97L0 24l6.28-1.65a11.88 11.88 0 0 0 5.76 1.47h.01c6.6 0 11.94-5.33 11.94-11.9 0-3.18-1.24-6.17-3.47-8.44ZM12.04 21.8h-.01a9.87 9.87 0 0 1-5.03-1.38l-.36-.21-3.73.98 1-3.63-.24-.37a9.86 9.86 0 0 1-1.5-5.29c0-5.46 4.46-9.9 9.95-9.9 2.66 0 5.16 1.03 7.04 2.9a9.86 9.86 0 0 1 2.92 7c0 5.46-4.46 9.9-9.95 9.9Zm5.77-7.38c-.32-.16-1.88-.93-2.17-1.04-.29-.11-.5-.16-.72.16-.21.32-.83 1.04-1.02 1.25-.19.21-.37.24-.69.08-.32-.16-1.34-.49-2.55-1.56-.94-.84-1.57-1.88-1.76-2.2-.19-.32-.02-.49.14-.65.14-.14.32-.37.48-.56.16-.19.21-.32.32-.53.11-.21.05-.4-.03-.56-.08-.16-.72-1.74-.98-2.38-.26-.62-.52-.54-.72-.55l-.61-.01c-.21 0-.56.08-.85.4-.29.32-1.12 1.09-1.12 2.65 0 1.56 1.15 3.06 1.31 3.27.16.21 2.26 3.45 5.48 4.83.77.33 1.37.53 1.84.68.77.24 1.47.21 2.03.13.62-.09 1.88-.77 2.14-1.5.26-.74.26-1.37.18-1.5-.08-.13-.29-.21-.61-.37Z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-xl font-bold text-black mb-2">WhatsApp</h3>
            <p class="text-gray-600 text-sm mb-3">Chat langsung dengan tim support kami</p>
            <div class="bg-gray-50 rounded-xl p-4 mb-4">
              <p class="font-mono text-lg text-black">{{ $wa }}</p>
            </div>
            @if($waUrl)
              <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                 class="inline-flex items-center gap-2 btn-gray-soft px-6 py-3 rounded-full font-bold transition-all transform hover:scale-105">
                <span>💬 Chat Sekarang</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
              </a>
            @endif
          </div>
        </div>
      </div>

      <!-- Instagram Card -->
      <div class="bg-white rounded-3xl shadow-xl p-8 border-l-4 border-gray-600 hover:shadow-2xl transition-all duration-300 group">
        <div class="flex items-start gap-4">
          <div class="bg-gray-100 rounded-2xl p-3 group-hover:bg-gray-200 transition-colors">
            <svg class="w-8 h-8 text-gray-800" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M7 2C4.24 2 2 4.24 2 7v10c0 2.76 2.24 5 5 5h10c2.76 0 5-2.24 5-5V7c0-2.76-2.24-5-5-5H7zm10 2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h10zm-5 3.5A4.5 4.5 0 1 0 16.5 12 4.5 4.5 0 0 0 12 7.5zm0 2A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5zM17.75 6a.75.75 0 1 0 .75.75.75.75 0 0 0-.75-.75z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-xl font-bold text-black mb-2">Instagram</h3>
            <p class="text-gray-600 text-sm mb-3">Follow dan DM kami untuk update terbaru</p>
            <div class="bg-gray-50 rounded-xl p-4 mb-4">
              <p class="font-mono text-lg text-black">{{ $igUser ? '@'.$igUser : 'Belum tersedia' }}</p>
            </div>
            @if($igUrl)
              <a href="{{ $igUrl }}" target="_blank" rel="noopener"
                 class="inline-flex items-center gap-2 btn-gray-soft px-6 py-3 rounded-full font-bold transition-all transform hover:scale-105">
                <span>📸 Buka Profil</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Additional Info -->
    <div class="mt-12 bg-gradient-to-r from-yellow-50 to-red-50 rounded-3xl p-8 border border-yellow-200">
      <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-400 rounded-full mb-4">
          <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-black mb-2">Respon Cepat Guaranteed!</h3>
        <p class="text-gray-700 text-lg max-w-2xl mx-auto leading-relaxed">
          Tim customer service <span class="font-bold text-red-600">{{ $siteName }}</span> siap membantu Anda dengan respon dalam
          <span class="bg-yellow-200 px-2 py-1 rounded font-bold">maksimal 2 jam</span> pada jam kerja.
          Untuk pertanyaan mendesak, langsung chat WhatsApp kami!
        </p>
        <div class="mt-6 flex flex-wrap justify-center gap-4 text-sm">
          <div class="bg-white rounded-full px-4 py-2 border border-gray-200">
            <span class="text-green-600 font-bold">✓</span> Gratis Konsultasi
          </div>
          <div class="bg-white rounded-full px-4 py-2 border border-gray-200">
            <span class="text-green-600 font-bold">✓</span> Support 24/7
          </div>
          <div class="bg-white rounded-full px-4 py-2 border border-gray-200">
            <span class="text-green-600 font-bold">✓</span> Respon Cepat
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
