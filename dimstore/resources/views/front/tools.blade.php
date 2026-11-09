@extends('layouts.app', ['title' => 'Tools MLBB - Dimstore'])

@section('content')
@php
  // helper class biar konsisten tema (hitam-merah-kuning-putih)
  $card   = "rounded-2xl border border-white/10 bg-white p-6 shadow-sm";
  $badge  = "text-xs px-3 py-1 rounded-full bg-yellow-50 text-yellow-800 font-extrabold border border-yellow-200";
  $label  = "text-xs font-extrabold text-black/70";
  $input  = "mt-1 w-full rounded-xl border border-black/10 bg-white px-4 py-2.5
             focus:outline-none focus:ring-4 focus:ring-yellow-400/60 focus:border-yellow-500
             placeholder:text-black/30";
  $btn    = "rounded-xl bg-red-600 text-white py-2.5 font-extrabold hover:bg-red-700 transition
             focus:outline-none focus:ring-4 focus:ring-yellow-400/60";
  $btnAlt = "rounded-xl bg-black text-white py-2.5 font-extrabold hover:bg-black/90 transition
             focus:outline-none focus:ring-4 focus:ring-yellow-400/60";
@endphp

<div class="min-h-screen bg-black">
  {{-- glow halus biar ga flat --}}
  <div class="pointer-events-none fixed inset-0 opacity-40">
    <div class="absolute -top-40 -left-40 h-[420px] w-[420px] rounded-full bg-red-600 blur-[120px]"></div>
    <div class="absolute -bottom-40 -right-40 h-[420px] w-[420px] rounded-full bg-yellow-400 blur-[120px]"></div>
  </div>

  <div class="relative max-w-5xl mx-auto px-4 py-10 space-y-6">

    <!-- Header -->
    <div class="{{ $card }}">
      <div class="flex items-start justify-between gap-4">
        <div>
          <h1 class="text-2xl font-extrabold text-black">
            Tools Mobile Legends (MLBB) <span class="text-red-600">•</span>
          </h1>
          <p class="text-black/60 mt-1 text-sm">
            Cek Region, Kalkulator Winrate, Magic Wheel, dan Zodiac.
          </p>
          <div class="mt-3 h-1 w-20 bg-yellow-400 rounded-full"></div>
        </div>
        <div class="hidden sm:flex items-center gap-2">
          <span class="text-xs font-extrabold text-black/60">Dimstore</span>
          <span class="inline-block w-2 h-2 rounded-full bg-red-600"></span>
          <span class="inline-block w-2 h-2 rounded-full bg-yellow-400"></span>
          <span class="inline-block w-2 h-2 rounded-full bg-black"></span>
        </div>
      </div>
    </div>

    <!-- GRID Tools -->
    <div class="grid lg:grid-cols-2 gap-6">

      <!-- 1) Cek Region -->
      <div class="{{ $card }}">
        <div class="flex items-center justify-between gap-3">
          <h2 class="text-lg font-extrabold text-black">Cek Region MLBB</h2>
          <span class="{{ $badge }}">ID + Zone</span>
        </div>

        <p class="text-sm text-black/60 mt-2">
          Masukkan User ID & Zone ID untuk memastikan format benar sebelum order.
          <span class="block text-xs text-black/50 mt-1">
            * Jika server inquiry aktif, nama player akan ikut tampil.
          </span>
        </p>

        <form id="regionForm" class="mt-4 grid sm:grid-cols-2 gap-3" onsubmit="return false;">
          @csrf

          <div>
            <label class="{{ $label }}">User ID</label>
            <input id="region_user_id" class="{{ $input }}"
                   placeholder="Masukkan ID" inputmode="numeric">
            <p class="text-xs text-black/50 mt-1">ID Mobile Legend</p>
          </div>

          <div>
            <label class="{{ $label }}">Zone ID</label>
            <input id="region_zone_id" class="{{ $input }}"
                   placeholder="Masukkan Zone ID" inputmode="numeric">
            <p class="text-xs text-black/50 mt-1">Zone ID</p>
          </div>

          <button type="button" onclick="checkRegion()" class="sm:col-span-2 {{ $btn }}">
            Cek
          </button>
        </form>

        <div id="region_out" class="mt-4 text-sm"></div>
      </div>

      <!-- 2) Kalkulator Winrate -->
      <div class="{{ $card }}">
        <div class="flex items-center justify-between gap-3">
          <h2 class="text-lg font-extrabold text-black">Kalkulator Winrate</h2>
          <span class="{{ $badge }}">WR</span>
        </div>

        <p class="text-sm text-black/60 mt-2">
          Hitung butuh berapa win lagi untuk mencapai target winrate.
        </p>

        <div class="mt-4 grid gap-3">
          <div class="grid sm:grid-cols-3 gap-3">
            <div>
              <label class="{{ $label }}">Total Match</label>
              <input id="wr_matches" class="{{ $input }}" type="number" min="1" placeholder="contoh: 250">
            </div>
            <div>
              <label class="{{ $label }}">WR Sekarang (%)</label>
              <input id="wr_current" class="{{ $input }}" type="number" min="0" max="100" step="0.01" placeholder="contoh: 55.2">
            </div>
            <div>
              <label class="{{ $label }}">Target WR (%)</label>
              <input id="wr_target" class="{{ $input }}" type="number" min="0" max="99.99" step="0.01" placeholder="contoh: 60">
            </div>
          </div>

          <button type="button" onclick="calcWinrate()" class="{{ $btn }}">
            Hitung Winrate
          </button>

          <div id="wr_out" class="text-sm"></div>
        </div>
      </div>

      <!-- 3) Magic Wheel -->
      <div class="{{ $card }}">
        <div class="flex items-center justify-between gap-3">
          <h2 class="text-lg font-extrabold text-black">Kalkulator Magic Wheel</h2>
          <span class="{{ $badge }}">MW</span>
        </div>

        <p class="text-sm text-black/60 mt-2">
          Perkiraan jumlah spin & diamond untuk mencapai target point.
        </p>

        <div class="mt-4 grid gap-3">
          <div class="grid sm:grid-cols-2 gap-3">
            <div>
              <label class="{{ $label }}">Point Sekarang</label>
              <input id="mw_now" class="{{ $input }}" type="number" min="0" placeholder="contoh: 35">
            </div>
            <div>
              <label class="{{ $label }}">Target Point</label>
              <input id="mw_target" class="{{ $input }}" type="number" min="1" value="200">
            </div>
            <div>
              <label class="{{ $label }}">Point / Spin</label>
              <input id="mw_per" class="{{ $input }}" type="number" min="1" value="1">
            </div>
            <div>
              <label class="{{ $label }}">Diamond / Spin</label>
              <input id="mw_cost" class="{{ $input }}" type="number" min="0" value="50">
            </div>
          </div>

          <button type="button" onclick="calcMW()" class="{{ $btnAlt }}">
            Hitung Magic Wheel
          </button>

          <div id="mw_out" class="text-sm"></div>
          <div class="text-xs text-black/50">
            Angka di bawah hanya perkiraan, sesuaikan dengan event yang sedang berjalan.
          </div>
        </div>
      </div>

      <!-- 4) Zodiac -->
      <div class="{{ $card }}">
        <div class="flex items-center justify-between gap-3">
          <h2 class="text-lg font-extrabold text-black">Kalkulator Zodiac</h2>
          <span class="{{ $badge }}">ZOD</span>
        </div>

        <p class="text-sm text-black/60 mt-2">
          Perkiraan jumlah draw & diamond untuk target Zodiac.
        </p>

        <div class="mt-4 grid gap-3">
          <div class="grid sm:grid-cols-2 gap-3">
            <div>
              <label class="{{ $label }}">Point Sekarang</label>
              <input id="z_now" class="{{ $input }}" type="number" min="0" placeholder="contoh: 10">
            </div>
            <div>
              <label class="{{ $label }}">Target Point</label>
              <input id="z_target" class="{{ $input }}" type="number" min="1" value="100">
            </div>
            <div>
              <label class="{{ $label }}">Point / Draw</label>
              <input id="z_per" class="{{ $input }}" type="number" min="1" value="1">
            </div>
            <div>
              <label class="{{ $label }}">Diamond / Draw</label>
              <input id="z_cost" class="{{ $input }}" type="number" min="0" value="20">
            </div>
          </div>

          <button type="button" onclick="calcZ()" class="{{ $btn }}">
            Hitung Zodiac
          </button>

          <div id="z_out" class="text-sm"></div>
          <div class="text-xs text-black/50">
            Angka di bawah hanya perkiraan, sesuaikan dengan event yang sedang berjalan.
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  // --- helper UI (tema) ---
  function box(type, title, bodyHtml) {
    // type: 'ok' | 'warn' | 'err' | 'info'
    const map = {
      ok:   { border: 'border-green-200', bg: 'bg-green-50', text: 'text-green-800', dot: 'bg-green-500' },
      warn: { border: 'border-yellow-200', bg: 'bg-yellow-50', text: 'text-yellow-900', dot: 'bg-yellow-400' },
      err:  { border: 'border-red-200', bg: 'bg-red-50', text: 'text-red-800', dot: 'bg-red-600' },
      info: { border: 'border-black/10', bg: 'bg-white', text: 'text-black/80', dot: 'bg-black' },
    };
    const c = map[type] || map.info;
    return `
      <div class="rounded-xl border ${c.border} ${c.bg} p-3 ${c.text}">
        <div class="flex items-start gap-2">
          <span class="mt-1 inline-block w-2 h-2 rounded-full ${c.dot}"></span>
          <div class="flex-1">
            <b>${title}</b><div class="mt-1">${bodyHtml}</div>
          </div>
        </div>
      </div>
    `;
  }

  // 1) Cek Region + Nickname (butuh endpoint backend)
  async function checkRegion() {
    const uid = (document.getElementById('region_user_id').value || '').trim();
    const zid = (document.getElementById('region_zone_id').value || '').trim();
    const out = document.getElementById('region_out');

    const uidOk = /^[0-9]{6,12}$/.test(uid);
    const zidOk = /^[0-9]{3,6}$/.test(zid);

    if (!uidOk || !zidOk) {
      out.innerHTML = box('err', 'Format tidak valid.', `
        • User ID harus 6–12 digit<br>
        • Zone ID harus 3–6 digit
      `);
      return;
    }

    out.innerHTML = box('warn', 'Format valid ✅', `
      User ID: <b>${uid}</b> • Zone ID: <b>${zid}</b><br>
      <span class="text-black/60">Mengecek nama player...</span>
    `);

    const token = document.querySelector('#regionForm input[name="_token"]')?.value || '';

    try {
      const res = await fetch("{{ url('/mlbb/inquiry-nickname') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": token,
          "Accept": "application/json",
        },
        credentials: "same-origin",
        body: JSON.stringify({ user_id: uid, server_id: zid })
      });

      const json = await res.json().catch(() => ({}));

      if (!res.ok) {
        const msg = json.message || "Server inquiry belum aktif / endpoint belum ada.";
        out.innerHTML = box('ok', 'Format valid ✅', `
          User ID: <b>${uid}</b> • Zone ID: <b>${zid}</b><br>
          <span class="text-black/70">Gunakan ini saat order Mobile Legends.</span>
          <div class="mt-2 text-xs text-black/60">Catatan: ${msg}</div>
        `);
        return;
      }

      const nickname = (json.nickname || '').toString().trim();
      const message = json.message || '';

      if (nickname) {
        out.innerHTML = box('ok', 'Format valid ✅', `
          User ID: <b>${uid}</b> • Zone ID: <b>${zid}</b><br>
          <div class="mt-2"><b>Nama Player:</b> ${nickname}</div>
        `);
      } else {
        out.innerHTML = box('ok', 'Format valid ✅', `
          User ID: <b>${uid}</b> • Zone ID: <b>${zid}</b><br>
          <span class="text-black/70">Gunakan ini saat order Mobile Legends.</span>
          <div class="mt-2 text-xs text-black/60">
            ${message ? message : 'Nama player belum tersedia (API provider inquiry belum diset / tidak mengembalikan nickname).'}
          </div>
        `);
      }
    } catch (e) {
      out.innerHTML = box('ok', 'Format valid ✅', `
        User ID: <b>${uid}</b> • Zone ID: <b>${zid}</b><br>
        <span class="text-black/70">Gunakan ini saat order Mobile Legends.</span>
        <div class="mt-2 text-xs text-black/60">Catatan: gagal cek nama player (${e.message}).</div>
      `);
    }
  }

  // 2) Kalkulator Winrate
  function calcWinrate() {
    const M = parseFloat(document.getElementById('wr_matches').value || '0');
    const cur = parseFloat(document.getElementById('wr_current').value || '0');
    const tgt = parseFloat(document.getElementById('wr_target').value || '0');
    const out = document.getElementById('wr_out');

    if (M <= 0 || cur < 0 || cur > 100 || tgt < 0 || tgt >= 100) {
      out.innerHTML = box('err', 'Input tidak valid.', `Pastikan total match > 0, WR 0–100, dan target < 100%.`);
      return;
    }

    const W = (cur / 100) * M;

    if (cur >= tgt) {
      out.innerHTML = box('ok', 'Sudah memenuhi target ✅', `WR kamu sudah ≥ target. Tambahan win: <b>0</b>.`);
      return;
    }

    const x = Math.ceil(((tgt * M) - (100 * W)) / (100 - tgt));
    out.innerHTML = box('warn', 'Hasil perhitungan', `
      Butuh tambahan <b>${x}</b> win berturut-turut untuk mencapai minimal <b>${tgt}%</b>.
    `);
  }

  // 3) Magic Wheel
  function calcMW() {
    const now = parseFloat(document.getElementById('mw_now').value || '0');
    const target = parseFloat(document.getElementById('mw_target').value || '0');
    const per = parseFloat(document.getElementById('mw_per').value || '1');
    const cost = parseFloat(document.getElementById('mw_cost').value || '0');
    const out = document.getElementById('mw_out');

    if (target <= 0 || per <= 0 || now < 0 || cost < 0) {
      out.innerHTML = box('err', 'Input tidak valid.', `Periksa angka yang kamu isi.`);
      return;
    }

    const need = Math.max(0, target - now);
    const spins = Math.ceil(need / per);
    const diamonds = spins * cost;

    out.innerHTML = box('warn', 'Estimasi Magic Wheel', `
      Sisa point: <b>${need}</b><br>
      Perkiraan butuh <b>${spins}</b> spin (~<b>${diamonds}</b> diamonds).
    `);
  }

  // 4) Zodiac
  function calcZ() {
    const now = parseFloat(document.getElementById('z_now').value || '0');
    const target = parseFloat(document.getElementById('z_target').value || '0');
    const per = parseFloat(document.getElementById('z_per').value || '1');
    const cost = parseFloat(document.getElementById('z_cost').value || '0');
    const out = document.getElementById('z_out');

    if (target <= 0 || per <= 0 || now < 0 || cost < 0) {
      out.innerHTML = box('err', 'Input tidak valid.', `Periksa angka yang kamu isi.`);
      return;
    }

    const need = Math.max(0, target - now);
    const draws = Math.ceil(need / per);
    const diamonds = draws * cost;

    out.innerHTML = box('warn', 'Estimasi Zodiac', `
      Sisa point: <b>${need}</b><br>
      Perkiraan butuh <b>${draws}</b> draw (~<b>${diamonds}</b> diamonds).
    `);
  }
</script>
@endsection
