(function ($, Drupal) {

  Drupal.behaviors.animations__aos = {
    attach: function (context, settings) {
      if (typeof AOS !== "undefined") {
        AOS.init({once: true});
      }
    }
  }

})(jQuery, Drupal);
