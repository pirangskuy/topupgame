<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $title ?? 'Dimstore - Top Up Game' }}</title>

  <!-- Tailwind CDN (cepat untuk pemula) -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

{{-- ✅ FIX: body full merah + flex layout biar ga ada gap putih --}}
<body class="min-h-screen bg-[#AC0008] text-slate-900 flex flex-col">

  @php
    use Illuminate\Support\Str;

    // Ambil setting terbaru (kalau record bisa lebih dari 1)
    $site = \App\Models\SiteSetting::query()->latest('id')->first();

    // Helper URL aman untuk file public storage (Laragon/Windows friendly)
    $resolvePublicUrl = function ($path) {
      $path = trim((string) $path);
      if ($path === '') return null;

      $path = str_replace('\\', '/', $path);

      if (Str::startsWith($path, ['http://', 'https://'])) return $path;

      if (Str::startsWith($path, ['/storage/', 'storage/'])) {
        return Str::startsWith($path, '/') ? $path : "/{$path}";
      }

      return "/storage/{$path}";
    };

    $logoUrl   = $site ? $resolvePublicUrl($site->logo ?? null) : null;
    $siteName  = $site->site_name   ?? 'Dimstore';
    $footerText= $site->footer_text ?? 'Dimstore merupakan website Top Up Termurah, tercepat, dan terpercaya untuk layanan games dan entertainment.';
    $wa        = $site->whatsapp    ?? '089691106601';
    $ig        = $site->instagram   ?? null;

    // Optional: kalau nanti kamu mau simpan di DB juga boleh
    // (kalau kolomnya belum ada, ini tetap aman -> hasilnya null)
    $tiktok    = $site->tiktok      ?? null;  // contoh: @dimstore atau dimstore
    $youtube   = $site->youtube     ?? null;  // contoh: https://youtube.com/@dimstore atau dimstore
    $facebook  = $site->facebook    ?? null;  // contoh: dimstore

    $email     = 'dimstore@gmail.com';
    $waDigits  = preg_replace('/\D+/', '', (string) $wa);

    // helper bikin url sosmed dari handle / url
    $makeUrl = function ($value, $type) {
      $value = trim((string) $value);
      if ($value === '') return null;

      if (Str::startsWith($value, ['http://', 'https://'])) return $value;

      // bersihkan @
      $handle = ltrim($value, '@');

      return match ($type) {
        'ig' => "https://instagram.com/{$handle}",
        'fb' => "https://facebook.com/{$handle}",
        'tt' => "https://tiktok.com/@{$handle}",
        'yt' => "https://youtube.com/@{$handle}",
        default => null,
      };
    };

    $igUrl = $makeUrl($ig, 'ig');
    $fbUrl = $makeUrl($facebook, 'fb');
    $ttUrl = $makeUrl($tiktok, 'tt');
    $ytUrl = $makeUrl($youtube, 'yt');

    $helpText = rawurlencode('Halo Admin Dimstore, saya butuh bantuan.');
    $waHelpUrl = "https://wa.me/{$waDigits}?text={$helpText}";
  @endphp

  <!-- HEADER (sticky) - HAMBURGER -->
  {{-- ✅ FIX: header lebih hitam --}}
  <header class="sticky top-0 z-50 bg-[#121212] border-b border-white/10 shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
      <div class="h-16 flex items-center justify-between gap-4">

        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0">
          <img
            src="{{ $logoUrl ?? asset('images/logo.png') }}"
            alt="Logo"
            class="w-10 h-10 rounded-full object-cover"
          >
          <span class="text-white font-extrabold hidden sm:inline">{{ $siteName }}</span>
        </a>

        @php
          $isHome = request()->routeIs('home') || request()->is('/');

          $nav = [
            ['label' => 'Home', 'url' => url('/'), 'active' => $isHome, 'icon' => 'home'],
            ['label' => 'Cek Pesanan', 'url' => url('/cek-pesanan'), 'active' => request()->is('cek-pesanan*'), 'icon' => 'search'],
            ['label' => 'Tools', 'url' => url('/mlbb/tools'), 'active' => request()->is('mlbb/tools*'), 'icon' => 'tools'],
            ['label' => 'Jadwal Gift Skin', 'url' => url('/jadwal-gift-skin'), 'active' => request()->is('jadwal-gift-skin*'), 'icon' => 'calendar'],
            ['label' => 'Daftar Harga', 'url' => url('/daftar-harga'), 'active' => request()->is('daftar-harga*'), 'icon' => 'tag'],
            ['label' => 'Kontak Kami', 'url' => url('/kontak'), 'active' => request()->is('kontak*'), 'icon' => 'phone'],
          ];
        @endphp

        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center gap-2">
          @foreach($nav as $item)
            <a href="{{ $item['url'] }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition
                      {{ $item['active'] ? 'bg-red-700 text-white' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
              @if($item['icon'] === 'home')
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3 2 12h3v9h6v-6h2v6h6v-9h3L12 3z"/></svg>
              @elseif($item['icon'] === 'search')
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M10 2a8 8 0 1 0 4.9 14.3l4.4 4.4 1.4-1.4-4.4-4.4A8 8 0 0 0 10 2zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12z"/></svg>
              @elseif($item['icon'] === 'calendar')
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h2v2h6V2h2v2h3v18H4V4h3V2zm13 6H6v12h14V8z"/></svg>
              @elseif($item['icon'] === 'tag')
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M10 2H4v6l10 10 8-8L10 2zm-4 6V4h4l10 10-6 6L6 8zm2-1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>
              @elseif($item['icon'] === 'phone')
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1.1-.3 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V21c0 .6-.4 1-1 1C11.3 22 2 12.7 2 1c0-.6.4-1 1-1h3.9c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.4 0 .8-.3 1.1L6.6 10.8z"/></svg>
              @elseif($item['icon'] === 'tools')
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M21.7 18.3l-6.2-6.2a6 6 0 0 1-7.3-7.3l2.4 2.4 2.1-2.1-2.4-2.4a6 6 0 0 1 7.3 7.3l6.2 6.2-2.1 2.1zM6.2 20.5l4.2-4.2 1.4 1.4-4.2 4.2a1 1 0 0 1-1.4 0l0 0a1 1 0 0 1 0-1.4z"/>
                </svg>
              @endif
              <span>{{ $item['label'] }}</span>
            </a>
          @endforeach
        </nav>

        <!-- Mobile Hamburger Button -->
        <button
          type="button"
          data-mobile-menu-btn
          class="md:hidden inline-flex items-center justify-center w-11 h-11 rounded-xl bg-white/10 hover:bg-white/15 text-white"
          aria-label="Open menu"
          aria-expanded="false"
        >
          <svg data-icon-open class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
            <path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z"/>
          </svg>
          <svg data-icon-close class="w-6 h-6 hidden" viewBox="0 0 24 24" fill="currentColor">
            <path d="M18.3 5.7 12 12l6.3 6.3-1.4 1.4L10.6 13.4 4.3 19.7 2.9 18.3 9.2 12 2.9 5.7 4.3 4.3l6.3 6.3 6.3-6.3 1.4 1.4z"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu Panel -->
    {{-- ✅ FIX: mobile menu juga hitam --}}
    <div data-mobile-menu class="md:hidden hidden border-t border-white/10 bg-[#121212]">
      <div class="max-w-7xl mx-auto px-4 py-3">
        <div class="grid gap-2">
          @foreach($nav as $item)
            <a href="{{ $item['url'] }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl border border-white/10
                      {{ $item['active'] ? 'bg-red-700 text-white border-red-700' : 'bg-white/5 text-white hover:bg-white/10' }}">
              <span class="font-bold">{{ $item['label'] }}</span>
            </a>
          @endforeach
        </div>
      </div>
    </div>

    <script>
      (function () {
        const btn   = document.querySelector('[data-mobile-menu-btn]');
        const panel = document.querySelector('[data-mobile-menu]');
        if (!btn || !panel) return;

        const iconOpen  = btn.querySelector('[data-icon-open]');
        const iconClose = btn.querySelector('[data-icon-close]');

        function openMenu() {
          panel.classList.remove('hidden');
          btn.setAttribute('aria-expanded', 'true');
          iconOpen?.classList.add('hidden');
          iconClose?.classList.remove('hidden');
        }

        function closeMenu() {
          panel.classList.add('hidden');
          btn.setAttribute('aria-expanded', 'false');
          iconOpen?.classList.remove('hidden');
          iconClose?.classList.add('hidden');
        }

        function toggleMenu() {
          const isOpen = !panel.classList.contains('hidden');
          isOpen ? closeMenu() : openMenu();
        }

        btn.addEventListener('click', toggleMenu);
        panel.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeMenu(); });
      })();
    </script>
  </header>

  <!-- CONTENT -->
  {{-- ✅ FIX: flex-1 supaya konten mengisi layar dan ga muncul gap putih --}}
  <main class="w-full flex-1">
    @yield('content')
  </main>

  {{-- ✅ FLOATING SOCIAL + BANTUAN (default: TERTUTUP) --}}
  <div class="fixed right-4 bottom-24 z-[60] flex flex-col items-center gap-3 hidden" data-social-dock>
    @if($ttUrl)
      <a href="{{ $ttUrl }}" target="_blank" rel="noopener"
         class="w-12 h-12 rounded-2xl bg-black shadow-lg grid place-items-center hover:scale-105 transition">
        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
          <path d="M14 3v10.3a4.3 4.3 0 1 1-2-3.8V6h-2V4h4c.6 2 2.2 3.5 4 4v2c-1.5-.2-2.9-.9-4-1.9V3h0z"/>
        </svg>
      </a>
    @endif

    @if($ytUrl)
      <a href="{{ $ytUrl }}" target="_blank" rel="noopener"
         class="w-12 h-12 rounded-2xl bg-red-600 shadow-lg grid place-items-center hover:scale-105 transition">
        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
          <path d="M21.6 7.2c.2 1 .4 2.6.4 4.8s-.2 3.8-.4 4.8c-.2 1.1-1.1 2-2.2 2.2-1.2.2-3.4.4-7.4.4s-6.2-.2-7.4-.4c-1.1-.2-2-1.1-2.2-2.2C2.2 15.8 2 14.2 2 12s.2-3.8.4-4.8c.2-1.1 1.1-2-2.2-2.2C5.8 4.8 8 4.6 12 4.6s6.2.2 7.4.4c1.1.2 2 1.1 2.2 2.2zM10 9.2v5.6l5-2.8-5-2.8z"/>
        </svg>
      </a>
    @endif

    @if($fbUrl)
      <a href="{{ $fbUrl }}" target="_blank" rel="noopener"
         class="w-12 h-12 rounded-2xl bg-blue-600 shadow-lg grid place-items-center hover:scale-105 transition">
        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
          <path d="M14 8h2V5h-2c-2.2 0-4 1.8-4 4v2H8v3h2v7h3v-7h2.1l.9-3H13V9c0-.6.4-1 1-1z"/>
        </svg>
      </a>
    @endif

    @if($igUrl)
      <a href="{{ $igUrl }}" target="_blank" rel="noopener"
         class="w-12 h-12 rounded-2xl bg-gradient-to-br from-fuchsia-500 via-rose-500 to-amber-400 shadow-lg grid place-items-center hover:scale-105 transition">
        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
          <path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3zm-5 4.5A3.5 3.5 0 1 1 8.5 12 3.5 3.5 0 0 1 12 8.5zm0 2A1.5 1.5 0 1 0 13.5 12 1.5 1.5 0 0 0 12 10.5zM17.8 6.7a.8.8 0 1 1-.8-.8.8.8 0 0 1 .8.8z"/>
        </svg>
      </a>
    @endif

    <a href="{{ $waHelpUrl }}" target="_blank" rel="noopener"
       class="w-12 h-12 rounded-2xl bg-emerald-500 shadow-lg grid place-items-center hover:scale-105 transition">
      <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 2a10 10 0 0 0-8.7 15l-1.1 5 5.1-1A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .6.6-2.9-.2-.3A8 8 0 1 1 12 20zm4.4-5.1c-.2-.1-1.3-.6-1.5-.7s-.4-.1-.6.1-.7.7-.8.8-.3.2-.5.1a6.5 6.5 0 0 1-1.9-1.2 7.3 7.3 0 0 1-1.3-1.6c-.1-.2 0-.4.1-.5l.4-.5c.1-.1.1-.3.2-.4 0-.1 0-.3 0-.4s-.6-1.4-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.4.1-.6.3-.2.2-.8.8-.8 2s.8 2.3.9 2.5a8.9 8.9 0 0 0 3.4 3.2c.4.2 1.5.6 2.1.7.8.1 1.5.1 2.1-.1.6-.2 1.3-.7 1.5-1.3.2-.6.2-1.1.1-1.3-.1-.1-.2-.2-.4-.3z"/>
      </svg>
    </a>
  </div>

  {{-- Tombol Bantuan (toggle) --}}
  <button
    type="button"
    data-help-toggle
    class="fixed right-4 bottom-6 z-[61] inline-flex items-center gap-2 px-4 py-3 rounded-2xl shadow-lg
           bg-slate-900 text-white hover:bg-slate-800 transition"
    aria-expanded="false"
  >
    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
      <path d="M12 2a7 7 0 0 0-7 7v3a3 3 0 0 0 3 3h1v-6H8a1 1 0 0 1-1-1 5 5 0 0 1 10 0 1 1 0 0 1-1 1h-1v6h1a3 3 0 0 0 3-3V9a7 7 0 0 0-7-7zm-2 18h4v2h-4v-2z"/>
    </svg>
    <span class="text-sm font-bold">Hubungi</span>
  </button>

  <script>
    (function () {
      const dock = document.querySelector('[data-social-dock]');
      const btn  = document.querySelector('[data-help-toggle]');
      if (!dock || !btn) return;

      function setExpanded(isOpen){
        btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      }

      dock.classList.add('hidden');
      setExpanded(false);

      btn.addEventListener('click', () => {
        const isHidden = dock.classList.contains('hidden');
        if (isHidden) {
          dock.classList.remove('hidden');
          setExpanded(true);
        } else {
          dock.classList.add('hidden');
          setExpanded(false);
        }
      });
    })();
  </script>

  <!-- FOOTER -->
  {{-- ✅ FIX: footer lebih hitam + tidak ada gap --}}
  <footer class="bg-[#121212] text-white/90 mt-0 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 py-12">
      <div class="grid md:grid-cols-3 gap-10">

        <!-- Brand / About -->
        <div>
          <div class="flex items-center gap-3">
            <img
              src="{{ $logoUrl ?? asset('images/logo.png') }}"
              alt="Logo"
              class="w-10 h-10 rounded-full object-cover"
            >
            <div class="text-lg font-extrabold text-white">{{ $siteName }}</div>
          </div>

          <p class="mt-4 text-sm leading-relaxed text-white/80">
            {{ $footerText }}
          </p>
        </div>

        <!-- Halaman -->
        <div>
          <div class="text-base font-bold mb-4 text-white">Halaman</div>
          <ul class="space-y-3 text-sm">
            <li class="border-b border-white/10 pb-2">
              <a href="{{ url('/') }}" class="hover:text-white">Halaman Utama</a>
            </li>
            <li class="border-b border-white/10 pb-2">
              <a href="{{ url('/cek-pesanan') }}" class="hover:text-white">Cek Pesanan</a>
            </li>
            <li class="border-b border-white/10 pb-2">
              <a href="{{ url('/syarat-ketentuan') }}" class="hover:text-white">Syarat &amp; Ketentuan</a>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <div class="text-3xl font-extrabold mb-4 text-white">Contact</div>
          <ul class="text-sm space-y-2 text-white/80">
            <li>• Email:
              <a class="hover:text-white" href="mailto:{{ $email }}">{{ $email }}</a>
            </li>
            <li>• Whatsapp:
              <a class="hover:text-white" href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener">
                {{ $wa }}
              </a>
            </li>
          </ul>
        </div>

      </div>
    </div>

    <div class="border-t border-white/10 bg-[#121212]">
      <div class="max-w-7xl mx-auto px-4 py-4 text-center text-sm text-white/80">
        Copyright © {{ date('Y') }} {{ $siteName }}. All Rights Reserved
      </div>
    </div>
  </footer>

</body>
</html>
