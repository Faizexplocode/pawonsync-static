@props(['name', 'value', 'title', 'note' => null, 'checked' => false, 'trailing' => null])

<label class="flex cursor-pointer items-center gap-3 rounded-[14px] border border-line bg-white px-3.5 py-3 has-[:checked]:border-[1.5px] has-[:checked]:border-leaf has-[:checked]:bg-mint-faint">
    <input type="radio" name="{{ $name }}" value="{{ $value }}" @checked($checked) {{ $attributes }} class="peer sr-only" />
    <span class="grid size-[18px] shrink-0 place-items-center rounded-full border-2 border-line bg-white peer-checked:border-leaf peer-checked:before:size-2 peer-checked:before:rounded-full peer-checked:before:bg-leaf"></span>
    <span class="min-w-0 flex-1 space-y-0.5">
        <span class="block text-body font-semibold text-ink">{{ $title }}</span>
        @if ($note)
            <span class="block text-caption text-muted">{{ $note }}</span>
        @endif
    </span>
    @if ($trailing)
        <span class="shrink-0 text-[13px] font-bold text-forest">{{ $trailing }}</span>
    @endif
</label>
