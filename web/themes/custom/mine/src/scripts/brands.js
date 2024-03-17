(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.brands = {
    attach: function (context, settings) {

      $(once('slick', '.paragraph--type--brands')).each(function () {
        console.log('tester');
        const slider = $(this).find('.field--name-field-brands');
        slider.slick({
          autoplay: true,
          autoplaySpeed: 2000,
          infinite: true,
          speed: 300,
          arrows: false,
          slidesToShow: 5,
          adaptiveWidth: true,
          dots: false,
          centerMode: false,
          // prevArrow: '<div class="slick-prev"></div>',
          // nextArrow: '<div class="slick-next"></div>',
          lazyLoad: 'progressive'
        });
      });
    }
  };

})(jQuery, Drupal);
