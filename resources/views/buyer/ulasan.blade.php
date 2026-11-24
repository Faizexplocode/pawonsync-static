@extends('layouts.phone')

@section('title', 'Beri Ulasan')

@section('content')
    <x-app-bar title="Beri Ulasan" :subtitle="$order['title'].' - '.$order['seller']" :back="route('buyer.pesanan.detail', $order['id'])" />

    <form action="{{ route('buyer.ulasan.store', $order['id']) }}" method="post" class="flex flex-1 flex-col">
        @csrf

        <main class="flex-1 space-y-3.5 p-4">
            <h2 class="text-[18px] font-bold text-ink">Bagaimana pesananmu?</h2>
            <p class="text-caption text-muted">{{ $order['porsi'] }} porsi · {{ $order['tanggal'] }} · Ulasanmu membantu seller berkembang</p>

            <section class="space-y-1 rounded-[18px] border border-line bg-white px-4 py-3.5">
                <div class="flex items-center justify-between">
                    <p class="text-[13px] font-medium text-muted">Penilaian keseluruhan</p>
                    <x-badge color="green">Ketuk bintang</x-badge>
                </div>
                <div class="flex flex-row-reverse justify-end text-[28px] leading-none [&_input:checked~label]:text-amber">
                    @foreach (range(5, 1) as $star)
                        <input type="radio" id="rating-{{ $star }}" name="rating" value="{{ $star }}" @checked($star === 5) class="peer sr-only" />
                        <label for="rating-{{ $star }}" class="cursor-pointer text-line">★</label>
                    @endforeach
                </div>
            </section>

            <label class="block">
                <span class="mb-1.5 block text-caption font-medium text-muted">Komentar (opsional)</span>
                <textarea name="komentar" rows="5" maxlength="2000" placeholder="Tulis pengalamanmu di sini..." class="w-full rounded-xl border border-line bg-white px-3.5 py-[13px] text-body text-ink outline-none placeholder:text-muted/70 focus:border-leaf">{{ old('komentar') }}</textarea>
            </label>

            <x-alert tone="info">Ulasan tampil atas nama {{ $order['buyer'] }} dan membantu seller meningkatkan layanan.</x-alert>
        </main>

        <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
            <x-button type="submit">Kirim Ulasan</x-button>
        </div>
    </form>
@endsection
