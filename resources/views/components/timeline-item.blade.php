@props(['title', 'note' => null, 'time' => null, 'done' => false, 'last' => false])

<div class="flex items-start gap-3">
    <div class="flex flex-col items-center">
        <span @class([
            'grid size-[22px] place-items-center rounded-full border-2 text-[11px] font-bold',
            'border-leaf-dark bg-leaf-dark text-white' => $done,
            'border-line bg-white text-transparent' => ! $done,
        ])>✓</span>
        @unless ($last)
            <span @class(['h-[38px] w-0.5', 'bg-leaf-dark' => $done, 'bg-line' => ! $done])></span>
        @endunless
    </div>
    <div class="min-w-0 flex-1 space-y-0.5 pb-3">
        <div class="flex items-start justify-between gap-2">
            <p @class(['flex-1 text-body', 'font-semibold text-ink' => $done, 'font-medium text-muted' => ! $done])>{{ $title }}</p>
            @if ($time)
                <p class="shrink-0 text-caption font-medium text-muted">{{ $time }}</p>
            @endif
        </div>
        @if ($note)
            <p class="text-caption text-muted">{{ $note }}</p>
        @endif
    </div>
</div>
