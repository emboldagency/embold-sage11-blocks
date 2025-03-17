document.addEventListener('DOMContentLoaded', function () {
    let sliderDelay;

    if (document.body.classList.contains('wp-admin')) {
        sliderDelay = 5000;
    } else {
        sliderDelay = 0;
    }

    // Check if .testimonial-splide element exists in the DOM
    const testimonialSplideElement = document.querySelector('.testimonial-splide');

    if (testimonialSplideElement && typeof Splide !== 'undefined') {
        setTimeout(function () {
            // Check again if Splide is not null before creating an instance
            if (Splide !== null) {
                new Splide(testimonialSplideElement, {
                    autoplay: true,
                    type: 'fade',
                    rewind: true,
                    gap: '2rem',
                    arrows: false,
                    lazyLoad: 'nearby',
                }).mount();
            }
        }, sliderDelay);
    }
});
