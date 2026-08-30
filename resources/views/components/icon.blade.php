@props(['name' => 'monitor', 'class' => 'w-5 h-5'])
{{-- mapping sederhana; bisa diganti heroicons/lucide package --}}
@if ($name === 'server')
    <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2">
        <rect x="3" y="4" width="18" height="6" rx="2" />
        <rect x="3" y="14" width="18" height="6" rx="2" />
    </svg>
@elseif($name === 'smartphone')
    <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2">
        <rect x="7" y="2" width="10" height="20" rx="2" />
        <circle cx="12" cy="18" r="1" />
    </svg>
@elseif($name === 'plus')
    <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2">
        <path d="M12 2v20M2 12h20" />
    </svg>
@elseif($name === 'arrow-big-up')
    <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2">
        <path d="M12 20V6" />
        <path d="M5 13l7-7 7 7" />
    </svg>
@else
    {{-- default monitor --}}
    <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2">
        <rect x="3" y="3" width="18" height="14" rx="2" />
        <path d="M8 21h8" />
        <path d="M12 17v4" />
    </svg>
@endif
