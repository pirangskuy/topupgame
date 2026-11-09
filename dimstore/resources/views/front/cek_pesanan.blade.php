{{-- resources/views/front/cek_pesanan.blade.php --}}
@extends('layouts.app')

@php
  $title = 'Cek Pesanan - Dimstore';
@endphp

@section('content')
  {{-- ✅ Background merah full --}}
  <section class="w-full bg-gradient-to-b from-[#AC0008] via-[#AC0008] to-[#8E0006]">
    <div class="max-w-7xl mx-auto px-4 py-10">
      {{-- ✅ Judul jadi putih --}}
      <h1 class="text-xl font-extrabold text-white">Cek Status Pesanan</h1>

      {{-- ✅ Card form jadi hitam (#121212) --}}
      <div class="mt-5 bg-[#121212] rounded-2xl shadow-lg border border-white/10 overflow-hidden">
        <div class="p-6">
          <form method="GET" action="{{ url('/cek-pesanan') }}"
                class="flex flex-col md:flex-row md:items-end gap-4">

            <div class="w-full flex-1">
              <label for="order_code" class="block text-sm font-semibold text-white/90">
                No. Transaksi
              </label>

              <input
                id="order_code"
                name="order_code"
                value="{{ request('order_code') }}"
                type="text"
                autocomplete="off"
                placeholder="Contoh: DIM-260123-ABC123"
                class="mt-2 w-full h-11 rounded-xl bg-white text-slate-900 px-4 outline-none
                       ring-1 ring-white/10 focus:ring-2 focus:ring-yellow-200 focus:border-yellow-300"
              >

              @error('order_code')
                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
              @enderror
            </div>

            <div class="w-full md:w-auto">
              <button
                type="submit"
                class="w-full md:w-auto h-11 px-6 rounded-xl bg-red-700 text-white font-extrabold
                       hover:bg-black active:scale-[0.99] transition"
              >
                CEK PESANAN
              </button>
            </div>
          </form>
        </div>
      </div>

      {{-- HASIL (kalau ketemu) --}}
      @isset($order)
        {{-- ✅ Card hasil jadi hitam (#121212) --}}
        <div class="mt-6 bg-[#121212] rounded-2xl border border-white/10 shadow-lg p-6 text-white">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
              <div class="text-sm text-white/70">No. Transaksi</div>
              <div class="text-lg font-extrabold text-white">{{ $order->order_code }}</div>
            </div>

            <div class="inline-flex items-center gap-2">
              <span class="text-sm text-white/70">Status:</span>
              <span class="px-3 py-1 rounded-full text-sm font-bold
                {{ $order->status === 'success' ? 'bg-emerald-500/15 text-emerald-200 ring-1 ring-emerald-400/20' : '' }}
                {{ $order->status === 'pending' ? 'bg-amber-500/15 text-amber-200 ring-1 ring-amber-400/20' : '' }}
                {{ $order->status === 'failed'  ? 'bg-red-500/15 text-red-200 ring-1 ring-red-400/20' : '' }}
                {{ !in_array($order->status, ['success','pending','failed']) ? 'bg-white/10 text-white ring-1 ring-white/10' : '' }}
              ">
                {{ strtoupper($order->status) }}
              </span>
            </div>
          </div>

          <div class="mt-4 grid md:grid-cols-3 gap-4 text-sm">
            <div class="rounded-xl bg-white/5 p-4 border border-white/10">
              <div class="text-white/70">Game</div>
              <div class="font-semibold text-white">{{ $order->game?->name ?? '-' }}</div>
            </div>
            <div class="rounded-xl bg-white/5 p-4 border border-white/10">
              <div class="text-white/70">Nominal</div>
              <div class="font-semibold text-white">{{ $order->denomination?->name ?? '-' }}</div>
            </div>
            <div class="rounded-xl bg-white/5 p-4 border border-white/10">
              <div class="text-white/70">Total</div>
              <div class="font-semibold text-yellow-300">
                Rp {{ number_format((int) $order->total_amount, 0, ',', '.') }}
              </div>
            </div>
          </div>

          <div class="mt-4">
            <a href="{{ route('invoice.show', $order->order_code) }}"
               class="inline-flex items-center justify-center h-11 px-5 rounded-xl bg-red-700 text-white font-extrabold
                      hover:bg-black transition">
              Lihat Invoice
            </a>
          </div>
        </div>
      @endisset

      {{-- NOT FOUND --}}
      @if(($notFound ?? false) === true)
        <div class="mt-6 bg-[#121212] border border-red-400/30 text-red-200 rounded-2xl p-5">
          Pesanan dengan nomor transaksi <b>{{ request('order_code') }}</b> tidak ditemukan.
        </div>
      @endif

    </div>
  </section>
@endsection
