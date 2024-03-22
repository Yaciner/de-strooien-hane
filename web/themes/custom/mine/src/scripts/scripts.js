import Headroom from "headroom.js";
import "./lottie-animations";
import "./reviews";
import "./faq";
import "./image-slider";
import "./text-image-slider";
import "./fancybox";
import "./brands";
import SplitType from "split-type";
import AOS from "aos";
import "aos/dist/aos.css"; // You can also use <link> for styles

(function ($, Drupal) {
  "use strict";
  let typeSplit;

  Drupal.behaviors.scripts = {
    attach: function (context, settings) {
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

      // $('.has-notification').append('.notification-bar');

      $(once("click", ".js-mm-toggle")).on("click", function (e) {
        $("body").toggleClass("mm-open");
        e.preventDefault();
      });

      const animationTargets = [
        ".field blockquote"
      ];

      function runSplit() {
        animationTargets.forEach((target) => {
          typeSplit = new SplitType(target, {
            types: "lines, words",
          });
          $(target).find(".word").append("<div class='line-mask'></div>");
        });
        createAnimation();
      }

      function createAnimation() {
        animationTargets.forEach((target) => {
          let allMasks = $(target)
            .find(".word")
            .map(function () {
              return $(this).find(".line-mask");
            })
            .get();

          let tl = gsap.timeline({
            scrollTrigger: {
              trigger: target,
              start: "top center",
              end: "bottom center",
              scrub: 1,
            },
          });

          tl.to(allMasks, {
            width: "0%",
            duration: 1,
            stagger: 0.5,
          });
        });
      }

      function runSplitObserver() {
        const resizeObserver = new ResizeObserver((entries) => {
          for (let entry of entries) {
            const myTimeout = setTimeout(runSplit, 500);
            ScrollTrigger.refresh();
          }
        });
        resizeObserver.observe(document.body);
      }

      if (!window.matchMedia("(pointer: coarse)").matches) {
        animationTargets.forEach((target) => {
          $(target).addClass("animate");
        });
        runSplit();
        runSplitObserver();
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
