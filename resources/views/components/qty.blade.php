@props(['value' => 1])

<div class="inline-flex items-center gap-3 rounded-full bg-mist px-2 py-1.5 ring-1 ring-line">
    <span class="grid size-7 place-items-center rounded-full bg-white text-forest-deep ring-1 ring-line">
        <x-icon name="minus" class="size-3.5" />
    </span>
    <span class="min-w-8 text-center text-[13px] font-bold">{{ $value }}</span>
    <span class="grid size-7 place-items-center rounded-full bg-forest text-white">
        <x-icon name="plus" class="size-3.5" />
    </span>
</div>
