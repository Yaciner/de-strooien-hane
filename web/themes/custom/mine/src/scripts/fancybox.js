(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.fancybox = {
    attach: function (context, settings) {
      console.log('test');
      
      $().fancybox({
        selector: '.lightbox:not(.slick-cloned)',
      });
    }
  };

})(jQuery, Drupal);