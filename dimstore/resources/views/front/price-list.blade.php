@extends('layouts.app', ['title' => 'Dimstore - Daftar Harga'])

@section('content')
  {{-- ✅ Background merah full --}}
  <section class="w-full bg-gradient-to-b from-[#AC0008] via-[#AC0008] to-[#8E0006]">
    <div class="max-w-7xl mx-auto px-4 py-10">

      <div class="flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex-1">
          {{-- ✅ judul putih --}}
          <h1 class="text-2xl font-extrabold text-white">Daftar Harga</h1>
          <p class="text-white/80 mt-1 text-sm">Pilih game untuk melihat daftar harga per paket.</p>
        </div>

        {{-- Pilih Game --}}
        <form method="GET" action="{{ url('/daftar-harga') }}" class="w-full md:w-[360px]">
          <label class="block text-sm font-semibold mb-2 text-white/90">Pilih Game</label>
          <select name="game"
                  class="w-full rounded-2xl border border-white/10 bg-white px-4 py-3 text-sm
                         focus:outline-none focus:ring-2 focus:ring-yellow-200"
                  onchange="this.form.submit()">
            @foreach($games as $g)
              <option value="{{ $g->id }}" {{ (int)$gameId === (int)$g->id ? 'selected' : '' }}>
                {{ $g->name }}
              </option>
            @endforeach
          </select>
        </form>
      </div>

      @if(!$selectedGame)
        <div class="mt-8 rounded-2xl border border-white/10 bg-[#121212] p-6 text-white/80">
          Tidak ada game yang dipilih / game tidak ditemukan.
        </div>
      @else
        {{-- Ringkas info --}}
        <div class="mt-6 rounded-2xl border border-white/10 bg-[#121212] p-6">
          <div class="flex items-center justify-between gap-4">
            <div>
              <div class="text-lg font-extrabold text-white">{{ $selectedGame->name }}</div>
              <div class="text-sm text-white/70">Daftar nominal & harga (aktif)</div>
            </div>
            <a href="{{ url('/') }}" class="text-sm font-semibold text-yellow-200 hover:text-yellow-100 hover:underline">
              ← Kembali ke Home
            </a>
          </div>
        </div>

        {{-- Paket --}}
        <div class="mt-6 grid lg:grid-cols-3 gap-6">

          @php
            $labels = [
              'paket_a' => 'Paket A',
              'paket_b' => 'Paket B',
              'weekly'  => 'Weekly',
            ];
          @endphp

          @foreach(['paket_a', 'paket_b', 'weekly'] as $key)
            @php
              $items = $groups->get($key, collect());
            @endphp

            {{-- ✅ card paket hitam --}}
            <div class="rounded-2xl border border-white/10 bg-[#121212] overflow-hidden">
              <div class="px-5 py-4 bg-[#121212] text-white flex items-center justify-between border-b border-white/10">
                <div class="font-extrabold">{{ $labels[$key] ?? strtoupper($key) }}</div>
                <div class="text-xs text-white/70">{{ $items->count() }} item</div>
              </div>

              @if($items->isEmpty())
                <div class="p-5 text-sm text-white/70">
                  Belum ada item untuk paket ini.
                </div>
              @else
                <div class="overflow-x-auto">
                  <table class="w-full text-sm">
                    <thead class="bg-white/5 text-white/80">
                      <tr>
                        <th class="text-left px-4 py-3 font-extrabold">Nominal</th>
                        <th class="text-right px-4 py-3 font-extrabold">Harga</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                      @foreach($items as $d)
                        <tr class="hover:bg-white/5">
                          <td class="px-4 py-3">
                            <div class="font-semibold text-white">{{ $d->name }}</div>
                          </td>
                          <td class="px-4 py-3 text-right font-extrabold text-yellow-300">
                            Rp {{ number_format((int)$d->price, 0, ',', '.') }}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          @endforeach

        </div>
      @endif

    </div>
  </section>
@endsection
