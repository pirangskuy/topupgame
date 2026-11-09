@extends('layouts.app', ['title' => 'Tools MLBB - Dimstore'])

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 space-y-6">

  <!-- Header -->
  <div class="rounded-2xl border bg-white p-6">
    <h1 class="text-2xl font-extrabold">Tools Mobile Legends (MLBB)</h1>
    <p class="text-slate-600 mt-1 text-sm">
      1 halaman berisi: Cek Region, Kalkulator Winrate, Magic Wheel, dan Zodiac.
    </p>
  </div>

  <!-- GRID Tools -->
  <div class="grid lg:grid-cols-2 gap-6">

    <!-- 1) Cek Region -->
    <div class="rounded-2xl border bg-white p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-extrabold">Cek Region MLBB</h2>
        <span class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-bold">ID + Zone</span>
      </div>

      <p class="text-sm text-slate-600 mt-2">
        Masukkan User ID & Zone ID untuk memastikan format benar sebelum order.
      </p>

      <form id="regionForm" class="mt-4 grid sm:grid-cols-2 gap-3" onsubmit="return false;">
        <div>
          <label class="text-xs font-semibold text-slate-600">User ID</label>
          <input id="region_user_id" class="mt-1 w-full rounded-xl border px-4 py-2"
                 placeholder="contoh: 123456789" inputmode="numeric">
          <p class="text-xs text-slate-500 mt-1">6–12 digit angka</p>
        </div>
        <div>
          <label class="text-xs font-semibold text-slate-600">Zone ID</label>
          <input id="region_zone_id" class="mt-1 w-full rounded-xl border px-4 py-2"
                 placeholder="contoh: 1234" inputmode="numeric">
          <p class="text-xs text-slate-500 mt-1">3–6 digit angka</p>
        </div>

        <button type="button" onclick="checkRegion()"
                class="sm:col-span-2 rounded-xl bg-blue-700 text-white py-2.5 font-bold hover:bg-blue-800 transition">
          Cek
        </button>
      </form>

      <div id="region_out" class="mt-4 text-sm"></div>
    </div>

    <!-- 2) Kalkulator Winrate -->
    <div class="rounded-2xl border bg-white p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-extrabold">Kalkulator Winrate</h2>
        <span class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-bold">WR</span>
      </div>

      <p class="text-sm text-slate-600 mt-2">
        Hitung butuh berapa win lagi untuk mencapai target winrate.
      </p>

      <div class="mt-4 grid gap-3">
        <div class="grid sm:grid-cols-3 gap-3">
          <div>
            <label class="text-xs font-semibold text-slate-600">Total Match</label>
            <input id="wr_matches" class="mt-1 w-full rounded-xl border px-4 py-2" type="number" min="1" placeholder="contoh: 250">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">WR Sekarang (%)</label>
            <input id="wr_current" class="mt-1 w-full rounded-xl border px-4 py-2" type="number" min="0" max="100" step="0.01" placeholder="contoh: 55.2">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">Target WR (%)</label>
            <input id="wr_target" class="mt-1 w-full rounded-xl border px-4 py-2" type="number" min="0" max="99.99" step="0.01" placeholder="contoh: 60">
          </div>
        </div>

        <button type="button" onclick="calcWinrate()"
                class="rounded-xl bg-blue-700 text-white py-2.5 font-bold hover:bg-blue-800 transition">
          Hitung Winrate
        </button>

        <div id="wr_out" class="text-sm"></div>
      </div>
    </div>

    <!-- 3) Magic Wheel -->
    <div class="rounded-2xl border bg-white p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-extrabold">Kalkulator Magic Wheel</h2>
        <span class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-bold">MW</span>
      </div>

      <p class="text-sm text-slate-600 mt-2">
        Perkiraan jumlah spin & diamond untuk mencapai target point.
      </p>

      <div class="mt-4 grid gap-3">
        <div class="grid sm:grid-cols-2 gap-3">
          <div>
            <label class="text-xs font-semibold text-slate-600">Point Sekarang</label>
            <input id="mw_now" class="mt-1 w-full rounded-xl border px-4 py-2" type="number" min="0" placeholder="contoh: 35">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">Target Point</label>
            <input id="mw_target" class="mt-1 w-full rounded-xl border px-4 py-2" type="number" min="1" value="200">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">Point / Spin</label>
            <input id="mw_per" class="mt-1 w-full rounded-xl border px-4 py-2" type="number" min="1" value="1">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">Diamond / Spin</label>
            <input id="mw_cost" class="mt-1 w-full rounded-xl border px-4 py-2" type="number" min="0" value="50">
          </div>
        </div>

        <button type="button" onclick="calcMW()"
                class="rounded-xl bg-blue-700 text-white py-2.5 font-bold hover:bg-blue-800 transition">
          Hitung Magic Wheel
        </button>

        <div id="mw_out" class="text-sm"></div>
        <div class="text-xs text-slate-500">
          * Angka default bisa kamu ubah sesuai event.
        </div>
      </div>
    </div>

    <!-- 4) Zodiac -->
    <div class="rounded-2xl border bg-white p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-extrabold">Kalkulator Zodiac</h2>
        <span class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-bold">ZOD</span>
      </div>

      <p class="text-sm text-slate-600 mt-2">
        Perkiraan jumlah draw & diamond untuk target Zodiac.
      </p>

      <div class="mt-4 grid gap-3">
        <div class="grid sm:grid-cols-2 gap-3">
          <div>
            <label class="text-xs font-semibold text-slate-600">Point Sekarang</label>
            <input id="z_now" class="mt-1 w-full rounded-xl border px-4 py-2" type="number" min="0" placeholder="contoh: 10">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">Target Point</label>
            <input id="z_target" class="mt-1 w-full rounded-xl border px-4 py-2" type="number" min="1" value="100">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">Point / Draw</label>
            <input id="z_per" class="mt-1 w-full rounded-xl border px-4 py-2" type="number" min="1" value="1">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">Diamond / Draw</label>
            <input id="z_cost" class="mt-1 w-full rounded-xl border px-4 py-2" type="number" min="0" value="20">
          </div>
        </div>

        <button type="button" onclick="calcZ()"
                class="rounded-xl bg-blue-700 text-white py-2.5 font-bold hover:bg-blue-800 transition">
          Hitung Zodiac
        </button>

        <div id="z_out" class="text-sm"></div>
        <div class="text-xs text-slate-500">
          * Angka default bisa kamu ubah sesuai periode Zodiac.
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  // 1) Cek Region (validasi format)
  function checkRegion() {
    const uid = (document.getElementById('region_user_id').value || '').trim();
    const zid = (document.getElementById('region_zone_id').value || '').trim();
    const out = document.getElementById('region_out');

    const uidOk = /^[0-9]{6,12}$/.test(uid);
    const zidOk = /^[0-9]{3,6}$/.test(zid);

    if (!uidOk || !zidOk) {
      out.innerHTML = `
        <div class="rounded-xl border border-red-200 bg-red-50 p-3 text-red-700">
          <b>Format tidak valid.</b><br>
          • User ID harus 6–12 digit angka<br>
          • Zone ID harus 3–6 digit angka
        </div>
      `;
      return;
    }

    out.innerHTML = `
      <div class="rounded-xl border border-green-200 bg-green-50 p-3 text-green-700">
        <b>Format valid ✅</b><br>
        User ID: <b>${uid}</b> • Zone ID: <b>${zid}</b><br>
        Gunakan ini saat order Mobile Legends.
      </div>
    `;
  }

  // 2) Kalkulator Winrate
  function calcWinrate() {
    const M = parseFloat(document.getElementById('wr_matches').value || '0');
    const cur = parseFloat(document.getElementById('wr_current').value || '0');
    const tgt = parseFloat(document.getElementById('wr_target').value || '0');
    const out = document.getElementById('wr_out');

    if (M <= 0 || cur < 0 || cur > 100 || tgt < 0 || tgt >= 100) {
      out.innerHTML = `<div class="rounded-xl border border-red-200 bg-red-50 p-3 text-red-700">
        Input tidak valid. Pastikan total match > 0, WR 0–100, dan target < 100%.
      </div>`;
      return;
    }

    const W = (cur / 100) * M;

    if (cur >= tgt) {
      out.innerHTML = `<div class="rounded-xl border border-green-200 bg-green-50 p-3 text-green-700">
        WR kamu sudah ≥ target. Tambahan win: <b>0</b>
      </div>`;
      return;
    }

    // x = ceil(((tgt*M) - (100*W)) / (100 - tgt))
    const x = Math.ceil(((tgt * M) - (100 * W)) / (100 - tgt));

    out.innerHTML = `<div class="rounded-xl border bg-slate-50 p-3 text-slate-800">
      Butuh tambahan <b>${x}</b> win berturut-turut untuk mencapai minimal <b>${tgt}%</b>.
    </div>`;
  }

  // 3) Magic Wheel
  function calcMW() {
    const now = parseFloat(document.getElementById('mw_now').value || '0');
    const target = parseFloat(document.getElementById('mw_target').value || '0');
    const per = parseFloat(document.getElementById('mw_per').value || '1');
    const cost = parseFloat(document.getElementById('mw_cost').value || '0');
    const out = document.getElementById('mw_out');

    if (target <= 0 || per <= 0 || now < 0 || cost < 0) {
      out.innerHTML = `<div class="rounded-xl border border-red-200 bg-red-50 p-3 text-red-700">
        Input tidak valid.
      </div>`;
      return;
    }

    const need = Math.max(0, target - now);
    const spins = Math.ceil(need / per);
    const diamonds = spins * cost;

    out.innerHTML = `<div class="rounded-xl border bg-slate-50 p-3 text-slate-800">
      Sisa point: <b>${need}</b> → Perkiraan butuh <b>${spins}</b> spin (~<b>${diamonds}</b> diamonds).
    </div>`;
  }

  // 4) Zodiac
  function calcZ() {
    const now = parseFloat(document.getElementById('z_now').value || '0');
    const target = parseFloat(document.getElementById('z_target').value || '0');
    const per = parseFloat(document.getElementById('z_per').value || '1');
    const cost = parseFloat(document.getElementById('z_cost').value || '0');
    const out = document.getElementById('z_out');

    if (target <= 0 || per <= 0 || now < 0 || cost < 0) {
      out.innerHTML = `<div class="rounded-xl border border-red-200 bg-red-50 p-3 text-red-700">
        Input tidak valid.
      </div>`;
      return;
    }

    const need = Math.max(0, target - now);
    const draws = Math.ceil(need / per);
    const diamonds = draws * cost;

    out.innerHTML = `<div class="rounded-xl border bg-slate-50 p-3 text-slate-800">
      Sisa point: <b>${need}</b> → Perkiraan butuh <b>${draws}</b> draw (~<b>${diamonds}</b> diamonds).
    </div>`;
  }
</script>
@endsection
