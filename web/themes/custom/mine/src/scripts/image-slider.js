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
        let uniqueNavClass = 'swiper-navigation-i-s-' + index;
        $(item).addClass('swiper');
        $(item).find('.field__item').addClass('swiper-slide');
        $(item).children().wrapAll('<div class="swiper-wrapper"></div>');

        $(item).parent().find('.swiper-button-next').addClass(uniqueNavClass + '-next');
        $(item).parent().find('.swiper-button-prev').addClass(uniqueNavClass + '-prev');
        
        if($(item).find('.swiper-wrapper').children().length <= 3) {
          let clones = $(item).find('.swiper-wrapper').children().clone();
          $(item).find('.swiper-wrapper').append(clones);
        }
        

        Swiper.use([Navigation]);
        let dom = $(item).get(0);
        new Swiper(dom, {
          slidesPerView: 2.2,
          spaceBetween: 30,
          loop: true,
          centeredSlides: true,
          navigation: {
            nextEl: '.' + uniqueNavClass + '-next',
            prevEl: '.' + uniqueNavClass + '-prev',
          }
        });
      });
    }
  };

})(jQuery, Drupal);
