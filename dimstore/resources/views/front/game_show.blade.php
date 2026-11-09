@extends('layouts.app', ['title' => $game->name.' - Dimstore'])

@section('content')
@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;

  // helper URL image public
  $publicSrc = function ($path) {
    $path = trim((string) $path);
    if ($path === '') return null;

    $path = str_replace('\\', '/', $path);

    if (Str::startsWith($path, ['http://', 'https://'])) return $path;

    $path = preg_replace('#^/?public/#', '', $path);
    $path = preg_replace('#^/?storage/#', '', $path);
    $path = ltrim($path, '/');

    if (!Str::contains($path, '/')) {
      $path = "games/{$path}";
    }

    return url(Storage::disk('public')->url($path));
  };

  $gameImg = $publicSrc($game->image ?? null);

  $labelOf = function (string $g) {
    $k = strtolower($g);
    if ($k === 'paket_a') return 'Paket A';
    if ($k === 'paket_b') return 'Paket B';
    if ($k === 'weekly')  return 'Weekly';
    return Str::title(str_replace(['_', '-'], ' ', $g));
  };

  /**
   * LIST CHANNEL MIDTRANS
   */
  $midtransEwallet = [
    ['code' => 'gopay',     'name' => 'GoPay',     'badge' => null],
    ['code' => 'dana',      'name' => 'DANA',      'badge' => null],
    ['code' => 'ovo',       'name' => 'OVO',       'badge' => null],
    ['code' => 'shopeepay', 'name' => 'ShopeePay', 'badge' => 'Termurah'],
    ['code' => 'qris',      'name' => 'QRIS',      'badge' => 'Termurah'],
  ];

  $midtransVA = [
    ['code' => 'bca_va',     'name' => 'VA BCA'],
    ['code' => 'bri_va',     'name' => 'VA BRI'],
    ['code' => 'bni_va',     'name' => 'VA BNI'],
    ['code' => 'mandiri_va', 'name' => 'VA Mandiri'],
    ['code' => 'permata_va', 'name' => 'VA Permata'],
    ['code' => 'cimb_va',    'name' => 'VA CIMB'],
  ];

  $logoMap = [
    'gopay' => 'gopay.svg',
    'dana' => 'dana.svg',
    'ovo' => 'ovo.svg',
    'shopeepay' => 'shopeepay.svg',
    'qris' => 'qris.svg',
    'bca_va' => 'bca.svg',
    'bri_va' => 'bri.svg',
    'bni_va' => 'bni.svg',
    'mandiri_va' => 'mandiri.svg',
    'permata_va' => 'permata.svg',
    'cimb_va' => 'cimb.svg',
  ];

  $logoUrl = function (string $code) use ($logoMap) {
    $file = $logoMap[$code] ?? null;
    return $file ? asset("assets/payments/{$file}") : null;
  };

  $isProd = (bool) config('services.midtrans.is_production', false);
  $snapJs = $isProd
    ? 'https://app.midtrans.com/snap/snap.js'
    : 'https://app.sandbox.midtrans.com/snap/snap.js';

  $clientKey = (string) config('services.midtrans.client_key', '');
@endphp

<style>
  /* tema */
  .theme-card { border: 1px solid rgba(255,255,255,.10); }
  .theme-shadow { box-shadow: 0 12px 30px rgba(0,0,0,.18); }
  .badge-cheap { background: #facc15; color:#111827; }

  /* ✅ Panel step pakai hitam (#121212) biar match header/footer */
  .panel-dark { background:#121212; }

  /* ✅ Card kiri info (biar ga putih) */
  .card-dark { background:#121212; }

  /* ✅ Card nominal & payment maroon */
  .bg-maroon { background:#7b1e1e; }
  .bg-maroon-2 { background:#8b2323; }
</style>

{{-- Snap JS (Midtrans) --}}
@if($clientKey !== '')
  <script src="{{ $snapJs }}" data-client-key="{{ $clientKey }}"></script>
@endif

{{-- BACKGROUND MERAH --}}
<div class="bg-gradient-to-b from-[#AC0008] via-[#AC0008] to-[#8E0006]">
  <div class="max-w-7xl mx-auto px-4 py-8">

    <div class="grid lg:grid-cols-3 gap-6">
      <!-- Left info -->
      <div class="lg:col-span-1">
        {{-- ✅ FIX: kiri jadi hitam juga --}}
        <div class="rounded-2xl card-dark theme-card theme-shadow p-5 text-white">
          <div class="aspect-square rounded-xl bg-white/5 overflow-hidden flex items-center justify-center ring-1 ring-white/10">
            @if($gameImg)
              <img src="{{ $gameImg }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
            @else
              <span class="text-4xl">🎮</span>
            @endif
          </div>

          <h1 class="mt-4 text-xl font-extrabold text-white">{{ $game->name }}</h1>
          <p class="mt-2 text-sm text-white/80">
            <span class="inline-block w-2 h-2 bg-yellow-400 rounded-full mr-1"></span>
            Layanan aktif 24 jam. Pastikan ID/Server benar.
          </p>

          <div class="mt-4 text-sm">
            <div class="font-extrabold text-white flex items-center gap-2">
              <span class="inline-block w-2 h-2 bg-yellow-400 rounded-full"></span>
              Cara Order
            </div>
            <ol class="mt-2 space-y-1 text-white/80">
              <li>1) Masukkan ID / Server</li>
              <li>2) Pilih nominal</li>
              <li>3) Pilih metode pembayaran (Midtrans)</li>
              <li>4) Masukkan WhatsApp</li>
            </ol>
          </div>

          <div class="mt-4 rounded-xl bg-black text-white p-4 ring-1 ring-white/10">
            <div class="font-extrabold">Dimstore</div>
            <div class="text-xs text-white/80 mt-1">
              Jual Diamond Termurah<span class="text-yellow-300 font-bold">Seluruh</span> • <span class="text-yellow-300 font-bold">Indonesia</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right form -->
      <div class="lg:col-span-2">
        <form method="POST" action="{{ route('order.store') }}" class="space-y-6" id="orderForm">
          @csrf
          <input type="hidden" name="game_id" value="{{ $game->id }}"/>

          <!-- Step 1 -->
          <div class="rounded-2xl panel-dark theme-card theme-shadow p-5">
            <div class="font-extrabold text-white flex items-center gap-2">
              <span class="w-8 h-8 rounded-full bg-red-700 text-white flex items-center justify-center text-sm">1</span>
              Masukkan Data Akun
            </div>

            <div class="mt-4 grid md:grid-cols-2 gap-4">
              <div>
                <label class="text-sm text-white/90">User ID</label>
                <input name="user_id" value="{{ old('user_id') }}" id="userId"
                       class="mt-1 w-full rounded-xl border border-white/10 px-4 py-2 bg-white text-black
                              focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-400"
                       placeholder="Masukkan User ID" required>
              </div>

              @if((bool)($game->requires_server ?? false))
                <div>
                  <label class="text-sm text-white/90">Server ID</label>
                  <input name="server_id" value="{{ old('server_id') }}" id="serverId"
                         class="mt-1 w-full rounded-xl border border-white/10 px-4 py-2 bg-white text-black
                                focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-400"
                         placeholder="Masukkan Zone ID" required>
                </div>
              @else
                <input type="hidden" name="server_id" value="">
              @endif
            </div>
          </div>

          <!-- Step 2 -->
          <div class="rounded-2xl panel-dark theme-card theme-shadow p-5">
            <div class="font-extrabold text-white flex items-center gap-2">
              <span class="w-8 h-8 rounded-full bg-red-700 text-white flex items-center justify-center text-sm">2</span>
              Pilih Nominal
            </div>

            @if(!empty($groups) && $groups->isNotEmpty())
              <div class="mt-4 flex flex-wrap gap-2">
                @foreach($groups as $g)
                  <a href="{{ route('games.show', $game) . '?group=' . urlencode($g) }}"
                     class="px-3 py-1.5 rounded-lg text-sm font-extrabold border transition
                            {{ ($activeGroup ?? '') === $g
                                ? 'bg-red-700 text-white border-red-700'
                                : 'bg-white/5 text-white border-white/10 hover:bg-white/10 hover:border-white/20' }}">
                    {{ $labelOf($g) }}
                  </a>
                @endforeach
              </div>
            @endif

            <div class="mt-4">
              @if($denoms->isNotEmpty())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                  @foreach($denoms as $d)
                    <label class="cursor-pointer">
                      <input
                        type="radio"
                        name="denomination_id"
                        value="{{ $d->id }}"
                        data-price="{{ (int) $d->price }}"
                        class="peer sr-only"
                        @checked((string)old('denomination_id') === (string)$d->id)
                        required
                      >

                      {{-- ✅ CARD NOMINAL MAROON + TEXT PUTIH --}}
                      <div class="rounded-2xl border p-4 transition flex gap-3
                                  bg-maroon border-white/15 hover:border-yellow-300
                                  peer-checked:bg-maroon-2 peer-checked:border-yellow-300
                                  peer-checked:ring-2 peer-checked:ring-yellow-200">
                        <div class="min-w-0">
                          <div class="font-extrabold truncate text-white denom-name">{{ $d->name }}</div>
                          <div class="text-sm text-white/90 mt-1 denom-price">
                            Rp {{ number_format((int)$d->price, 0, ',', '.') }}
                          </div>
                        </div>
                      </div>
                    </label>
                  @endforeach
                </div>
              @else
                <div class="text-sm text-white/90">
                  Belum ada nominal untuk paket ini.
                </div>
              @endif
            </div>

            <div class="mt-4 rounded-xl bg-black text-white p-4 flex items-center justify-between ring-1 ring-white/10">
              <div>
                <div class="text-xs text-white/80">Total (sementara)</div>
                <div id="totalPrice" class="text-xl font-extrabold text-yellow-300">Rp 0</div>
              </div>
              <div class="text-xs text-white/70">
                Pilih nominal + metode
              </div>
            </div>
          </div>

          <!-- Step 3 (MIDTRANS) -->
          <div class="rounded-2xl panel-dark theme-card theme-shadow p-5">
            <div class="font-extrabold text-white flex items-center gap-2">
              <span class="w-8 h-8 rounded-full bg-red-700 text-white flex items-center justify-center text-sm">3</span>
              Metode Pembayaran (Midtrans)
            </div>

            {{-- E-Wallet & QRIS --}}
            <div class="mt-4">
              <div class="text-sm font-extrabold text-white">E-Wallet dan QRIS</div>

              <div class="mt-3 grid sm:grid-cols-2 gap-3">
                @foreach($midtransEwallet as $p)
                  @php $logo = $logoUrl($p['code']); @endphp
                  <label class="cursor-pointer">
                    <input
                      type="radio"
                      name="payment_channel"
                      value="{{ $p['code'] }}"
                      class="peer sr-only"
                      @checked(old('payment_channel') === $p['code'])
                      required
                    >

                    {{-- ✅ CARD PAYMENT MAROON + TEXT PUTIH --}}
                    <div class="rounded-2xl border p-4 transition flex items-center gap-3 relative
                                bg-maroon border-white/15 hover:border-yellow-300
                                peer-checked:bg-maroon-2 peer-checked:border-yellow-300
                                peer-checked:ring-2 peer-checked:ring-yellow-200">
                      <div class="w-12 h-12 rounded-xl bg-white border border-black/10 flex items-center justify-center overflow-hidden shrink-0">
                        @if($logo)
                          <img src="{{ $logo }}" alt="{{ $p['name'] }}" class="w-full h-full object-contain p-2">
                        @else
                          <span class="font-extrabold text-black">{{ Str::upper(substr($p['name'], 0, 2)) }}</span>
                        @endif
                      </div>

                      <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                          <div class="font-extrabold text-white truncate">{{ $p['name'] }}</div>
                          <div class="text-right">
                            <div class="font-extrabold text-white pm-price" data-code="{{ $p['code'] }}">Rp 0</div>
                          </div>
                        </div>

                        <div class="text-xs text-white/80 mt-1">Instruksi akan muncul setelah bayar</div>
                      </div>

                      @if(!empty($p['badge']))
                        <span class="absolute top-2 right-2 text-[11px] px-2 py-0.5 rounded-full badge-cheap font-extrabold">
                          {{ $p['badge'] }}
                        </span>
                      @endif
                    </div>
                  </label>
                @endforeach
              </div>
            </div>

            {{-- Virtual Account --}}
            <div class="mt-6">
              <div class="text-sm font-extrabold text-white">Virtual Account</div>

              <div class="mt-3 grid sm:grid-cols-2 gap-3">
                @foreach($midtransVA as $p)
                  @php $logo = $logoUrl($p['code']); @endphp
                  <label class="cursor-pointer">
                    <input
                      type="radio"
                      name="payment_channel"
                      value="{{ $p['code'] }}"
                      class="peer sr-only"
                      @checked(old('payment_channel') === $p['code'])
                      required
                    >

                    {{-- ✅ CARD PAYMENT MAROON + TEXT PUTIH --}}
                    <div class="rounded-2xl border p-4 transition flex items-center gap-3
                                bg-maroon border-white/15 hover:border-yellow-300
                                peer-checked:bg-maroon-2 peer-checked:border-yellow-300
                                peer-checked:ring-2 peer-checked:ring-yellow-200">
                      <div class="w-12 h-12 rounded-xl bg-white border border-black/10 flex items-center justify-center overflow-hidden shrink-0">
                        @if($logo)
                          <img src="{{ $logo }}" alt="{{ $p['name'] }}" class="w-full h-full object-contain p-2">
                        @else
                          <span class="font-extrabold text-black">{{ Str::upper(substr($p['name'], 0, 2)) }}</span>
                        @endif
                      </div>

                      <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                          <div class="font-extrabold text-white truncate">{{ $p['name'] }}</div>
                          <div class="text-right">
                            <div class="font-extrabold text-white pm-price" data-code="{{ $p['code'] }}">Rp 0</div>
                          </div>
                        </div>
                        <div class="text-xs text-white/80 mt-1">Instruksi akan muncul setelah bayar</div>
                      </div>
                    </div>
                  </label>
                @endforeach
              </div>
            </div>

            <div class="mt-4 text-xs text-white/90">
              * Metode & biaya admin final akan mengikuti konfigurasi Midtrans akunmu.
            </div>

            @if($clientKey === '')
              <div class="mt-3 text-xs rounded-xl border border-yellow-300 bg-yellow-50 p-3 text-black">
                ⚠️ <b>Midtrans Client Key belum terisi.</b> Isi <code>MIDTRANS_CLIENT_KEY</code> di .env dan mapping di <code>config/services.php</code>.
              </div>
            @endif
          </div>

          <!-- Step 4 -->
          <div class="rounded-2xl panel-dark theme-card theme-shadow p-5">
            <div class="font-extrabold text-white flex items-center gap-2">
              <span class="w-8 h-8 rounded-full bg-red-700 text-white flex items-center justify-center text-sm">4</span>
              Nomor WhatsApp
            </div>

            <div class="mt-4">
              <input name="whatsapp" value="{{ old('whatsapp') }}" id="whatsapp"
                     class="w-full rounded-xl border border-white/10 px-4 py-2 bg-white text-black
                            focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-400"
                     placeholder="Contoh: 08xxxxxxxxxx" required>
              <div class="text-xs text-white/90 mt-2">
                Pastikan nomor benar untuk notifikasi status.
              </div>
            </div>

            <button type="submit"
                    id="submitBtn"
                    class="mt-5 w-full rounded-xl bg-red-700 text-white py-3 font-extrabold hover:bg-black transition flex items-center justify-center gap-2">
              <span id="btnText">Buat Pesanan & Bayar</span>
              <span id="btnLoader" class="hidden">Memproses...</span>
            </button>

            <div class="mt-3 text-xs text-white/90">
              Jika popup tidak muncul, kamu tetap bisa bayar dari halaman invoice.
            </div>

            @if($errors->any())
              <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <div class="font-bold mb-2">Ada error:</div>
                <ul class="list-disc ml-5">
                  @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

<script>
  function formatIDR(n){
    return 'Rp ' + (n || 0).toLocaleString('id-ID');
  }

  function getSelectedBasePrice(){
    const denom = document.querySelector('input[name="denomination_id"]:checked');
    return denom ? parseInt(denom.dataset.price || '0', 10) : 0;
  }

  function updatePrices(){
    const base = getSelectedBasePrice();
    const total = document.getElementById('totalPrice');
    if (total) total.textContent = formatIDR(base);

    document.querySelectorAll('.pm-price').forEach(el => {
      el.textContent = formatIDR(base);
    });
  }

  document.querySelectorAll('input[name="denomination_id"]').forEach(r => {
    r.addEventListener('change', updatePrices);
  });

  window.addEventListener('load', updatePrices);

  const form = document.getElementById('orderForm');
  const submitBtn = document.getElementById('submitBtn');
  const btnText = document.getElementById('btnText');
  const btnLoader = document.getElementById('btnLoader');

  form.addEventListener('submit', async function(e){
    if (typeof window.snap === 'undefined') return;

    e.preventDefault();

    submitBtn.disabled = true;
    btnText.classList.add('hidden');
    btnLoader.classList.remove('hidden');

    try {
      const fd = new FormData(form);

      const res = await fetch(form.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': fd.get('_token'),
        },
        body: fd
      });

      const ct = (res.headers.get('content-type') || '').toLowerCase();

      if (!ct.includes('application/json')) {
        submitBtn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoader.classList.add('hidden');
        form.submit();
        return;
      }

      const data = await res.json();

      if (!res.ok) {
        alert(data?.message || 'Gagal membuat pesanan.');
        submitBtn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoader.classList.add('hidden');
        return;
      }

      const orderCode = data.order_code || data.order?.order_code;
      if (!orderCode) {
        alert('Order berhasil dibuat, tapi order_code tidak ditemukan. Silakan cek invoice.');
        window.location.href = "{{ url('/cek-pesanan') }}";
        return;
      }

      const tokenRes = await fetch("{{ url('/midtrans/token') }}/" + encodeURIComponent(orderCode), {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': fd.get('_token'),
        },
        body: JSON.stringify({
          payment_channel: fd.get('payment_channel')
        })
      });

      const tokenData = await tokenRes.json();
      const snapToken = tokenData.token;

      if (!snapToken) {
        alert('Order dibuat, tapi token Midtrans gagal. Silakan bayar dari invoice.');
        window.location.href = "{{ url('/invoice') }}/" + encodeURIComponent(orderCode);
        return;
      }

      window.snap.pay(snapToken, {
        onSuccess: function () { window.location.href = "{{ url('/invoice') }}/" + encodeURIComponent(orderCode); },
        onPending: function () { window.location.href = "{{ url('/invoice') }}/" + encodeURIComponent(orderCode); },
        onError: function ()   { window.location.href = "{{ url('/invoice') }}/" + encodeURIComponent(orderCode); },
        onClose: function ()   { window.location.href = "{{ url('/invoice') }}/" + encodeURIComponent(orderCode); },
      });

    } catch (err) {
      alert('Terjadi error: ' + (err?.message || err));
      submitBtn.disabled = false;
      btnText.classList.remove('hidden');
      btnLoader.classList.add('hidden');
    }
  });
</script>
@endsection
