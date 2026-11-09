@extends('layouts.app', ['title' => 'Dimstore - Top Up Game'])

@section('content')
  @php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;

    /**
     * URL aman untuk file public storage.
     * Handle:
     * - games/xxx.jpg
     * - xxx.jpg  (fallback otomatis jadi games/xxx.jpg)
     * - public/games/xxx.jpg
     * - storage/games/xxx.jpg
     * - /storage/games/xxx.jpg
     * - http(s)://...
     */
    $publicSrc = function ($path) {
      $path = trim((string) $path);
      if ($path === '') return null;

      $path = str_replace('\\', '/', $path);

      // kalau sudah URL penuh
      if (Str::startsWith($path, ['http://', 'https://'])) return $path;

      // Normalisasi prefix biar ga dobel
      $path = preg_replace('#^/?public/#', '', $path);
      $path = preg_replace('#^/?storage/#', '', $path);

      // Kandidat path yang akan dicek
      $candidates = [$path];

      // Kalau cuma nama file (tidak ada slash), coba asumsikan di folder games/
      if (!Str::contains($path, '/')) {
        array_unshift($candidates, "games/{$path}");
      }

      // Cari yang benar-benar ada filenya
      foreach ($candidates as $p) {
        $p = ltrim($p, '/');

        // 1) cek di storage disk public (storage/app/public)
        if (Storage::disk('public')->exists($p)) {
          return url("/storage/{$p}");
        }

        // 2) fallback kalau ternyata kamu simpan langsung di public/...
        if (is_file(public_path($p))) {
          return url("/{$p}");
        }

        // 3) fallback kalau ada di public/storage/... (symlink)
        if (is_file(public_path("storage/{$p}"))) {
          return url("/storage/{$p}");
        }
      }

      return null;
    };
  @endphp

  {{-- WRAPPER: merah (full) --}}
  <div class="bg-gradient-to-b from-[#AC0008] via-[#AC0008] to-[#8E0006] min-h-screen text-white">

    {{-- FULL HERO BANNER (SLIDER) --}}
    @if(!empty($banners) && $banners->isNotEmpty())
      <section class="relative left-1/2 right-1/2 -mx-[50vw] w-screen overflow-hidden">
        <div data-banner-carousel class="relative w-full h-[62vh] md:h-[75vh] lg:h-[calc(100vh-6rem)] bg-black">
          <div data-track class="flex h-full transition-transform duration-500 ease-out" style="transform: translateX(0%);">
            @foreach($banners as $banner)
              @php
                $src  = $publicSrc($banner->image ?? null);
                $href = !empty($banner->link_url) ? $banner->link_url : null;
              @endphp

              <div data-slide class="min-w-full h-full relative">
                @if($href)
                  <a href="{{ $href }}" class="block w-full h-full">
                    @if($src)
                      <img src="{{ $src }}" alt="Banner" class="w-full h-full object-cover" />
                    @else
                      <div class="w-full h-full flex items-center justify-center text-5xl text-white">🖼️</div>
                    @endif
                  </a>
                @else
                  @if($src)
                    <img src="{{ $src }}" alt="Banner" class="w-full h-full object-cover" />
                  @else
                    <div class="w-full h-full flex items-center justify-center text-5xl text-white">🖼️</div>
                  @endif
                @endif

                {{-- overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/20 to-black/10 pointer-events-none"></div>
              </div>
            @endforeach
          </div>

          @if($banners->count() > 1)
            {{-- tombol --}}
            <button type="button" data-prev
              class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full
                     bg-black/55 hover:bg-black/70 text-white
                     ring-1 ring-white/20 hover:ring-white/30
                     focus:outline-none focus:ring-4 focus:ring-white/40
                     flex items-center justify-center"
              aria-label="Previous">‹</button>

            <button type="button" data-next
              class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full
                     bg-black/55 hover:bg-black/70 text-white
                     ring-1 ring-white/20 hover:ring-white/30
                     focus:outline-none focus:ring-4 focus:ring-white/40
                     flex items-center justify-center"
              aria-label="Next">›</button>

            <div class="absolute bottom-4 left-0 right-0 flex items-center justify-center gap-2">
              @foreach($banners as $i => $banner)
                <button type="button" data-dot="{{ $i }}"
                  class="w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white
                         focus:outline-none focus:ring-4 focus:ring-white/40"
                  aria-label="Go to slide {{ $i + 1 }}"></button>
              @endforeach
            </div>
          @endif
        </div>
      </section>

      <script>
        (function () {
          const root = document.querySelector('[data-banner-carousel]');
          if (!root) return;

          const track  = root.querySelector('[data-track]');
          const slides = Array.from(root.querySelectorAll('[data-slide]'));
          const prev   = root.querySelector('[data-prev]');
          const next   = root.querySelector('[data-next]');
          const dots   = Array.from(root.querySelectorAll('[data-dot]'));

          if (!track || slides.length <= 1) return;

          let index = 0;
          let timer = null;

          function paintDots() {
            dots.forEach((d, i) => {
              d.classList.toggle('bg-white', i === index);
              d.classList.toggle('bg-white/40', i !== index);
            });
          }

          function go(i) {
            index = (i + slides.length) % slides.length;
            track.style.transform = `translateX(-${index * 100}%)`;
            paintDots();
          }

          function start() {
            stop();
            timer = setInterval(() => go(index + 1), 5000);
          }

          function stop() {
            if (timer) clearInterval(timer);
            timer = null;
          }

          prev?.addEventListener('click', () => go(index - 1));
          next?.addEventListener('click', () => go(index + 1));
          dots.forEach((d) => d.addEventListener('click', () => go(parseInt(d.dataset.dot, 10) || 0)));

          root.addEventListener('mouseenter', stop);
          root.addEventListener('mouseleave', start);

          go(0);
          start();
        })();
      </script>
    @endif

    {{-- Categories (CLICKABLE) --}}
    <section class="mt-8 max-w-7xl mx-auto px-4">
      <div class="flex items-end justify-between gap-4">
        <div>
          <h2 class="text-lg font-extrabold text-white">
            Kategori <span class="text-white/80">•</span>
          </h2>
          <div class="mt-1 h-1 w-16 bg-white/70 rounded-full"></div>
        </div>
      </div>

      <div class="mt-4 flex flex-wrap gap-2">
        @foreach($categories as $cat)
          <a href="{{ route('home', ['category' => $cat->id]) }}"
             class="px-3.5 py-1.5 rounded-full border text-sm font-semibold transition
                    focus:outline-none focus:ring-4 focus:ring-white/40
                    {{ (int)($categoryId ?? 0) === (int)$cat->id
                        ? 'bg-white text-[#AC0008] border-white shadow-sm'
                        : 'bg-[#121212] text-white border-white/10 hover:bg-white/10 hover:border-white/20' }}">
            {{ $cat->name }}
          </a>
        @endforeach
      </div>
    </section>

    {{-- Games --}}
    <section class="mt-8 max-w-7xl mx-auto px-4 pb-10">
      <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($popularGames as $g)
          @php $src = $publicSrc($g->image ?? null); @endphp

          <a href="{{ route('games.show', $g) }}"
             class="group rounded-2xl border border-white/10 bg-[#121212] p-3 transition
                    hover:shadow-lg hover:-translate-y-0.5
                    hover:bg-white/10
                    focus:outline-none focus:ring-4 focus:ring-white/40">
            <div class="aspect-square rounded-xl bg-black/10 overflow-hidden flex items-center justify-center
                        ring-1 ring-white/10 group-hover:ring-white/20">
              @if($src)
                <img src="{{ $src }}" alt="{{ $g->name }}" class="w-full h-full object-cover">
              @else
                <span class="text-3xl">🎮</span>
              @endif
            </div>

            <div class="mt-3 text-sm font-extrabold text-white line-clamp-2">
              {{ $g->name }}
            </div>

            <div class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-white/90">
              <span class="inline-block w-1.5 h-1.5 rounded-full bg-white/80"></span>
              Top Up
            </div>
          </a>
        @endforeach
      </div>
    </section>

  </div>
@endsection
