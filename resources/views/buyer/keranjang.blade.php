@extends('layouts.phone')

@section('title', 'Keranjang')

@section('content')
    <x-app-bar title="Keranjang" :subtitle="$kosong ? 'Belum ada item' : count($items).' item dari 1 dapur'" :back="route('buyer.katalog')" />

    <main class="stagger flex-1 space-y-4 bg-cream px-5 pb-6 pt-4">
        @if ($kosong)
            <div class="flex flex-col items-center gap-3 rounded-[28px] bg-white/70 px-6 py-10 text-center">
                <span class="relative grid size-20 place-items-center">
                    <span class="absolute inset-2 rounded-full bg-mint animate-ripple"></span>
                    <span class="relative grid size-16 place-items-center rounded-full bg-mint-soft text-forest animate-float"><x-icon name="cart" class="size-8" /></span>
                </span>
                <p class="text-[16px] font-semibold text-ink">Keranjang masih kosong</p>
                <p class="text-[13px] text-muted">Pilih menu katering favoritmu terlebih dahulu.</p>
            </div>
            <x-button pill :href="route('buyer.katalog')">Lihat katalog</x-button>
        @else
            <div class="flex items-center gap-3">
                <span class="grid size-10 shrink-0 place-items-center rounded-full bg-mint-soft text-[13px] font-semibold text-forest">{{ strtoupper(substr($items[0]['seller'], 0, 2)) }}</span>
                <p class="min-w-0 flex-1 truncate text-[15px] font-semibold text-ink">{{ $items[0]['seller'] }}</p>
                <x-badge color="green">Verified</x-badge>
            </div>

            <div class="space-y-3">
                @foreach ($items as $item)
                    <section class="surface space-y-3 p-3">
                        <div class="flex items-start gap-3">
                            <x-photo :src="$item['photo']" label="Foto" class="size-[76px] shrink-0 rounded-[18px]" />
                            <div class="min-w-0 flex-1 space-y-1">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-body font-semibold leading-snug text-ink">{{ $item['menu_nama'] }}</p>
                                    <form action="{{ route('buyer.keranjang.hapus') }}" method="post" class="shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="key" value="{{ $item['key'] }}" />
                                        <button type="submit" class="grid size-8 place-items-center rounded-full bg-danger-soft text-danger" aria-label="Hapus {{ $item['menu_nama'] }}"><x-icon name="trash" class="size-4" /></button>
                                    </form>
                                </div>
                                @if ($item['paket_nama'] || count($item['opsi_nama']) > 0)
                                    <p class="text-[11px] text-muted">{{ collect([$item['paket_nama'], ...$item['opsi_nama']])->filter()->implode(' · ') }}</p>
                                @endif
                                <p class="tabular text-[12px] text-muted">Rp {{ number_format($item['price'], 0, ',', '.') }} / porsi</p>
                            </div>
                        </div>
                        <form action="{{ route('buyer.keranjang.update') }}" method="post" class="flex items-center justify-between gap-2 border-t border-line/70 pt-3">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="key" value="{{ $item['key'] }}" />
                            <x-qty-stepper :value="$item['qty']" :submit-on-change="true" />
                            <span class="tabular text-[15px] font-semibold text-forest">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </form>
                    </section>
                @endforeach
            </div>

            <section class="surface space-y-2.5 p-4">
                <p class="text-[15px] font-semibold text-ink">Ringkasan belanja</p>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Subtotal ({{ $porsi }} porsi)</span>
                    <span class="tabular font-semibold text-ink">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Estimasi ongkir &amp; layanan</span>
                    <span class="font-semibold text-ink">Dihitung di checkout</span>
                </div>
                <div class="h-px bg-line"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[13px] text-muted">Total sementara</span>
                    <span class="tabular text-[18px] font-semibold text-forest">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </section>
        @endif
    </main>

    @unless ($kosong)
        <x-action-bar>
            <x-button pill :href="route('buyer.checkout')">Checkout ({{ count($items) }})</x-button>
        </x-action-bar>
    @endunless
@endsection
