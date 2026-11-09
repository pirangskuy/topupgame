@extends('layouts.app', ['title' => 'Invoice '.$order->order_code])

@section('content')
  @php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;

    // helper URL image public (sama seperti home & game detail)
    $publicSrc = function ($path) {
      $path = trim((string) $path);
      if ($path === '') return null;

      $path = str_replace('\\', '/', $path);

      if (Str::startsWith($path, ['http://', 'https://'])) return $path;

      $path = preg_replace('#^/?public/#', '', $path);
      $path = preg_replace('#^/?storage/#', '', $path);
      $path = ltrim($path, '/');

      // kalau cuma nama file, anggap di folder games/
      if (!Str::contains($path, '/')) {
        $path = "games/{$path}";
      }

      return url(Storage::disk('public')->url($path));
    };

    $gameImg = $publicSrc($order->game->image ?? null);

    // styling status
    $status = (string)($order->status ?? 'pending');
    $statusLabel = strtoupper($status);

    $statusClass = match($status) {
      'paid', 'success', 'done' => 'bg-green-100 text-green-700',
      'failed', 'cancelled', 'canceled', 'expired' => 'bg-red-100 text-red-700',
      default => 'bg-yellow-100 text-yellow-700', // pending
    };

    $packLabel = $order->denomination->group ?? null;
    if ($packLabel === 'paket_a') $packLabel = 'Paket A';
    if ($packLabel === 'paket_b') $packLabel = 'Paket B';
    if ($packLabel === 'weekly')  $packLabel = 'Weekly';
  @endphp

  <style>
    .theme-card { border: 1px solid rgba(0,0,0,.08); }
    .theme-shadow { box-shadow: 0 12px 30px rgba(0,0,0,.08); }
    .panel-626 { background:#626262; }
  </style>

  {{-- ✅ BACKGROUND MERAH seperti halaman game --}}
  <div class="bg-red-700">
    <div class="max-w-7xl mx-auto px-4 py-8">

      <div class="grid lg:grid-cols-3 gap-6">

        {{-- LEFT: Detail --}}
        <div class="lg:col-span-2 space-y-6">

          {{-- Header --}}
          <div class="rounded-2xl panel-626 theme-card theme-shadow p-6">
            <div class="flex items-start justify-between gap-4">
              <div>
                <div class="text-sm text-white/80">Invoice</div>

                <div class="mt-1 flex flex-wrap items-center gap-2">
                  <div class="text-2xl font-extrabold text-white" id="invoiceCode">{{ $order->order_code }}</div>

                  <button type="button"
                          id="copyInvoice"
                          class="px-3 py-1.5 rounded-lg border border-white/20 text-sm text-white hover:bg-white/10">
                    Copy
                  </button>

                  <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                    {{ $statusLabel }}
                  </span>
                </div>

                <div class="mt-2 text-sm text-white/80">
                  Dibuat: {{ optional($order->created_at)->format('d M Y, H:i') ?? '-' }}
                </div>
              </div>

              <a href="{{ route('home') }}" class="text-sm text-yellow-300 hover:underline">Kembali</a>
            </div>

            {{-- Ringkas --}}
            <div class="mt-6 grid md:grid-cols-2 gap-4 text-sm">

              <div class="rounded-xl bg-black/30 p-4 border border-white/10">
                <div class="text-white/80">Game</div>
                <div class="font-semibold text-white">{{ $order->game->name }}</div>

                <div class="mt-3 flex items-center gap-3">
                  <div class="w-14 h-14 rounded-xl bg-white border overflow-hidden flex items-center justify-center">
                    @if($gameImg)
                      <img src="{{ $gameImg }}" alt="{{ $order->game->name }}" class="w-full h-full object-cover">
                    @else
                      <span class="text-2xl">🎮</span>
                    @endif
                  </div>

                  <div class="min-w-0">
                    <div class="text-white/80">Nominal</div>
                    <div class="font-semibold text-white truncate">{{ $order->denomination->name }}</div>

                    @if(!empty($packLabel))
                      <div class="mt-1 text-xs inline-flex items-center px-2 py-1 rounded-full bg-white/10 border border-white/15 text-white">
                        {{ $packLabel }}
                      </div>
                    @endif
                  </div>
                </div>
              </div>

              <div class="rounded-xl bg-black/30 p-4 border border-white/10">
                <div class="text-white/80">User ID</div>
                <div class="font-semibold text-white">{{ $order->user_id }}</div>

                <div class="text-white/80 mt-2">Server</div>
                <div class="font-semibold text-white">{{ $order->server_id ?? '-' }}</div>

                <div class="text-white/80 mt-2">WhatsApp</div>
                <div class="font-semibold text-white">{{ $order->whatsapp }}</div>
              </div>

            </div>
          </div>

          {{-- Instruksi pembayaran --}}
          <div class="rounded-2xl panel-626 theme-card theme-shadow p-6">
            <div class="font-bold text-lg text-white">Instruksi Pembayaran</div>

            @if($order->paymentMethod)
              <div class="mt-3 text-sm text-white">
                <div class="font-semibold">{{ $order->paymentMethod->name }}</div>

                <div class="mt-2 whitespace-pre-line text-white/80">
                  {{ $order->paymentMethod->instruction ?? 'Instruksi belum diisi admin.' }}
                </div>
              </div>
            @else
              <div class="mt-3 text-sm text-white/80">
                Kamu belum memilih metode pembayaran. Silakan buat order ulang dan pilih metode.
              </div>
            @endif

            <div class="mt-5 text-xs text-white/70">
              * Nanti saat Midtrans aktif, tombol “Bayar Sekarang” akan muncul di sini.
            </div>
          </div>

        </div>

        {{-- RIGHT: Total --}}
        <div class="lg:col-span-1">
          {{-- ✅ INI YANG BIKIN “HARGA” NGGAK PUTIH LAGI --}}
          <div class="rounded-2xl panel-626 theme-card theme-shadow p-6">
            <div class="text-sm text-white/80">Total</div>
            <div class="text-3xl font-extrabold mt-1 text-yellow-300">
              Rp {{ number_format((int)$order->total_amount, 0, ',', '.') }}
            </div>

            <div class="mt-4 rounded-xl bg-black text-white p-4 text-sm">
              Simpan kode invoice ini untuk cek status.
            </div>

            <div class="mt-4 text-xs text-white/80">
              Kode: <span class="font-semibold text-white">{{ $order->order_code }}</span>
            </div>
          </div>
        </div>

      </div>

      <script>
        (function () {
          const btn = document.getElementById('copyInvoice');
          const codeEl = document.getElementById('invoiceCode');
          if (!btn || !codeEl) return;

          btn.addEventListener('click', async () => {
            const text = codeEl.textContent?.trim() || '';
            if (!text) return;

            try {
              await navigator.clipboard.writeText(text);
              btn.textContent = 'Copied!';
              setTimeout(() => (btn.textContent = 'Copy'), 1200);
            } catch (e) {
              const ta = document.createElement('textarea');
              ta.value = text;
              document.body.appendChild(ta);
              ta.select();
              document.execCommand('copy');
              document.body.removeChild(ta);
              btn.textContent = 'Copied!';
              setTimeout(() => (btn.textContent = 'Copy'), 1200);
            }
          });
        })();
      </script>

    </div>
  </div>
@endsection
 