@extends('layouts.phone')

@section('title', 'Beri Ulasan')

@php
    $order = config('demo.orders')[0];
@endphp

@section('content')
    <x-app-bar title="Beri Ulasan" subtitle="Pesanan selesai" :back="route('buyer.tiba')" />

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl border border-line bg-white p-4 text-center">
            <div class="mx-auto grid size-14 place-items-center rounded-full bg-mint text-[15px] font-extrabold text-forest">PL</div>
            <p class="mt-2 text-[13px] font-bold text-forest-deep">{{ $order['title'] }}</p>
            <p class="text-[10px] text-muted">Pawon Lestari · 150 porsi</p>
            <div class="mt-3 flex justify-center gap-1 text-[26px] leading-none text-amber">
                @foreach (range(1, 5) as $star)
                    <span>{{ $star <= 5 ? '★' : '☆' }}</span>
                @endforeach
            </div>
            <p class="mt-1 text-[11px] font-semibold text-forest">Sangat memuaskan</p>
        </section>

        <section>
            <p class="mb-2 text-[12px] font-bold text-forest-deep">Apa yang paling berkesan?</p>
            <div class="flex flex-wrap gap-2">
                @foreach (['Rasa enak', 'Tepat waktu', 'Porsi pas', 'Kemasan rapi', 'Kurir ramah', 'Harga worth it'] as $tag)
                    <x-chip :active="$loop->index < 2" href="#">{{ $tag }}</x-chip>
                @endforeach
            </div>
        </section>

        <section>
            <p class="mb-2 text-[12px] font-bold text-forest-deep">Tulis ulasan</p>
            <textarea rows="4" placeholder="Ceritakan pengalamanmu memesan di PawonSync..." class="w-full rounded-2xl border border-line bg-white px-4 py-3 text-[12px] leading-relaxed text-ink outline-none placeholder:text-muted/70 focus:border-leaf focus:ring-2 focus:ring-mint"></textarea>
        </section>

        <section>
            <p class="mb-2 text-[12px] font-bold text-forest-deep">Tambah foto (opsional)</p>
            <div class="grid grid-cols-4 gap-2">
                <x-photo label="Foto" class="h-16" />
                <x-photo label="Foto" class="h-16" />
                <x-photo label="+ Foto" class="h-16 border border-dashed border-line bg-white text-muted" />
            </div>
        </section>

        <label class="flex items-center gap-2 rounded-2xl bg-white p-3 text-[11px] text-muted ring-1 ring-line">
            <input type="checkbox" checked class="size-4 rounded border-line text-leaf focus:ring-mint" />
            Tampilkan nama saya sebagai Bu Diana
        </label>

        <a href="{{ route('buyer.pesanan') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[14px] font-bold text-white">
            Kirim ulasan
            <x-icon name="arrow-right" class="size-4" />
        </a>
        <a href="{{ route('buyer.pesanan') }}" class="block text-center text-[11px] font-semibold text-muted">Nanti saja</a>
    </main>
@endsection
