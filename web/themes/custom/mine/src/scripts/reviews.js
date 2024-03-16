import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';

(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.reviews = {
    attach: function (context, settings) {

      $(once('slick', '.mine_views_block__reviews_overview')).each(function () {
        const slider = $(this).find('.views-rows');

        slider.slick({
          dots: true,
          infinite: true,
          speed: 300,
          slidesToShow: 4,
          adaptiveWidth: true,
          dots: false,
          centerMode: false,
          prevArrow: '<div class="slick-prev"></div>',
          nextArrow: '<div class="slick-next"></div>',
          lazyLoad: 'progressive'
        });
      });
    }
  };

})(jQuery, Drupal);
