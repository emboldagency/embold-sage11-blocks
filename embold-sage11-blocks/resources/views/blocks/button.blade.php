<div class="{{ $block->classes }} {{ $padding }}">
    <a @if (! is_admin()) href="{{ $link }}" @endif class="{{ $full_width ? 'block w-full' : '' }} btn btn-{{ $block->style }}">
        {{ $text }}
    </a>
</div>
