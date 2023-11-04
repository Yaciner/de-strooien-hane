import Headroom from "headroom.js";
// import './fancybox';
import './lottie-animations';
import './faq';

(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.scripts = {
    attach: function (context, settings) {
      // Geysir
      $(once('click', 'body')).on('click', '.geysir-dialog .horizontal-tab-button a',function() {
        $('#geysir-modal').dialog({
          height: 'auto',
        });
      });

      // Headroom
      let header = document.querySelector('.region-header');
      let headroom  = new Headroom(header);
      headroom.init();
    }
  };
})(jQuery, Drupal);

jQuery.event.special.touchstart = {
  setup: function( _, ns, handle ) {
      this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
  }
};
jQuery.event.special.touchmove = {
  setup: function( _, ns, handle ) {
      this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
  }
};
jQuery.event.special.wheel = {
  setup: function( _, ns, handle ){
      this.addEventListener("wheel", handle, { passive: true });
  }
};
jQuery.event.special.mousewheel = {
  setup: function( _, ns, handle ){
      this.addEventListener("mousewheel", handle, { passive: true });
  }
};
