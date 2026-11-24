@extends('layouts.phone')

@section('title', 'Beranda Buyer')

@section('content')
    <header class="bg-[linear-gradient(150deg,var(--color-forest-deep)_0%,var(--color-leaf)_71.4%)]">
        <x-status-bar tone="light" />
        <div class="flex items-center gap-3 px-4 pb-4 pt-2.5">
            <div class="min-w-0 flex-1">
                <h1 class="truncate text-[18px] font-bold text-white">Halo, {{ $buyer->nama }}</h1>
                <p class="mt-0.5 truncate text-caption text-mint">
                    {{ $alamat ? 'Kirim ke: '.$alamat : 'Pesan katering untuk acara Anda' }}
                </p>
            </div>
            <a href="{{ route('buyer.notifikasi') }}" class="shrink-0 text-caption font-semibold text-mint">{{ $notif }} notif</a>
        </div>
        <div class="flex items-center gap-2.5 px-4 pb-[18px]">
            <a href="{{ route('buyer.katalog') }}" class="flex h-11 flex-1 items-center gap-2.5 rounded-[14px] border border-line bg-white px-3.5 text-[13px] text-muted">
                <x-icon name="search" class="size-3.5 shrink-0" />
                <span class="truncate">Cari nasi box, prasmanan, tumpeng...</span>
            </a>
            <a href="{{ route('buyer.filter') }}" class="rounded-[14px] bg-mint px-3.5 py-3 text-caption font-bold text-forest-deep">Filter</a>
        </div>
    </header>

    <main class="flex-1 space-y-4 p-4">
        <section class="relative flex flex-col items-start gap-1.5 overflow-hidden rounded-[20px] bg-[linear-gradient(152deg,var(--color-forest-deep)_0%,var(--color-leaf-soft)_71.4%)] px-[18px] py-4">
            <div class="pointer-events-none absolute -top-[30px] left-[230px] size-[150px] rounded-full bg-white/10"></div>
            <x-badge color="mint">{{ $promo['badge'] }}</x-badge>
            <p class="text-[18px] font-bold leading-6 text-white">{{ $promo['title'] }}</p>
            <p class="text-caption text-mint">{{ $promo['note'] }}</p>
            <a href="{{ route('buyer.katalog') }}" class="rounded-full bg-white px-3.5 py-2 text-caption font-bold text-forest">Lihat promo</a>
        </section>

        <section>
            <x-section-title title="Kategori" />
            <div class="flex items-start justify-between">
                @foreach ($categories as $category)
                    <a href="{{ route('buyer.katalog') }}" class="flex flex-1 flex-col items-center gap-1.5">
                        <span class="grid size-[52px] place-items-center rounded-[18px] bg-mint-soft text-caption font-bold text-forest">{{ $category['code'] }}</span>
                        <span class="whitespace-nowrap text-[11px] font-medium text-ink">{{ $category['name'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section>
            <x-section-title title="Pesanan Aktif" :href="route('buyer.pesanan')" action="Lihat Semua" />
            @if ($order)
                <a href="{{ route('buyer.pesanan.detail', $order['id']) }}" class="block space-y-2.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
                    <div class="flex items-center justify-between gap-2">
                        <p class="truncate text-title font-bold text-ink">{{ $order['title'] }}</p>
                        <x-badge :color="$order['tone']" class="shrink-0">{{ $order['status'] }}</x-badge>
                    </div>
                    <p class="text-caption text-muted">{{ $order['meta'] }}</p>
                    <x-progress-bar :value="$order['progress'] / $order['steps'] * 100" />
                    <div class="flex items-center justify-between text-[11px] font-medium">
                        <span class="text-leaf-dark">{{ $order['progress'] }} dari {{ $order['steps'] }} tahap selesai</span>
                        <span class="text-muted">{{ $order['eta'] }}</span>
                    </div>
                </a>
            @else
                <a href="{{ route('buyer.katalog') }}" class="block rounded-[18px] border border-dashed border-line bg-white p-4 text-center text-caption text-muted">
                    Belum ada pesanan aktif. Yuk pilih menu untuk acara berikutnya.
                </a>
            @endif
        </section>

        <section>
            <x-section-title title="Rekomendasi untuk Anda" :href="route('buyer.katalog')" action="Lihat Semua" />
            <div class="grid grid-cols-2 items-start gap-3">
                @foreach (array_slice($menus, 0, 4) as $menu)
                    <x-menu-card :menu="$menu" />
                @endforeach
            </div>
        </section>

        <section>
            <div class="mb-3 flex items-center justify-between gap-3">
                <h2 class="text-title font-bold text-ink">Flash Sale Hari Ini</h2>
                <span class="text-caption font-semibold text-leaf-dark">Berakhir {{ $flashSaleBerakhir }}</span>
            </div>
            <a href="{{ route('buyer.katalog') }}" class="flex items-center gap-3 rounded-[18px] bg-[linear-gradient(161deg,var(--color-forest)_0%,var(--color-leaf)_71.4%)] p-3.5">
                <x-photo label="Foto" class="size-16 shrink-0 rounded-[14px]" />
                <div class="min-w-0 flex-1 space-y-[3px]">
                    <p class="truncate text-body font-bold text-white">{{ $flashSale['title'] }}</p>
                    <p class="text-caption font-medium text-mint">
                        Rp {{ number_format($flashSale['harga'], 0, ',', '.') }}
                        <span class="ml-1 line-through opacity-80">Rp {{ number_format($flashSale['harga_normal'], 0, ',', '.') }}</span>
                    </p>
                    <x-progress-bar :value="$flashSale['terjual']" tone="mint" height="h-1.5" />
                    <p class="text-[11px] font-medium text-mint">{{ $flashSale['terjual'] }}% terjual</p>
                </div>
            </a>
        </section>
    </main>

    <x-bottom-nav role="buyer" active="buyer.beranda" />
@endsection
