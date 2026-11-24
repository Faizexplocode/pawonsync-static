@props(['active' => false])

<a {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center justify-center rounded-full px-3.5 py-2.5 text-caption transition '.($active ? 'bg-forest font-semibold text-white' : 'border border-line bg-white font-medium text-ink')]) }}>{{ $slot }}</a>
