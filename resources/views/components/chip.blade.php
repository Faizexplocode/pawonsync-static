@props(['active' => false])

<a {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center justify-center rounded-full px-4 py-2 text-[13px] transition '.($active ? 'bg-forest font-medium text-white' : 'bg-white text-muted shadow-soft')]) }}>{{ $slot }}</a>
