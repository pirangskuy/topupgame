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

    $packLabel = $order->denomination->group ?? null; // kalau kamu sudah pakai group
    if ($packLabel === 'paket_a') $packLabel = 'Paket A';
    if ($packLabel === 'paket_b') $packLabel = 'Paket B';
    if ($packLabel === 'weekly')  $packLabel = 'Weekly';

    // ✅ cek apakah payment method = Midtrans
    $isMidtrans = $order->paymentMethod
      && str_contains(strtolower((string) $order->paymentMethod->name), 'midtrans');
  @endphp

  <div class="grid lg:grid-cols-3 gap-6">
    {{-- LEFT: Detail --}}
    <div class="lg:col-span-2 space-y-6">
      {{-- Header --}}
      <div class="rounded-2xl border bg-white p-6">
        <div class="flex items-start justify-between gap-4">
          <div>
            <div class="text-sm text-slate-500">Invoice</div>

            <div class="mt-1 flex flex-wrap items-center gap-2">
              <div class="text-2xl font-extrabold" id="invoiceCode">{{ $order->order_code }}</div>

              <button type="button"
                      id="copyInvoice"
                      class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50">
                Copy
              </button>

              <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                {{ $statusLabel }}
              </span>
            </div>

            <div class="mt-2 text-sm text-slate-500">
              Dibuat: {{ optional($order->created_at)->format('d M Y, H:i') ?? '-' }}
            </div>
          </div>

          <a href="{{ route('home') }}" class="text-sm text-blue-700 hover:underline">Kembali</a>
        </div>

        {{-- Ringkas --}}
        <div class="mt-6 grid md:grid-cols-2 gap-4 text-sm">
          <div class="rounded-xl bg-slate-50 p-4">
            <div class="text-slate-500">Game</div>
            <div class="font-semibold">{{ $order->game->name }}</div>

            <div class="mt-3 flex items-center gap-3">
              <div class="w-14 h-14 rounded-xl bg-white border overflow-hidden flex items-center justify-center">
                @if($gameImg)
                  <img src="{{ $gameImg }}" alt="{{ $order->game->name }}" class="w-full h-full object-cover">
                @else
                  <span class="text-2xl">🎮</span>
                @endif
              </div>

              <div class="min-w-0">
                <div class="text-slate-500">Nominal</div>
                <div class="font-semibold truncate">{{ $order->denomination->name }}</div>

                @if(!empty($packLabel))
                  <div class="mt-1 text-xs inline-flex items-center px-2 py-1 rounded-full bg-white border text-slate-600">
                    {{ $packLabel }}
                  </div>
                @endif
              </div>
            </div>
          </div>

          <div class="rounded-xl bg-slate-50 p-4">
            <div class="text-slate-500">User ID</div>
            <div class="font-semibold">{{ $order->user_id }}</div>

            <div class="text-slate-500 mt-2">Server</div>
            <div class="font-semibold">{{ $order->server_id ?? '-' }}</div>

            <div class="text-slate-500 mt-2">WhatsApp</div>
            <div class="font-semibold">{{ $order->whatsapp }}</div>
          </div>
        </div>
      </div>

      {{-- Instruksi pembayaran --}}
      <div class="rounded-2xl border bg-white p-6">
        <div class="font-bold text-lg">Instruksi Pembayaran</div>

        @if($order->paymentMethod)
          <div class="mt-3 text-sm text-slate-700">
            <div class="font-semibold">{{ $order->paymentMethod->name }}</div>

            {{-- ✅ Jika Midtrans + status pending, tampilkan tombol Bayar + embed Snap --}}
            @if($isMidtrans && $status === 'pending')
              <div class="mt-4">
                <button
                  type="button"
                  id="btnPayMidtrans"
                  class="w-full rounded-xl bg-blue-700 text-white py-3 font-bold hover:bg-blue-800 transition"
                >
                  Pilih Metode Pembayaran (Midtrans)
                </button>

                <div class="mt-4">
                  <div id="snap-container" class="rounded-2xl border bg-white overflow-hidden"></div>
                </div>

                <div class="mt-3 text-xs text-slate-500">
                  Setelah memilih metode dan membayar, status invoice akan berubah otomatis (via notifikasi Midtrans).
                </div>
              </div>
            @else
              <div class="mt-2 whitespace-pre-line text-slate-600">
                {{ $order->paymentMethod->instruction ?? 'Instruksi belum diisi admin.' }}
              </div>
            @endif
          </div>
        @else
          <div class="mt-3 text-sm text-slate-600">
            Kamu belum memilih metode pembayaran. Silakan buat order ulang dan pilih metode.
          </div>
        @endif
      </div>
    </div>

    {{-- RIGHT: Total --}}
    <div class="lg:col-span-1">
      <div class="rounded-2xl border bg-white p-6">
        <div class="text-sm text-slate-500">Total</div>
        <div class="text-3xl font-extrabold mt-1">
          Rp {{ number_format((int)$order->total_amount, 0, ',', '.') }}
        </div>

        <div class="mt-4 rounded-xl bg-blue-50 p-4 text-sm text-blue-900">
          Simpan kode invoice ini untuk cek status.
        </div>

        <div class="mt-4 text-xs text-slate-500">
          Kode: <span class="font-semibold">{{ $order->order_code }}</span>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script>
  // Copy invoice
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

  // Midtrans Embed
  (function () {
    const btnPay = document.getElementById('btnPayMidtrans');
    const container = document.getElementById('snap-container');
    if (!btnPay || !container) return;

    let loaded = false;

    btnPay.addEventListener('click', async () => {
      if (loaded) return;

      btnPay.disabled = true;
      btnPay.textContent = 'Memuat pembayaran...';

      try {
        const res = await fetch("{{ route('midtrans.token', $order->order_code) }}", {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Accept": "application/json"
          }
        });

        const data = await res.json();

        if (!data.token) {
          throw new Error(data.message || 'Token Midtrans tidak ditemukan');
        }

        // ✅ tampilkan Snap di dalam div => muncul dropdown kategori
        window.snap.embed(data.token, { embedId: "snap-container" });
        loaded = true;

        btnPay.textContent = 'Pembayaran Siap (Scroll ke bawah)';
      } catch (e) {
        console.error(e);
        btnPay.disabled = false;
        btnPay.textContent = 'Pilih Metode Pembayaran (Midtrans)';
        alert('Gagal memuat pembayaran: ' + (e.message || e));
      }
    });
  })();
</script>
@endpush
