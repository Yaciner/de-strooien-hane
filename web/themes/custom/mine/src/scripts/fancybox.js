(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.fancybox = {
    attach: function (context, settings) {
      $().fancybox({
        selector: '.lightbox:not(.slick-cloned)',
      });
    }
  };

})(jQuery, Drupal);