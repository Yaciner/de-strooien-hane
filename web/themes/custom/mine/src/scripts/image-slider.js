import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';

(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.image_slider = {
    attach: function (context, settings) {
      let sliders = $('.paragraph--type--image-slider.paragraph--view-mode--default .field--name-field-media')

      $(once('swiper', sliders)).each(function (index, item) {
        $(item).addClass('swiper');
        $(item).find('.field__item').addClass('swiper-slide');
        $(item).children().wrapAll('<div class="swiper-wrapper"></div>');

        Swiper.use([Navigation]);
        let dom = $(item).get(0);
        new Swiper(dom, {
          spaceBetween: 16,
          slidesPerView: 3,
          loop: true,
          centeredSlides: true,
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          }
        });
      });
    }
  };

})(jQuery, Drupal);
