@extends('layouts.phone')

@section('title', 'Pembayaran')

@section('content')
    <x-app-bar title="Pembayaran" :subtitle="$order['kode']" :back="route('buyer.checkout')" />

    <form action="{{ route('buyer.pembayaran.bayar', $order['id']) }}" method="post" class="flex flex-1 flex-col bg-cream">
        @csrf

        <main class="stagger flex-1 space-y-5 px-5 pb-6 pt-4">
            <section class="relative overflow-hidden rounded-[28px] bg-[linear-gradient(140deg,var(--color-forest-night)_0%,var(--color-forest)_55%,var(--color-leaf)_100%)] p-5 text-white">
                <div class="pointer-events-none absolute -right-10 -top-10 size-44 rounded-full bg-white/5"></div>
                <div class="relative space-y-1">
                    <p class="text-[12px] text-mint/80">Total tagihan</p>
                    <p class="tabular text-[30px] font-semibold leading-tight">Rp {{ number_format($order['total'], 0, ',', '.') }}</p>
                    <p class="text-[12px] text-mint/80">{{ $order['title'] }} · {{ $order['porsi'] }} porsi</p>
                </div>
                <div class="relative mt-4 space-y-1.5 rounded-[18px] bg-white/10 px-4 py-3 text-[12px]">
                    <div class="flex items-center justify-between">
                        <span class="text-mint/80">Subtotal</span>
                        <span class="tabular font-medium">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-mint/80">Biaya layanan</span>
                        <span class="tabular font-medium">Rp 0</span>
                    </div>
                </div>
            </section>

            <section class="space-y-3">
                <p class="text-[15px] font-semibold text-ink">Pilih metode pembayaran</p>
                @foreach ($metode as $method)
                    <label class="surface flex cursor-pointer items-center gap-3 p-3.5 transition has-[:checked]:bg-mint-faint has-[:checked]:ring-2 has-[:checked]:ring-leaf">
                        <input type="radio" name="metode" value="{{ $method['code'] }}" @checked($loop->first) class="peer sr-only" />
                        <span class="grid size-11 shrink-0 place-items-center rounded-2xl bg-mint-soft text-forest"><x-icon :name="$method['icon'] ?? 'wallet'" class="size-5" /></span>
                        <span class="min-w-0 flex-1 space-y-0.5">
                            <span class="block text-body font-semibold text-ink">{{ $method['name'] }}</span>
                            <span class="block text-[12px] text-muted">{{ $method['note'] }}</span>
                        </span>
                        <span class="grid size-5 shrink-0 place-items-center rounded-full border-2 border-line bg-white peer-checked:border-leaf peer-checked:before:size-2.5 peer-checked:before:rounded-full peer-checked:before:bg-leaf"></span>
                    </label>
                @endforeach
            </section>

            <p class="flex items-center justify-center gap-2 text-[12px] text-muted"><x-icon name="lock" class="size-4 text-leaf" />Pembayaran diproses aman dan terenkripsi</p>
        </main>

        <x-action-bar>
            <x-button pill type="submit">Bayar Rp {{ number_format($order['total'], 0, ',', '.') }}</x-button>
        </x-action-bar>
    </form>
@endsection
