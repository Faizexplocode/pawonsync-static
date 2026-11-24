@props(['title' => 'Belum ada data', 'tone' => 'empty'])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center gap-2 py-4 text-center']) }}>
    @if ($tone === 'error')
        <span class="grid size-[88px] place-items-center rounded-full bg-danger-soft text-[38px] font-bold text-danger">!</span>
    @else
        <span class="grid size-[88px] place-items-center rounded-full bg-linear-[127deg] from-mint-soft to-mint text-[34px] font-bold text-leaf-dark">—</span>
    @endif
    <p class="text-[18px] font-bold text-ink">{{ $title }}</p>
    <p class="text-[13px] text-muted">{{ $slot }}</p>
</div>
