@extends('layouts.phone')

@section('title', 'Beranda Kurir')

@php
    $courier = config('demo.courier');
    $tasks = config('demo.tasks');
    $active = $tasks[0];
@endphp

@section('content')
    <x-app-bar title="Halo, {{ $courier['name'] }}" subtitle="{{ $courier['vehicle'] }}">
        <x-slot:right>
            <span class="inline-flex items-center gap-1 rounded-full bg-mint px-2.5 py-1.5 text-[10px] font-bold text-forest">
                <span class="size-1.5 rounded-full bg-leaf"></span>
                Online
            </span>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl bg-forest p-4 text-white">
            <div class="flex items-center justify-between">
                <p class="text-[10px] font-semibold uppercase tracking-wide text-mint/70">Pendapatan hari ini</p>
                <x-badge color="mint">2 tugas selesai</x-badge>
            </div>
            <p class="mt-1 text-[26px] font-extrabold leading-none">Rp 185.000</p>
            <div class="mt-3 grid grid-cols-3 gap-2 border-t border-white/15 pt-3 text-center">
                <div><p class="text-[13px] font-extrabold">3</p><p class="text-[9px] text-mint/70">Tugas</p></div>
                <div><p class="text-[13px] font-extrabold">24,6 km</p><p class="text-[9px] text-mint/70">Jarak</p></div>
                <div><p class="text-[13px] font-extrabold">4.9</p><p class="text-[9px] text-mint/70">Rating</p></div>
            </div>
        </section>

        <section>
          <x-section-title title="Tugas aktif" :href="route('kurir.tugas')" action="Semua tugas" />
          <a href="{{ route('kurir.tugas.detail', $active['id']) }}" class="block overflow-hidden rounded-2xl border border-line bg-white">
              <div class="relative h-28 bg-mint-soft">
                  <div class="absolute inset-0 opacity-60 [background-image:linear-gradient(#c7e6d5_1px,transparent_1px),linear-gradient(90deg,#c7e6d5_1px,transparent_1px)] [background-size:24px_24px]"></div>
                  <span class="absolute left-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-[9px] font-bold text-forest-deep">Pawon Lestari</span>
                  <span class="absolute bottom-3 right-3 rounded-full bg-white/90 px-2.5 py-1 text-[9px] font-bold text-forest-deep">Graha ITS</span>
                  <span class="absolute left-1/2 top-1/2 grid size-9 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full bg-white text-leaf shadow-lg ring-1 ring-line">
                      <x-icon name="bike" class="size-5" />
                  </span>
              </div>
              <div class="p-4">
                  <div class="flex items-start justify-between gap-2">
                      <div class="min-w-0">
                          <p class="text-[13px] font-extrabold text-forest-deep">{{ $active['title'] }}</p>
                          <p class="mt-0.5 text-[10px] text-muted">{{ $active['address'] }}</p>
                      </div>
                      <x-badge color="amber">{{ $active['status'] }}</x-badge>
                  </div>
                  <div class="mt-3 grid grid-cols-3 gap-2 rounded-xl bg-mist p-2 text-center">
                      <div><p class="text-[9px] text-muted">Muatan</p><p class="text-[11px] font-bold text-forest-deep">{{ $active['load'] }}</p></div>
                      <div><p class="text-[9px] text-muted">Jarak</p><p class="text-[11px] font-bold text-forest-deep">{{ $active['distance'] }}</p></div>
                      <div><p class="text-[9px] text-muted">Target</p><p class="text-[11px] font-bold text-forest-deep">10.30</p></div>
                  </div>
                  <div class="mt-3 flex gap-2">
                      <a href="{{ route('kurir.departure') }}" class="flex-1 rounded-xl bg-forest py-2.5 text-center text-[11px] font-bold text-white">Mulai perjalanan</a>
                      <a href="{{ route('kurir.live') }}" class="rounded-xl border border-line px-4 py-2.5 text-center text-[11px] font-bold text-muted">Live</a>
                  </div>
              </div>
          </a>
        </section>

        <section>
            <x-section-title title="Tugas berikutnya" :href="route('kurir.tugas')" action="Lihat semua" />
            <div class="space-y-2">
                @foreach (array_slice($tasks, 1) as $task)
                    <a href="{{ route('kurir.tugas.detail', $task['id']) }}" class="flex items-center gap-3 rounded-2xl border border-line bg-white p-3">
                        <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-mint-soft text-leaf"><x-icon name="package" class="size-5" /></span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[12px] font-bold text-forest-deep">{{ $task['title'] }}</p>
                            <p class="text-[9px] text-muted">{{ $task['load'] }} · {{ $task['eta'] }}</p>
                        </div>
                        <x-icon name="chevron-right" class="size-4 shrink-0 text-muted" />
                    </a>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Status ketersediaan</p>
                <span class="relative h-6 w-11 rounded-full bg-leaf">
                    <span class="absolute right-1 top-1 size-4 rounded-full bg-white shadow"></span>
                </span>
            </div>
            <p class="mt-1 text-[10px] leading-relaxed text-muted">Kamu akan menerima tugas baru dari seller di sekitar Surabaya Timur.</p>
            <div class="mt-3 grid grid-cols-2 gap-2">
                <a href="{{ route('kurir.bukti') }}" class="flex items-center justify-center gap-1.5 rounded-xl border border-line py-2.5 text-[11px] font-bold text-forest-deep">
                    <x-icon name="camera" class="size-4" />
                    Bukti
                </a>
                <a href="{{ route('kurir.tugas') }}" class="flex items-center justify-center gap-1.5 rounded-xl bg-forest py-2.5 text-[11px] font-bold text-white">
                    <x-icon name="package" class="size-4" />
                    Ambil tugas
                </a>
            </div>
        </section>

        <section class="rounded-2xl bg-amber-soft p-3 text-[10px] leading-relaxed text-amber">
            <span class="font-bold">Pengingat:</span> selalu foto muatan sebelum berangkat dan setelah serah terima.
        </section>
    </main>

    <x-bottom-nav role="kurir" active="kurir.beranda" />
@endsection
