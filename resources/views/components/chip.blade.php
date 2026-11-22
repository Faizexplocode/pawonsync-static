@props(['active' => false])

<a {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center justify-center rounded-full px-4 py-2 text-[12px] font-semibold transition ' . ($active ? 'bg-forest text-white shadow-sm' : 'bg-white text-muted ring-1 ring-line')]) }}>{{ $slot }}</a>
