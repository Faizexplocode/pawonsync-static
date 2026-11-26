@extends('layouts.phone')

@section('title', 'Beri Ulasan')

@section('content')
    <x-app-bar title="Beri Ulasan" :subtitle="$order['title'].' - '.$order['seller']" :back="route('buyer.pesanan.detail', $order['id'])" />

    <form action="{{ route('buyer.ulasan.store', $order['id']) }}" method="post" class="flex flex-1 flex-col bg-cream">
        @csrf

        <main class="stagger flex-1 space-y-4 px-5 pb-6 pt-4">
            <section class="surface flex flex-col items-center gap-3 px-5 py-7 text-center">
                <span class="grid size-16 place-items-center rounded-full bg-mint-soft text-[18px] font-semibold text-forest animate-float">{{ strtoupper(substr($order['seller'], 0, 2)) }}</span>
                <div class="space-y-1">
                    <h2 class="text-[18px] font-semibold text-ink">Bagaimana pesananmu?</h2>
                    <p class="text-[12px] text-muted">{{ $order['porsi'] }} porsi · {{ $order['tanggal'] }}</p>
                </div>
                <div class="flex flex-row-reverse justify-center gap-1 text-[40px] leading-none [&_input:checked~label]:text-amber">
                    @foreach (range(5, 1) as $star)
                        <input type="radio" id="rating-{{ $star }}" name="rating" value="{{ $star }}" @checked($star === 5) class="peer sr-only" />
                        <label for="rating-{{ $star }}" class="cursor-pointer text-line transition active:scale-90" aria-label="{{ $star }} bintang">★</label>
                    @endforeach
                </div>
                <p class="text-[12px] text-muted">Ketuk bintang untuk menilai</p>
            </section>

            <label class="block">
                <span class="mb-2 block text-[15px] font-semibold text-ink">Komentar <span class="text-[12px] font-normal text-muted">(opsional)</span></span>
                <textarea name="komentar" rows="5" maxlength="2000" placeholder="Tulis pengalamanmu di sini..." class="w-full rounded-[20px] border border-line bg-white px-4 py-3.5 text-body text-ink outline-none transition placeholder:text-muted/70 focus:border-leaf focus:ring-2 focus:ring-leaf/20">{{ old('komentar') }}</textarea>
            </label>

            <x-alert tone="info">Ulasan tampil atas nama {{ $order['buyer'] }} dan membantu seller meningkatkan layanan.</x-alert>
        </main>

        <x-action-bar>
            <x-button pill type="submit">Kirim Ulasan</x-button>
        </x-action-bar>
    </form>
@endsection
