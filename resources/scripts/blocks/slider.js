document.addEventListener("DOMContentLoaded", function () {
  let sliderDelay;

  if (document.body.classList.contains("wp-admin")) {
    sliderDelay = 3500;
  } else {
    sliderDelay = 0;
  }

  // Check if .hero-splide element exists in the DOM
  const heroSplideElement = document.querySelector(".splide");

  if (heroSplideElement && typeof Splide !== "undefined") {
    setTimeout(function () {
      // Check again if Splide is not null before creating an instance
      if (Splide !== null) {
        new Splide(heroSplideElement, {
          autoplay: true,
          type: "fade",
          rewind: true,
          gap: "2rem",
          arrows: false,
          lazyLoad: "nearby",
        }).mount();
      }
    }, sliderDelay);
  }
});
