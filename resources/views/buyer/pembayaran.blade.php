@extends('layouts.phone')

@section('title', 'Pembayaran')

@section('content')
    <x-app-bar title="Pembayaran" :subtitle="$order['kode']" :back="route('buyer.checkout')" />

    <form action="{{ route('buyer.pembayaran.bayar', $order['id']) }}" method="post" class="flex flex-1 flex-col">
        @csrf

        <main class="flex-1 space-y-3.5 p-4">
            <section class="space-y-1.5 rounded-[20px] bg-[linear-gradient(158deg,var(--color-forest-deep)_0%,var(--color-leaf)_71.4%)] px-[18px] py-4">
                <p class="text-caption font-medium text-mint">Total tagihan</p>
                <p class="text-[28px] font-bold text-white">Rp {{ number_format($order['total'], 0, ',', '.') }}</p>
                <p class="text-[11px] text-mint">{{ $order['title'] }} · {{ $order['porsi'] }} porsi</p>
            </section>

            <section class="space-y-1.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Subtotal</span>
                    <span class="font-semibold text-ink">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Biaya layanan</span>
                    <span class="font-semibold text-ink">Rp 0</span>
                </div>
                <div class="h-px bg-line"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[13px] font-semibold text-ink">Dibayar sekarang</span>
                    <span class="text-[20px] font-bold text-forest">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                </div>
            </section>

            <p class="text-body font-bold text-ink">Pilih metode pembayaran</p>
            @foreach ($metode as $method)
                <label class="flex cursor-pointer items-center gap-3 rounded-[14px] border border-line bg-white px-3.5 py-3 has-[:checked]:border-[1.5px] has-[:checked]:border-leaf has-[:checked]:bg-mint-faint">
                    <input type="radio" name="metode" value="{{ $method['code'] }}" @checked($loop->first) class="peer sr-only" />
                    <span class="grid size-[18px] shrink-0 place-items-center rounded-full border-2 border-line bg-white peer-checked:border-leaf peer-checked:before:size-2 peer-checked:before:rounded-full peer-checked:before:bg-leaf"></span>
                    <span class="min-w-0 flex-1 space-y-0.5">
                        <span class="block text-body font-semibold text-ink">{{ $method['name'] }}</span>
                        <span class="block text-caption text-muted">{{ $method['note'] }}</span>
                    </span>
                </label>
            @endforeach

            <x-badge color="green" class="whitespace-normal">Pembayaran diproses aman dan terenkripsi</x-badge>
        </main>

        <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
            <x-button type="submit">Bayar Rp {{ number_format($order['total'], 0, ',', '.') }}</x-button>
        </div>
    </form>
@endsection
