@extends('layouts.phone')

@section('title', 'Penugasan Kurir')

@php
    $couriers = [
        ['name' => 'Budi Santoso', 'vehicle' => 'Motor box · L 1234 AB', 'rating' => '4.9', 'distance' => '1,8 km', 'active' => true, 'status' => 'Tersedia'],
        ['name' => 'Agus Prasetyo', 'vehicle' => 'Motor box · L 8891 CD', 'rating' => '4.8', 'distance' => '3,2 km', 'active' => false, 'status' => 'Tersedia'],
        ['name' => 'Rizky Hidayat', 'vehicle' => 'Pickup · L 9012 EF', 'rating' => '4.7', 'distance' => '5,6 km', 'active' => false, 'status' => 'Sibuk'],
    ];
@endphp

@section('content')
    <x-app-bar title="Departure Gate" subtitle="Wisuda ITS · 150 box" :back="route('seller.packaging')">
        <x-slot:right>
            <span class="inline-flex items-center gap-1 rounded-full bg-mint px-2.5 py-1.5 text-[10px] font-bold text-forest">
                <span class="size-1.5 rounded-full bg-leaf"></span>
                Siap
            </span>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-semibold text-muted">Pesanan siap diambil</p>
                    <p class="text-[16px] font-extrabold text-forest-deep">150 box · 4 karung</p>
                </div>
                <span class="grid size-11 place-items-center rounded-2xl bg-mint text-forest"><x-icon name="package" class="size-5" /></span>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-2 text-[10px]">
                <div class="rounded-xl bg-mist p-2.5">
                    <p class="font-semibold text-muted">Tujuan</p>
                    <p class="mt-0.5 font-bold text-forest-deep">Graha ITS</p>
                </div>
                <div class="rounded-xl bg-mist p-2.5">
                    <p class="font-semibold text-muted">Target tiba</p>
                    <p class="mt-0.5 font-bold text-forest-deep">10.30 WIB</p>
                </div>
            </div>
        </section>

        <section>
            <x-section-title title="Pilih kurir" action="Lihat semua" href="#" />
            <div class="space-y-2">
                @foreach ($couriers as $courier)
                    <label @class([
                        'flex cursor-pointer items-center gap-3 rounded-2xl border p-3',
                        'border-leaf bg-mint-soft' => $courier['active'],
                        'border-line bg-white' => ! $courier['active'],
                    ])>
                        <span class="grid size-10 shrink-0 place-items-center rounded-full bg-white text-[12px] font-extrabold text-forest ring-1 ring-line">
                            {{ strtoupper(substr($courier['name'], 0, 2)) }}
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="flex items-center gap-1.5">
                                <span class="text-[12px] font-bold text-ink">{{ $courier['name'] }}</span>
                                <span class="text-[9px] font-semibold text-amber">★ {{ $courier['rating'] }}</span>
                            </span>
                            <span class="block text-[9px] text-muted">{{ $courier['vehicle'] }} · {{ $courier['distance'] }}</span>
                            <span @class([
                                'mt-1 inline-block rounded-full px-2 py-0.5 text-[8px] font-bold',
                                'bg-mint text-forest' => $courier['status'] === 'Tersedia',
                                'bg-line text-muted' => $courier['status'] !== 'Tersedia',
                            ])>{{ $courier['status'] }}</span>
                        </span>
                        <span @class(['grid size-5 shrink-0 place-items-center rounded-full border-2', 'border-leaf bg-leaf text-white' => $courier['active'], 'border-line' => ! $courier['active']])>
                            @if ($courier['active'])<x-icon name="check" class="size-3" />@endif
                        </span>
                    </label>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Instruksi untuk kurir</p>
            <textarea rows="3" placeholder="Contoh: masuk lewat gerbang barat, hubungi Pak Reza di lobi..." class="mt-2 w-full rounded-xl border border-line bg-mist px-3 py-2.5 text-[11px] leading-relaxed text-ink outline-none placeholder:text-muted/70 focus:border-leaf focus:ring-2 focus:ring-mint"></textarea>
            <div class="mt-3 flex items-center justify-between rounded-xl bg-mist p-3">
                <div>
                    <p class="text-[10px] font-bold text-forest-deep">Ongkir ditanggung buyer</p>
                    <p class="text-[9px] text-muted">Rp 85.000 · sudah dibayar</p>
                </div>
                <x-badge color="green">Lunas</x-badge>
            </div>
        </section>

        <section class="rounded-2xl border border-dashed border-line p-4">
            <div class="flex items-center gap-2 text-[10px] font-semibold text-muted">
                <x-icon name="info" class="size-4 shrink-0 text-leaf" />
                Kurir akan menerima notifikasi dan wajib scan QR di departure gate sebelum berangkat.
            </div>
        </section>

        <a href="{{ route('seller.pesanan.detail', 'wisuda-its') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[13px] font-bold text-white">
            <x-icon name="check" class="size-4" />
            Tugaskan {{ $couriers[0]['name'] }}
        </a>
    </main>
@endsection
