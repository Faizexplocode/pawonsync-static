@extends('layouts.phone')

@section('title', 'Update Milestone')

@php
    $milestones = [
        ['code' => 'M1', 'title' => 'Bahan diterima', 'desc' => 'Belanja bahan selesai & dicek kualitasnya', 'time' => '11 Okt 06.00', 'done' => true],
        ['code' => 'M2', 'title' => 'Produksi dimulai', 'desc' => 'Dapur mulai memasak 150 porsi', 'time' => '11 Okt 09.00', 'done' => true],
        ['code' => 'M3', 'title' => 'Packing selesai', 'desc' => 'Semua box tertutup & berlabel', 'time' => '12 Okt 08.30', 'done' => false],
        ['code' => 'M4', 'title' => 'Siap diambil kurir', 'desc' => 'Pesanan menunggu di departure gate', 'time' => '12 Okt 09.30', 'done' => false],
        ['code' => 'M5', 'title' => 'Terkirim', 'desc' => 'Kurir menyerahkan ke buyer', 'time' => '12 Okt 10.30', 'done' => false],
    ];
@endphp

@section('content')
    <x-app-bar title="Update Milestone" subtitle="Wisuda ITS · 150 porsi" :back="route('seller.pesanan.detail', 'wisuda-its')" />

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Progres pesanan</p>
                <span class="text-[10px] font-bold text-leaf">2 dari 5 selesai</span>
            </div>
            <div class="mt-2"><x-progress-bar :value="40" /></div>
            <p class="mt-2 text-[10px] text-muted">Target selesai: 12 Okt 2026, 10.30 WIB</p>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Pilih milestone</p>
            <div class="mt-3 space-y-2">
                @foreach ($milestones as $milestone)
                    <label @class([
                        'flex cursor-pointer items-start gap-3 rounded-xl border p-3',
                        'border-leaf bg-mint-soft' => $loop->index === 2,
                        'border-line bg-white' => $loop->index !== 2,
                    ])>
                        <span @class([
                            'mt-0.5 grid size-5 shrink-0 place-items-center rounded-full text-white',
                            'bg-leaf' => $milestone['done'],
                            'border-2 border-leaf bg-white' => ! $milestone['done'] && $loop->index === 2,
                            'border-2 border-line bg-white' => ! $milestone['done'] && $loop->index !== 2,
                        ])>
                            @if ($milestone['done'])
                                <x-icon name="check" class="size-3" />
                            @elseif ($loop->index === 2)
                                <span class="size-2 rounded-full bg-leaf"></span>
                            @endif
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-bold {{ $milestone['done'] ? 'text-forest-deep' : 'text-ink' }}">{{ $milestone['title'] }}</span>
                                <span class="shrink-0 text-[9px] font-semibold text-muted">{{ $milestone['time'] }}</span>
                            </span>
                            <span class="mt-0.5 block text-[9px] leading-relaxed text-muted">{{ $milestone['desc'] }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Bukti milestone</p>
            <div class="mt-2 grid grid-cols-3 gap-2">
                <x-photo label="Foto dapur" class="h-20" />
                <x-photo label="Foto box" class="h-20" />
                <x-photo label="+ Foto" class="h-20 border border-dashed border-line bg-white text-muted" />
            </div>
            <label class="mt-3 block">
                <span class="mb-1.5 block text-[11px] font-semibold text-forest-deep">Catatan untuk buyer</span>
                <textarea rows="3" placeholder="Contoh: packing selesai 08.20, menunggu kurir..." class="w-full rounded-xl border border-line bg-mist px-3 py-2.5 text-[11px] leading-relaxed text-ink outline-none placeholder:text-muted/70 focus:border-leaf focus:ring-2 focus:ring-mint"></textarea>
            </label>
        </section>

        <label class="flex items-start gap-2 rounded-2xl bg-white p-3 text-[10px] leading-relaxed text-muted ring-1 ring-line">
            <input type="checkbox" checked class="mt-0.5 size-4 rounded border-line text-leaf focus:ring-mint" />
            Kirim notifikasi otomatis ke buyer saat milestone diperbarui.
        </label>

        <a href="{{ route('seller.pesanan.detail', 'wisuda-its') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[13px] font-bold text-white">
            <x-icon name="check" class="size-4" />
            Simpan &amp; update buyer
        </a>
        <a href="{{ route('seller.packaging') }}" class="flex items-center justify-center gap-2 rounded-2xl border border-line bg-white px-4 py-3 text-[12px] font-bold text-forest-deep">
            <x-icon name="package" class="size-4" />
            Lanjut ke packaging
        </a>
    </main>
@endsection
