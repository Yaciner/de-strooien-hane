import simpleParallax from "simple-parallax-js";
import SplitType from "split-type";

(function ($, Drupal) {
  "use strict";

  Drupal.behaviors.custom_animations = {
    attach: function (context, settings) {
      let typeSplitHero;

      // Simple parallax next collection
      var nextCollection = document.querySelector(
        ".node--view-mode-next-collection .field--name-field-next-collection"
      );
      new simpleParallax(nextCollection, {
        delay: 0.6,
        scale: 1.1,
        orientation: "right",
        transition: "cubic-bezier(0,0,0,1)",
      });

      // Simple parallax hero
      var hero = document.querySelector(
        ".paragraph--type--hero .field--name-field-media"
      );
      new simpleParallax(hero, {
        delay: 0.6,
        transition: "cubic-bezier(0,0,0,1)",
      });

      const heroTargets = [
        ".node-type-homepage .paragraph--type--hero h1",
        ".node-type-homepage .paragraph--type--hero .field--name-field-text *:not(a)",
      ];

      function splitHero() {
        heroTargets.forEach((target) => {
          typeSplitHero = new SplitType(target, {
            types: "lines, words",
          });
          const words = document.querySelectorAll(target + " .word");
          words.forEach((word) => {
            const wrapper = document.createElement("div");
            wrapper.classList.add("word-wrapper");
            word.parentNode.insertBefore(wrapper, word);
            wrapper.appendChild(word);
          });
        });
      }

      splitHero();
      $(".paragraph--type--hero a.btn--video").addClass("active");

      const tl = gsap.timeline();
      tl.from(".line .word", 1, {
        y: 300,
        ease: "ease-in",
        delay: 0.5,
        stagger: {
          amount: 0.6,
        },
      });

      var t_m = document.querySelector(
        ".paragraph--type--text-media .pg-text-media__media .field--name-field-media"
      );
      new simpleParallax(t_m, {
        transition: "ease-in",
      });

      // skewing img on scroll
      // const mediaItems = document.querySelectorAll('.paragraph:not(.paragraph--type--hero) .field--type-entity-reference img, .paragraph:not(.paragraph--type--hero) .field--type-entity-reference video');

      // mediaItems.forEach(media => {
      //   let currentPos = window.pageYOffset;

      //   const update = () => {
      //     const newPos = window.pageYOffset;
      //     const diff = newPos - currentPos;
      //     const speed = diff * 0.02;

      //     media.style.transform = `skewY(${speed}deg)`;

      //     currentPos = newPos;

      //     requestAnimationFrame(update);
      //   }

      //   update();
      // });

      const quoteTargets = [".field blockquote"];
      let typeSplitQuote;

      function runSplit() {
        // check if quoteTargets exist
        if (!document.querySelector(quoteTargets)) {
          return;
        }
        quoteTargets.forEach((target) => {
          typeSplitQuote = new SplitType(target, {
            types: "lines, words",
          });
          $(target).find(".word").append("<div class='line-mask'></div>");
        });
        createAnimation();
      }

      function createAnimation() {
        // check if quoteTargets exist
        if (!document.querySelector(quoteTargets)) {
          return;
        }
        quoteTargets.forEach((target) => {
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
        quoteTargets.forEach((target) => {
          $(target).addClass("animate");
        });
        runSplit();
        runSplitObserver();
      }
    },
  };
})(jQuery, Drupal);
