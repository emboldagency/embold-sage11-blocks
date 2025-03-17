<div class="{{ $block->classes }} {{ $padding }}">
    @if ($slides)
        <section class="splide" aria-label="slider with decorative images and links to pages">
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach ($slides as $slide)
                        <li class="splide__slide">
                            <div class="flex">
                                <div>
                                    <div>{!! $slide['title'] !!}</div>
                                    <div>{!! $slide['body'] !!}</div>
                                    <div>
                                        <a href="{{ $slide['link'] }}">Read More</a>
                                    </div>
                                </div>
                                <div>
                                    <img src="{{ $slide['image'] }}" role="presentation">
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif
</section>
