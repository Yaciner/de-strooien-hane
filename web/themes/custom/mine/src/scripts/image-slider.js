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
      let autoplay = settings.image_slider;
      console.log(autoplay);

      $(once('slick', '.paragraph--type--image-slider.paragraph--view-mode--default')).each(function () {
        const slider = $(this).find('.field--name-field-media');

        slider.slick({
          dots: true,
          infinite: true,
          autoplay: autoplay,
          autoplaySpeed: 3000,
          speed: 300,
          slidesToShow: 1,
          centerMode: true,
          variableWidth: true,
          dots: false,
          prevArrow: '<div class="slick-prev"></div>',
          nextArrow: '<div class="slick-next"></div>',
          lazyLoad: 'progressive'
        });

        function updateSlideClasses(slick, currentSlide) {
          slick.$slides.removeClass('prevSlide nextSlide');
          $(slick.$slides[currentSlide]).prev().addClass('prevSlide');
          $(slick.$slides[currentSlide]).next().addClass('nextSlide');
        }

        updateSlideClasses(slider.slick('getSlick'), 0);

        slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
          updateSlideClasses(slick, nextSlide);
        });
      });
    }
  };

})(jQuery, Drupal);
