import Headroom from "headroom.js";
import "./lottie-animations";
import "./reviews";
import "./faq";
import "./image-slider";
import "./text-image-slider";
import "./fancybox";
import "./brands";
import "aos/dist/aos.css";
import "./masonry";
import "./custom_animations";
import AOS from "aos";
import "./back-to-top";

(function ($, Drupal) {
  "use strict";
  let typeSplit;

  Drupal.behaviors.scripts = {
    attach: function (context, settings) {
      const loggedIn = $("body").hasClass("logged-in");

      // AOS
      AOS.init({
        once: true,
      });

      // Geysir
      $(once("click", "body")).on(
        "click",
        ".geysir-dialog .horizontal-tab-button a",
        function () {
          $("#geysir-modal").dialog({
            height: "auto",
          });
        }
      );

      // Headroom
      let header = document.querySelector(".region-header");
      let headroom = new Headroom(header);
      headroom.init();

      $(once("click", ".js-mm-toggle")).on("click", function (e) {
        $("body").toggleClass("mm-open");
        e.preventDefault();
      });

      if (window.matchMedia("(max-width: 767px)").matches) {
        $(once("slick", ".field--name-field-quickmenu-links")).each(
          function () {
            $(this).slick({
              autoplay: true,
              autoplaySpeed: 2000,
              infinite: true,
              fade: true,
              speed: 300,
              arrows: true,
              slidesToShow: 1,
              dots: false,
              centerMode: false,
              prevArrow: '<div class="slick-prev"></div>',
              nextArrow: '<div class="slick-next"></div>',
              lazyLoad: "progressive",
            });
          }
        );
      }
    },
  };
})(jQuery, Drupal);

jQuery.event.special.touchstart = {
  setup: function (_, ns, handle) {
    this.addEventListener("touchstart", handle, {
      passive: !ns.includes("noPreventDefault"),
    });
  },
};
jQuery.event.special.touchmove = {
  setup: function (_, ns, handle) {
    this.addEventListener("touchmove", handle, {
      passive: !ns.includes("noPreventDefault"),
    });
  },
};
jQuery.event.special.wheel = {
  setup: function (_, ns, handle) {
    this.addEventListener("wheel", handle, { passive: true });
  },
};
jQuery.event.special.mousewheel = {
  setup: function (_, ns, handle) {
    this.addEventListener("mousewheel", handle, { passive: true });
  },
};
