(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.reviews = {
    attach: function (context, settings) {

      $(once('slick', '.mine_views_block__reviews_overview')).each(function () {
        const slider = $(this).find('.views-rows');

        console.log('test');
        slider.slick({
          dots: true,
          infinite: true,
          speed: 300,
          slidesToShow: 4,
          dots: false,
          centerMode: false,
          adaptiveHeight: true,
          prevArrow: '<div class="slick-prev"></div>',
          nextArrow: '<div class="slick-next"></div>',
          lazyLoad: 'progressive',
          responsive: [
            {
              breakpoint: 1200,
              settings: {
                slidesToShow: 3,
              }
            },
            {
              breakpoint: 940,
              settings: {
                slidesToShow: 2,
              }
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 1,
              }
            }
          ]
        });
      });
    }
  };

})(jQuery, Drupal);
