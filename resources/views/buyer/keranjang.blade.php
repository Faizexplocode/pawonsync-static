@extends('layouts.phone')

@section('title', 'Keranjang')

@section('content')
    <x-app-bar title="Keranjang" :subtitle="$kosong ? 'Belum ada item' : count($items).' item dari 1 dapur'" :back="route('buyer.katalog')" />

    <main class="flex-1 space-y-3.5 p-4">
        @if ($kosong)
            <x-empty-state title="Keranjang masih kosong">Pilih menu katering favoritmu terlebih dahulu.</x-empty-state>
            <x-button :href="route('buyer.katalog')">Lihat katalog</x-button>
        @else
            <section class="space-y-3 rounded-[18px] border border-line bg-white px-4 py-3.5">
                <div class="flex items-center gap-2.5">
                    <span class="grid size-8 shrink-0 place-items-center rounded-full bg-forest text-[11.5px] font-bold text-white">{{ strtoupper(substr($items[0]['seller'], 0, 2)) }}</span>
                    <p class="min-w-0 flex-1 truncate text-body font-bold text-ink">{{ $items[0]['seller'] }}</p>
                    <x-badge color="green">Verified</x-badge>
                </div>

                @foreach ($items as $item)
                    <div class="h-px bg-line"></div>
                    <div class="flex items-start gap-3">
                        <x-photo :src="$item['photo']" label="Foto" class="size-[76px] shrink-0 rounded-[14px]" />
                        <div class="min-w-0 flex-1 space-y-1">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-body font-semibold text-ink">{{ $item['menu_nama'] }}</p>
                                <form action="{{ route('buyer.keranjang.hapus') }}" method="post" class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="key" value="{{ $item['key'] }}" />
                                    <button type="submit" class="text-caption font-semibold text-danger">Hapus</button>
                                </form>
                            </div>
                            @if ($item['paket_nama'] || count($item['opsi_nama']) > 0)
                                <p class="text-[11px] text-muted">{{ collect([$item['paket_nama'], ...$item['opsi_nama']])->filter()->implode(' · ') }}</p>
                            @endif
                            <p class="text-caption font-medium text-muted">Rp {{ number_format($item['price'], 0, ',', '.') }} / porsi</p>
                            <form action="{{ route('buyer.keranjang.update') }}" method="post" class="flex items-center justify-between">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="key" value="{{ $item['key'] }}" />
                                <x-qty-stepper :value="$item['qty']" :submit-on-change="true" />
                                <span class="text-[13px] font-bold text-forest">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </form>
                        </div>
                    </div>
                @endforeach
            </section>

            <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
                <p class="text-body font-bold text-ink">Ringkasan belanja</p>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Subtotal ({{ $porsi }} porsi)</span>
                    <span class="font-semibold text-ink">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Estimasi ongkir &amp; layanan</span>
                    <span class="font-semibold text-ink">Dihitung di checkout</span>
                </div>
                <div class="h-px bg-line"></div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Total sementara</span>
                    <span class="font-bold text-forest">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </section>
        @endif
    </main>

    @unless ($kosong)
        <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
            <x-button :href="route('buyer.checkout')">Checkout ({{ count($items) }})</x-button>
        </div>
    @endunless
@endsection
