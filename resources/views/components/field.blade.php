@props(['label' => null, 'value' => '', 'placeholder' => '', 'type' => 'text', 'icon' => null, 'hint' => null])

<label class="block">
    @if ($label)
        <span class="mb-1.5 block text-[12px] font-semibold text-forest-deep">{{ $label }}</span>
    @endif
    <span class="flex items-center gap-2 rounded-2xl border border-line bg-white px-4 py-3 focus-within:border-leaf focus-within:ring-2 focus-within:ring-mint">
        @if ($icon)
            <x-icon :name="$icon" class="size-4 shrink-0 text-muted" />
        @endif
        <input type="{{ $type }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="w-full bg-transparent text-[13px] font-medium text-ink outline-none placeholder:text-muted/70" {{ $attributes }} />
    </span>
    @if ($hint)
        <span class="mt-1 block text-[10px] text-muted">{{ $hint }}</span>
    @endif
</label>
