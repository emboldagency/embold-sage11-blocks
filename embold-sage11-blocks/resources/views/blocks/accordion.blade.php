@if ($inline_style)
    @php
        // This is no longer needed, but I'm leaving it in as an example
        // of how you can add code (maybe a <style> section) to only the
        // first instance of a block on the page.
    @endphp
@endif

<div class="group {{ $block->classes }} {{ $padding }}">
    @if ($title)
        <details class="border-gray-400 border-t-2 group-last-of-type:border-b-2">
            <summary class="pt-3 lg:pt-8 lg:pb-4">
                <h3>{{ $title }}</h3>
            </summary>
            <div><InnerBlocks /></div>
      </details>
    @else
        <p>{{ $block->preview ? 'This item has no title' : 'Untitled item' }}</p>
    @endif
</div>
