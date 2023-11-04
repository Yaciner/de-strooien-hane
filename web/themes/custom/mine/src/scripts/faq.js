import Accordion from 'accordion-js';


(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.faq = {
    attach: function (context, settings) {

      console.log('test - script');
      const accordions = Array.from(document.querySelectorAll('.field--name-field-faq-items'));
      new Accordion(accordions, {
        showMultiple: false,
        elementClass: 'field__item',
        triggerClass: 'ac-trigger',
        panelClass: 'ac-panel'
      });
    }
  };

})(jQuery, Drupal);
