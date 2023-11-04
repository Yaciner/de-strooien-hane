import lottie from "lottie-web";

(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.lottie_animations = {
    attach: function (context, settings) {

      let cursor = $(once('cursor' , '.lottie-cursor'));

      lottie.loadAnimation({
        container: $(cursor)[0],
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: settings.theme_path + '/json/cursor.json'
      });
    }
  };

})(jQuery, Drupal);
