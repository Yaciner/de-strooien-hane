(function ($, Drupal) {
  
  'use strict';
  
  Drupal.behaviors.text_image_slider = {
    attach: function (context, settings) {
      $(once('slick', '.paragraph--type--text-media-slider.paragraph--view-mode--default')).each(function () {
        const slider = $(this).find('.field--name-field-entries');

        slider.slick({
          dots: true,
          infinite: true,
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
