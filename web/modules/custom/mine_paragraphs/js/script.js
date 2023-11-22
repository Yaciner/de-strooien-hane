(function ($, Drupal) {

    'use strict';
    var copied_id;

    Drupal.behaviors.mineParagraphs = {
        attach: function (context, settings) {
            $(once('paragraph-html-id', '.paragraph .html-id')).click(function (e) {
                copied_id = $(this).attr('data-id');

                if (!navigator.clipboard) {
                    fallbackCopyTextToClipboard();
                    return;
                } else {
                    navigator.clipboard.writeText('#' + copied_id).then(function () {
                        showConfirmationMessage();
                    }, function (err) {
                        // console.error('Async: Could not copy text: ', err);
                    });
                }

            });
        }
    };

    function fallbackCopyTextToClipboard() {
        var textArea = document.createElement("textarea");
        textArea.value = '#' + copied_id;

        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            showConfirmationMessage();
        } catch (err) {
            // console.error('Fallback: Oops, unable to copy', err);
        }

        document.body.removeChild(textArea);
    }

    function showConfirmationMessage() {
        $('body').append("<div class='copied-text--message'>" + Drupal.t("The ID '@id' has been copied to your clipboard", {'@id': copied_id}) + "</div>");

        setTimeout(function () {
            $('.copied-text--message').animate({
                opacity: 0
            }, 500, function () {
                $(this).remove();
            });
        }, 1000);

    }

})(jQuery, Drupal);
