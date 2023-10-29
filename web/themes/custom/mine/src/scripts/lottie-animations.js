import lottie from "lottie-web";

(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.lottie_animations = {
    attach: function (context, settings) {

      console.log(settings.theme_path);
      let cursor = document.querySelector('.lottie-cursor');

      lottie.loadAnimation({
        container: cursor,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: settings.theme_path + '/json/cursor.json'
      });
    }
  };

})(jQuery, Drupal);
