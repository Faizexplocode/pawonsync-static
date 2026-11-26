@extends('layouts.phone')

@section('title', 'Keuangan')

@php
    $ringkas = fn (float $nominal): string => $nominal >= 1000000
        ? 'Rp '.rtrim(rtrim(number_format($nominal / 1000000, 1, ',', '.'), '0'), ',').' jt'
        : 'Rp '.number_format($nominal, 0, ',', '.');

    $lebar = 300;
    $tinggi = 110;
    $puncak = max(1, collect($bulanan)->max('total'));
    $jarak = $lebar / max(1, count($bulanan) - 1);
    $titik = collect($bulanan)->values()->map(fn (array $bulan, int $i) => [
        round($i * $jarak, 1),
        round($tinggi - 8 - ($bulan['total'] / $puncak) * ($tinggi - 24), 1),
    ]);
    $garis = $titik->map(fn (array $t, int $i) => ($i === 0 ? 'M' : 'L').$t[0].' '.$t[1])->implode(' ');
    $area = $garis.' L'.$lebar.' '.$tinggi.' L0 '.$tinggi.' Z';
    $persenBersih = $masuk > 0 ? round($laba / $masuk * 100) : 0;
@endphp

@section('content')
    <div class="flex-1 bg-cream">
        <header class="px-5 pb-2 pt-5">
            <p class="text-[12px] text-muted">{{ $seller->nama }}</p>
            <h1 class="text-[20px] font-semibold text-ink">Keuangan</h1>
        </header>

        <main class="stagger space-y-6 px-5 pb-6 pt-3">
            <section class="relative overflow-hidden rounded-[28px] bg-[linear-gradient(150deg,var(--color-forest-night)_0%,var(--color-forest)_60%,var(--color-leaf)_100%)] px-5 pb-4 pt-5 text-white">
                <div class="pointer-events-none absolute -left-12 -top-16 size-48 rounded-full bg-white/5"></div>
                <div class="relative space-y-1">
                    <p class="text-[12px] text-mint/80">Pendapatan bersih</p>
                    <p class="tabular text-[30px] font-semibold leading-tight" data-count-to="{{ (int) $saldo }}" data-count-format="rupiah">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                    <p class="text-[12px] text-mint/80">dari {{ $jumlahSelesai }} pesanan selesai</p>
                </div>

                <svg viewBox="0 0 {{ $lebar }} {{ $tinggi }}" class="relative mt-4 h-[110px] w-full overflow-visible" preserveAspectRatio="none" aria-label="Grafik pendapatan 6 bulan">
                    <defs>
                        <linearGradient id="isi-grafik" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#8fe0b3" stop-opacity="0.45" />
                            <stop offset="100%" stop-color="#8fe0b3" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                    <path d="{{ $area }}" fill="url(#isi-grafik)" class="animate-rise" />
                    <path d="{{ $garis }}" fill="none" stroke="#bdf0d2" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke" pathLength="1" stroke-dasharray="1" stroke-dashoffset="1" class="garis-grafik" />
                    @foreach ($titik as $i => [$x, $y])
                        @if ($i === count($titik) - 1)
                            <circle cx="{{ $x }}" cy="{{ $y }}" r="4.5" fill="#ffffff" stroke="#16874f" stroke-width="2" vector-effect="non-scaling-stroke" />
                        @endif
                    @endforeach
                </svg>
                <div class="relative mt-1 flex justify-between text-[10px] text-mint/70">
                    @foreach ($bulanan as $bulan)
                        <span>{{ $bulan['label'] }}</span>
                    @endforeach
                </div>
            </section>

            <section class="grid grid-cols-2 gap-x-4 gap-y-5 px-1">
                <div class="space-y-1">
                    <p class="text-[12px] text-muted">Penjualan kotor</p>
                    <p class="tabular text-[17px] font-semibold text-ink">{{ $ringkas($masuk) }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[12px] text-muted">Rata-rata / pesanan</p>
                    <p class="tabular text-[17px] font-semibold text-ink">{{ $ringkas($rataRata) }}</p>
                </div>
                <div class="col-span-2 space-y-2">
                    <div class="flex h-2.5 overflow-hidden rounded-full bg-danger-soft">
                        <div class="h-full origin-left animate-grow-x rounded-full bg-leaf" style="width: {{ $persenBersih }}%; animation-delay: 300ms"></div>
                    </div>
                    <div class="flex items-center justify-between text-[12px]">
                        <span class="inline-flex items-center gap-1.5 text-muted"><span class="size-2 rounded-full bg-leaf"></span>Bersih <span class="tabular font-medium text-ink">{{ $ringkas($laba) }}</span></span>
                        <span class="inline-flex items-center gap-1.5 text-muted"><span class="size-2 rounded-full bg-danger/60"></span>Komisi 5% <span class="tabular font-medium text-ink">{{ $ringkas($komisi) }}</span></span>
                    </div>
                </div>
            </section>

            <section class="space-y-3">
                <h2 class="text-[15px] font-semibold text-ink">Riwayat transaksi</h2>
                @if (count($transactions) > 0)
                    <div class="surface divide-y divide-line/70 px-4">
                        @foreach ($transactions as $transaction)
                            @php($masukDana = $transaction['type'] === 'in')
                            <div class="flex items-center gap-3 py-3.5">
                                <span @class([
                                    'grid size-10 shrink-0 place-items-center rounded-full',
                                    'bg-mint-faint text-leaf' => $masukDana,
                                    'bg-danger-faint text-danger' => ! $masukDana,
                                ])>
                                    <x-icon :name="$masukDana ? 'arrow-right' : 'arrow-up-right'" :class="$masukDana ? 'size-4 rotate-90' : 'size-4'" />
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-[13px] font-medium text-ink">{{ $transaction['title'] }}</p>
                                    <p class="text-[11px] text-muted">{{ $transaction['date'] }}</p>
                                </div>
                                <span @class(['tabular shrink-0 text-[13px] font-semibold', 'text-leaf' => $masukDana, 'text-danger' => ! $masukDana])>
                                    {{ $masukDana ? '+' : '−' }}{{ $ringkas(abs($transaction['amount'])) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="rounded-[24px] bg-white/60 px-4 py-6 text-center text-[13px] text-muted">Belum ada transaksi dari pesanan selesai.</p>
                @endif
                <p class="px-1 text-[11px] leading-relaxed text-muted">Dihitung otomatis dari pesanan berstatus selesai. Pencairan mengikuti kebijakan platform.</p>
            </section>
        </main>
    </div>

    <x-bottom-nav role="seller" active="seller.keuangan" />

    <style>
        .garis-grafik {
            animation: gambar-garis 1.4s 0.3s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        @keyframes gambar-garis {
            to {
                stroke-dashoffset: 0;
            }
        }
    </style>
@endsection
