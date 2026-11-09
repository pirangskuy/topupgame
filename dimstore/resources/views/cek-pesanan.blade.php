{{-- resources/views/cek-pesanan.blade.php --}}
@extends('layouts.app') {{-- ganti sesuai layout kamu --}}

@php
  $title = 'Cek Pesanan - Dimstore';
  $trx = request('trx');
@endphp

@section('content')
  <section class="w-full">
    <div class="max-w-7xl mx-auto px-4 py-10">
      <h1 class="text-xl font-extrabold text-slate-900">Cek Status Pesanan</h1>

      <div class="mt-5 bg-slate-900 rounded-xl shadow-lg border border-white/10 overflow-hidden">
        <div class="p-6">
          <form method="GET" action="{{ url('/cek-pesanan') }}"
                class="flex flex-col md:flex-row md:items-end gap-4">

            <div class="w-full flex-1">
              <label for="trx" class="block text-sm font-semibold text-slate-200">
                No. Transaksi
              </label>

              <input
                id="trx"
                name="trx"
                value="{{ $trx }}"
                type="text"
                inputmode="text"
                autocomplete="off"
                placeholder="Contoh: DS-2026-000123"
                class="mt-2 w-full h-11 rounded-md bg-white text-slate-900 px-3 outline-none
                       ring-1 ring-slate-200 focus:ring-2 focus:ring-red-500"
              >

              @error('trx')
                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
              @enderror
            </div>

            <div class="w-full md:w-auto">
              <button
                type="submit"
                class="w-full md:w-auto h-11 px-6 rounded-md bg-red-700 text-white font-extrabold
                       hover:bg-red-800 active:scale-[0.99] transition"
              >
                CEK PESANAN
              </button>
            </div>
          </form>
        </div>
      </div>

      {{-- OPSIONAL: area hasil (kalau kamu kirim $order dari controller) --}}
      @isset($order)
        <div class="mt-6 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
              <div class="text-sm text-slate-500">No. Transaksi</div>
              <div class="text-lg font-extrabold text-slate-900">{{ $order->trx_id ?? '-' }}</div>
            </div>

            <div class="inline-flex items-center gap-2">
              <span class="text-sm text-slate-500">Status:</span>
              <span class="px-3 py-1 rounded-full text-sm font-bold
                {{ ($order->status ?? '') === 'success' ? 'bg-emerald-100 text-emerald-700' : '' }}
                {{ ($order->status ?? '') === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                {{ ($order->status ?? '') === 'failed'  ? 'bg-red-100 text-red-700' : '' }}
                {{ !in_array(($order->status ?? ''), ['success','pending','failed']) ? 'bg-slate-100 text-slate-700' : '' }}
              ">
                {{ strtoupper($order->status ?? 'UNKNOWN') }}
              </span>
            </div>
          </div>

          <div class="mt-4 grid md:grid-cols-3 gap-4 text-sm">
            <div class="rounded-lg bg-slate-50 p-4 border border-slate-100">
              <div class="text-slate-500">Produk</div>
              <div class="font-semibold text-slate-900">{{ $order->product_name ?? '-' }}</div>
            </div>
            <div class="rounded-lg bg-slate-50 p-4 border border-slate-100">
              <div class="text-slate-500">Tujuan</div>
              <div class="font-semibold text-slate-900">{{ $order->target ?? '-' }}</div>
            </div>
            <div class="rounded-lg bg-slate-50 p-4 border border-slate-100">
              <div class="text-slate-500">Total</div>
              <div class="font-semibold text-slate-900">
                {{ isset($order->total) ? 'Rp ' . number_format((int)$order->total,0,',','.') : '-' }}
              </div>
            </div>
          </div>
        </div>
      @endisset

      @if(request()->filled('trx') && !isset($order) && isset($notFound) && $notFound)
        <div class="mt-6 bg-red-50 border border-red-200 text-red-700 rounded-xl p-5">
          Pesanan dengan nomor transaksi <b>{{ $trx }}</b> tidak ditemukan.
        </div>
      @endif
    </div>
  </section>
@endsection
