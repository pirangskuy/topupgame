{{-- resources/views/front/jadwal_gift_skin.blade.php --}}
@extends('layouts.app')

@php
  $title = 'Jadwal Gift Skin - Dimstore';

  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;

  // Ambil banner aktif (mini) untuk halaman ini
  $bannersMini = $bannersMini ?? \App\Models\Banner::query()
      ->where('is_active', true)
      ->orderBy('sort_order')
      ->latest('id')
      ->limit(5)
      ->get();

  /**
   * URL aman untuk file public storage.
   * Handle:
   * - banners/xxx.jpg
   * - xxx.jpg
   * - public/banners/xxx.jpg
   * - storage/banners/xxx.jpg
   * - /storage/banners/xxx.jpg
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

    $candidates = [$path];

    // Kalau cuma nama file, coba beberapa folder umum
    if (!Str::contains($path, '/')) {
      array_unshift($candidates, "banners/{$path}");
      array_unshift($candidates, "games/{$path}");
    }

    foreach ($candidates as $p) {
      $p = ltrim($p, '/');

      if (Storage::disk('public')->exists($p)) {
        return url("/storage/{$p}");
      }

      if (is_file(public_path($p))) {
        return url("/{$p}");
      }

      if (is_file(public_path("storage/{$p}"))) {
        return url("/storage/{$p}");
      }
    }

    return null;
  };
@endphp

@section('content')
  {{-- ✅ Background merah full seperti halaman lain --}}
  <section class="w-full bg-gradient-to-b from-[#AC0008] via-[#AC0008] to-[#8E0006]">
    <div class="max-w-7xl mx-auto px-4 py-10">

      {{-- BANNER MINI (mengganti logo) --}}
      @if(!empty($bannersMini) && $bannersMini->isNotEmpty())
        {{-- ✅ card banner jadi hitam border putih halus --}}
        <section class="mb-6 overflow-hidden rounded-2xl border border-white/10 bg-[#121212]">
          <div data-banner-mini class="relative w-full h-[180px] md:h-[220px] bg-[#121212]">
            <div data-track class="flex h-full transition-transform duration-500 ease-out" style="transform: translateX(0%);">
              @foreach($bannersMini as $banner)
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
                        <div class="w-full h-full flex items-center justify-center text-3xl text-white">🖼️</div>
                      @endif
                    </a>
                  @else
                    @if($src)
                      <img src="{{ $src }}" alt="Banner" class="w-full h-full object-cover" />
                    @else
                      <div class="w-full h-full flex items-center justify-center text-3xl text-white">🖼️</div>
                    @endif
                  @endif

                  <div class="absolute inset-0 bg-black/20 pointer-events-none"></div>
                </div>
              @endforeach
            </div>

            @if($bannersMini->count() > 1)
              <button type="button" data-prev
                class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full
                       bg-black/45 hover:bg-black/65 text-white
                       ring-1 ring-white/15 hover:ring-white/25
                       flex items-center justify-center"
                aria-label="Previous">‹</button>

              <button type="button" data-next
                class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full
                       bg-black/45 hover:bg-black/65 text-white
                       ring-1 ring-white/15 hover:ring-white/25
                       flex items-center justify-center"
                aria-label="Next">›</button>

              <div class="absolute bottom-3 left-0 right-0 flex items-center justify-center gap-2">
                @foreach($bannersMini as $i => $banner)
                  <button type="button" data-dot="{{ $i }}"
                    class="w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white/80
                           focus:outline-none focus:ring-4 focus:ring-white/30"
                    aria-label="Go to slide {{ $i + 1 }}"></button>
                @endforeach
              </div>
            @endif
          </div>
        </section>

        <script>
          (function () {
            const root = document.querySelector('[data-banner-mini]');
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

      {{-- ✅ Card utama jadi hitam --}}
      <div class="bg-[#121212] rounded-2xl shadow-lg border border-white/10 overflow-hidden">
        <div class="p-6">
          <div class="text-xl font-extrabold text-white">Jadwal Gift Skin</div>

          {{-- Filter buttons --}}
          <div class="mt-4 flex flex-wrap gap-2">
            @php
              $base = [
                'perPage' => $perPage,
                'q' => $q,
              ];
            @endphp

            <a href="{{ url('/jadwal-gift-skin') . '?' . http_build_query(array_merge($base, ['status' => 'all'])) }}"
               class="px-3 py-1 rounded-xl text-xs font-bold border
                      {{ $status==='all' ? 'bg-red-700 text-white border-red-700' : 'bg-white/5 text-white/90 border-white/10 hover:bg-white/10' }}">
              ALL STATUS
            </a>

            <a href="{{ url('/jadwal-gift-skin') . '?' . http_build_query(array_merge($base, ['status' => 'done'])) }}"
               class="px-3 py-1 rounded-xl text-xs font-bold border
                      {{ $status==='done' ? 'bg-red-700 text-white border-red-700' : 'bg-white/5 text-white/90 border-white/10 hover:bg-white/10' }}">
              DONE
            </a>

            <a href="{{ url('/jadwal-gift-skin') . '?' . http_build_query(array_merge($base, ['status' => 'progress'])) }}"
               class="px-3 py-1 rounded-xl text-xs font-bold border
                      {{ $status==='progress' ? 'bg-red-700 text-white border-red-700' : 'bg-white/5 text-white/90 border-white/10 hover:bg-white/10' }}">
              PROGRESS
            </a>
          </div>

          {{-- Top controls (Show entries + Search) --}}
          <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <form method="GET" action="{{ url('/jadwal-gift-skin') }}" class="flex items-center gap-2 text-white/80 text-sm">
              <input type="hidden" name="status" value="{{ $status }}">
              <input type="hidden" name="q" value="{{ $q }}">

              <span>Show</span>
              <select name="perPage"
                      onchange="this.form.submit()"
                      class="h-10 rounded-xl bg-white text-slate-900 px-3 text-sm">
                @foreach([10,25,50,100] as $n)
                  <option value="{{ $n }}" @selected($perPage==$n)>{{ $n }}</option>
                @endforeach
              </select>
              <span>entries</span>
            </form>

            <form method="GET" action="{{ url('/jadwal-gift-skin') }}" class="flex items-center gap-2 text-white/80 text-sm">
              <input type="hidden" name="status" value="{{ $status }}">
              <input type="hidden" name="perPage" value="{{ $perPage }}">

              <label class="text-white/70">Search:</label>
              <input name="q" value="{{ $q }}"
                     class="h-10 w-40 md:w-56 rounded-xl bg-white text-slate-900 px-4 outline-none
                            ring-1 ring-white/10 focus:ring-2 focus:ring-yellow-200"
                     placeholder="Nickname / User ID">
            </form>
          </div>

          {{-- Table --}}
          <div class="mt-4 overflow-x-auto rounded-xl ring-1 ring-white/10">
            <table class="min-w-full text-sm">
              <thead>
                <tr class="bg-white/5 text-white/90">
                  <th class="text-left px-4 py-3 font-extrabold">ORDER DATE</th>
                  <th class="text-left px-4 py-3 font-extrabold">NICKNAME</th>
                  <th class="text-left px-4 py-3 font-extrabold">USER ID</th>
                  <th class="text-left px-4 py-3 font-extrabold">GIFT DATE</th>
                  <th class="text-left px-4 py-3 font-extrabold">STATUS</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-white/10">
                @forelse($rows as $r)
                  <tr class="text-white/90 hover:bg-white/5">
                    <td class="px-4 py-3 whitespace-nowrap">{{ $r->order_date?->format('Y-m-d H:i:s') }}</td>
                    <td class="px-4 py-3">{{ $r->nickname }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $r->user_id }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $r->gift_date?->format('Y-m-d') ?? '0000-00-00' }}</td>
                    <td class="px-4 py-3">
                      @if($r->status === 'done')
                        <span class="inline-flex px-2 py-1 rounded-xl bg-emerald-500/15 text-emerald-200 text-xs font-extrabold ring-1 ring-emerald-400/20">
                          DONE
                        </span>
                      @else
                        <span class="inline-flex px-2 py-1 rounded-xl bg-amber-500/15 text-amber-200 text-xs font-extrabold ring-1 ring-amber-400/20">
                          PROGRESS
                        </span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-white/70">
                      Data belum ada.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- Bottom info + pagination --}}
          <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 text-sm text-white/70">
            <div>
              @if($rows->total() > 0)
                Showing {{ $rows->firstItem() }} to {{ $rows->lastItem() }} of {{ $rows->total() }} entries
              @else
                Showing 0 to 0 of 0 entries
              @endif
            </div>

            <div class="flex items-center gap-2">
              <a href="{{ $rows->previousPageUrl() ?? '#' }}"
                 class="px-3 py-1 rounded-xl border border-white/10
                        {{ $rows->onFirstPage() ? 'opacity-50 pointer-events-none' : 'hover:bg-white/10' }}">
                Previous
              </a>

              <span class="px-3 py-1 rounded-xl bg-white text-slate-900 font-extrabold">
                {{ $rows->currentPage() }}
              </span>

              <a href="{{ $rows->nextPageUrl() ?? '#' }}"
                 class="px-3 py-1 rounded-xl border border-white/10
                        {{ $rows->hasMorePages() ? 'hover:bg-white/10' : 'opacity-50 pointer-events-none' }}">
                Next
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
@endsection
