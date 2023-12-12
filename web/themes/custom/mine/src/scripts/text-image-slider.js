import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';


(function ($, Drupal) {
  
  'use strict';
  
  Drupal.behaviors.text_image_slider = {
    attach: function (context, settings) {
      let defaultSliders = $('.paragraph--type--text-media-slider.paragraph--view-mode--default .field--name-field-entries');

      $(once('swiper', defaultSliders)).each(function (index, item) {
        $(item).addClass('swiper');
        $(item).find('.paragraph--type--text-media-item').addClass('swiper-slide');
        $(item).children().wrapAll('<div class="swiper-wrapper"></div>');

        Swiper.use([Navigation]);
        let dom = $(item).get(0);
        new Swiper(dom, {
          slidesPerView: 2.2,
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
