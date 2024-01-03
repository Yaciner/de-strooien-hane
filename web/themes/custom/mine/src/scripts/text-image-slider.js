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
        let uniqueNavClass = 'navigation-t-m-s-' + index; // Unique class for each slider

        $(item).addClass('swiper');
        $(item).find('.paragraph--type--text-media-item').addClass('swiper-slide');
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
