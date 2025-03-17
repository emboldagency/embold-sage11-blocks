@if (! is_admin())
    <div class="{{ $block->classes }} {{ $padding }} w-screen relative bg-center bg-cover" style="left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; background-image: url('{{ $background_image }}')">
        <div class="container">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:justify-between gap-x-6 p-6" style="background: transparent linear-gradient(269deg, #FFFFFF33 0%, #FFFFFFCC 43%, #FFFFFFB3 100%) 0% 0% no-repeat padding-box;">
                    <div class="order-2 relative lg:w-3/5 lg:pl-6">
                        <div class="absolute top-0 left-0 leading-none font-serif text-blue lg:pl-6" style="font-size: 90px;">"</div>
                        <div class="lg:pl-11 lg:pt-3 pt-12 pb-4 font-serif text-lg text-blue">{!! $quote !!}</div>
                        <div class="pb-4 font-serif text-2xl text-blue font-bold italic">- {{ $author }}</div>
                    </div>
                    <div class="lg:order-3 order-1 lg:w-2/5 mb-6 lg:mb-0">
                        <img src="{{ $image }}" alt="testimonial decorative image" class="border-4 border-white lg:ml-auto">
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="{{ $block->classes }} {{ $padding }} bg-center bg-cover" style="background-image: url('{{ $background_image }}')">
        <div class="container">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:justify-between gap-x-6 p-6" style="background: transparent linear-gradient(269deg, #FFFFFF33 0%, #FFFFFFCC 43%, #FFFFFFB3 100%) 0% 0% no-repeat padding-box;">
                    <div class="lg:w-3/5 lg:pl-6">
                        <div class="pb-4 font-serif text-xl text-blue">{!! $quote !!}</div>
                        <div class="pb-4 font-serif text-2xl text-blue font-bold italic">- {{ $author }}</div>
                    </div>
                    <div class="lg:w-2/5">
                        <img src="{{ $image }}" alt="testimonial decorative image" class="border-4 border-white lg:ml-auto">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
