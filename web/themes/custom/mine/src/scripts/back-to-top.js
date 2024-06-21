(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.back_to_top = {
    attach: function (context, settings) {
      const backToTop = () => {
        if ($(window).scrollTop() > 1000) {
          $('.back-to-top').css('opacity', '1');
          $('.back-to-top').fadeIn();

        } else {
          $('.back-to-top').fadeOut();
        }
      }

      backToTop();

      $(once('once', '.back-to-top')).on('click', function () {
        $("html, body").bind("scroll mousedown DOMMouseScroll mousewheel keyup", function () {
          $('html, body').stop();
        });
        $('html,body').animate({scrollTop: 0}, 350, 'linear', function () {
          $("html, body").unbind("scroll mousedown DOMMouseScroll mousewheel keyup");
        });
      });

      $(window).scroll(function () {
        backToTop();
      });
    }
  };

})(jQuery, Drupal);