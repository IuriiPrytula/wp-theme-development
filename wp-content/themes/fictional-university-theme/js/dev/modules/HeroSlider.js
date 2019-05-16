(function ($) {
  class HeroSlider {
    constructor() {
      this.els = $(".hero-slider");
      this.initSlider();
    }

    initSlider() {
      this.els.slick({
        autoplay: true,
        arrows: false,
        dots: true
      });
    }
  }

  var heroSlider = new HeroSlider();
})(jQuery);