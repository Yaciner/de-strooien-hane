import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';

(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.reviews = {
    attach: function (context, settings) {
      $('.field--name-field-block-to-embed .reviews .views-row').addClass('swiper-slide');
      Swiper.use([Navigation]);
      new Swiper('.swiper', {
        slidesPerView: 4,
        spaceBetween: 20,
        loop: true,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        }
      });
    }
  };

})(jQuery, Drupal);
