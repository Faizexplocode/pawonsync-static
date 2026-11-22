@extends('layouts.phone')

@section('title', 'Live Location')

@section('content')
    <x-app-bar title="Live Location" subtitle="Menuju Graha ITS" :back="route('kurir.departure')">
        <x-slot:right>
            <span class="inline-flex items-center gap-1 rounded-full bg-mint px-2.5 py-1.5 text-[10px] font-bold text-forest">
                <span class="size-1.5 animate-pulse rounded-full bg-leaf"></span>
                Live
            </span>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 px-4 py-4">
        <section class="relative h-72 overflow-hidden rounded-3xl border border-line bg-mint-soft">
            <div class="absolute inset-0 opacity-70 [background-image:linear-gradient(#c7e6d5_1px,transparent_1px),linear-gradient(90deg,#c7e6d5_1px,transparent_1px)] [background-size:30px_30px]"></div>
            <div class="absolute left-[14%] top-[68%] h-1.5 w-[62%] -rotate-12 rounded-full bg-leaf/40"></div>
            <div class="absolute left-[18%] top-[62%] size-3 rounded-full bg-forest ring-4 ring-forest/20"></div>
            <div class="absolute right-[14%] top-[26%] size-3 rounded-full bg-amber ring-4 ring-amber/20"></div>
            <div class="absolute left-[46%] top-[44%] grid size-11 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full bg-forest text-mint shadow-xl ring-4 ring-white">
                <x-icon name="bike" class="size-6" />
            </div>
            <span class="absolute bottom-3 left-3 rounded-full bg-white/95 px-3 py-1.5 text-[9px] font-bold text-forest-deep">Pawon Lestari</span>
            <span class="absolute right-3 top-3 rounded-full bg-white/95 px-3 py-1.5 text-[9px] font-bold text-forest-deep">Graha ITS</span>
            <span class="absolute bottom-3 right-3 rounded-full bg-forest px-3 py-1.5 text-[9px] font-bold text-mint">3,2 km lagi</span>
        </section>

        <section class="mt-4 rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center gap-3">
                <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-mint text-forest">
                    <x-icon name="navigation" class="size-5" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-semibold text-muted">Belok kanan dalam 300 m</p>
                    <p class="text-[13px] font-extrabold text-forest-deep">Jl. Arief Rahman Hakim</p>
                </div>
                <span class="text-right">
                    <span class="block text-[9px] text-muted">ETA</span>
                    <span class="block text-[13px] font-extrabold text-forest">10.28</span>
                </span>
            </div>
            <div class="mt-3"><x-progress-bar :value="74" /></div>
            <div class="mt-1.5 flex items-center justify-between text-[9px] font-semibold text-muted">
                <span>Sudah 9,2 km</span>
                <span>Sisa 3,2 km · 8 menit</span>
            </div>
        </section>

        <section class="mt-3 grid grid-cols-2 gap-2">
            <x-stat-card label="Kecepatan" value="32 km/j" sub="rata-rata" />
            <x-stat-card label="Muatan" value="150 box" sub="4 karung" tone="dark" />
        </section>

        <section class="mt-3 rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Penerima</p>
            <div class="mt-2 flex items-center gap-3">
                <span class="grid size-10 place-items-center rounded-full bg-mint text-[12px] font-extrabold text-forest">BD</span>
                <div class="min-w-0 flex-1">
                    <p class="text-[12px] font-bold text-ink">Bu Diana</p>
                    <p class="text-[9px] text-muted">0812 3456 7890 · Graha ITS, lobi utama</p>
                </div>
                <a href="#" class="grid size-9 place-items-center rounded-full bg-mint-soft text-forest"><x-icon name="phone" class="size-4" /></a>
            </div>
        </section>

        <section class="mt-3 rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Update lokasi otomatis</p>
                <span class="relative h-6 w-11 rounded-full bg-leaf">
                    <span class="absolute right-1 top-1 size-4 rounded-full bg-white shadow"></span>
                </span>
            </div>
            <p class="mt-1 text-[9px] leading-relaxed text-muted">Buyer &amp; seller dapat melihat posisimu setiap 10 detik.</p>
        </section>

        <div class="mt-3 grid grid-cols-2 gap-2">
            <a href="#" class="flex items-center justify-center gap-1.5 rounded-2xl border border-line bg-white py-3.5 text-[12px] font-bold text-forest-deep">
                <x-icon name="alert" class="size-4" />
                Lapor kendala
            </a>
            <a href="{{ route('kurir.bukti') }}" class="flex items-center justify-center gap-1.5 rounded-2xl bg-forest py-3.5 text-[12px] font-bold text-white">
                <x-icon name="check" class="size-4" />
                Sudah tiba
            </a>
        </div>
    </main>

    <x-bottom-nav role="kurir" active="kurir.live" />
@endsection
