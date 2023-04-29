(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.scripts = {
    attach: function (context, settings) {
      console.log('test');
      // Geysir
      $(once('click', 'body')).on('click', '.geysir-dialog .horizontal-tab-button a',function() {
        $('#geysir-modal').dialog({
          height: 'auto',
        });
      });
    }
  };
})(jQuery, Drupal);
