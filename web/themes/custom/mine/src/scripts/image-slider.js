import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { Observer } from "gsap/Observer";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";
import { TextPlugin } from "gsap/TextPlugin";



(function ($, Drupal) {
  
  'use strict';
  
  Drupal.behaviors.image_slider = {
    attach: function (context, settings) {
      gsap.registerPlugin(ScrollTrigger,Observer,ScrollToPlugin,TextPlugin);
      let defaultSliders = $('.paragraph--type--image-slider.paragraph--view-mode--default .field--name-field-media');
      let scrollSliders = $('.paragraph--type--image-slider.paragraph--view-mode--scroll_animation .field--name-field-media');

      $(once('swiper', defaultSliders)).each(function (index, item) {
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

      // write logic for scorll trigger


    }
  };

})(jQuery, Drupal);
