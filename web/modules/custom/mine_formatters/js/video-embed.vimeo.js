(function ($, Drupal) {

  Drupal.behaviors.video_embed__vimeo = {
    attach: function (context, settings) {
      var base_url = settings.path.baseUrl;
      var prefix = settings.path.pathPrefix;

      $('video.not-loaded').once().each(function () {
        var video_placeholder = $(this);
        var video_id = video_placeholder.attr('data-vid');
        var video_url = base_url + prefix + 'mine-formatters/vimeo/hls?video=' + video_id;

        $.getJSON(video_url, function (data) {
          if (data.status == 200) {
            video_placeholder.html("<source src='" + data.url + "'>");
            var video = video_placeholder[0];

            if (video.canPlayType('application/vnd.apple.mpegurl')) {
              return;
            } else if (Hls.isSupported()) {
              var hls = new Hls();
              // hls.loadSource(data.url);
              hls.attachMedia(video);

              hls.on(Hls.Events.MEDIA_ATTACHED, () => {
                // // Solution 1
                // // Lags sometime
                // const hlsFragments = [];
                // hls.loadSource(data.url);
                // hls.on(Hls.Events.FRAG_LOADED, function (event, data) {
                //   if (data.frag.type == 'main') {
                //     hlsFragments.push(data.frag);
                //   }
                // });
                //
                // hls.on(Hls.Events.LEVEL_SWITCHED, (event, data) => {
                //   const fragmentLevels = hlsFragments.map(fragment => fragment.level);
                //   const maxLevel = fragmentLevels.reduce((max, cur) => Math.max(max, cur), 0);
                //   hlsFragments.map((fragment, index) => {
                //     if (fragment.level < maxLevel) {
                //       hls.trigger(Hls.Events.BUFFER_FLUSHING, {startOffset: fragment.startPTS, endOffset: fragment.endPTS});
                //       hlsFragments.splice(index, 1);
                //     }
                //   });
                // });
                
                // Solution 2
                const hlsFragments = [];
                var dataSegements = [];
                hls.loadSource(data.url);
                hls.on(Hls.Events.FRAG_LOADED, function (event, data) {
                    if (data.frag.type == 'main') {
                      hlsFragments[data.frag.sn] = data.frag;
                      if (dataSegements[data.frag.sn] == undefined) {
                        dataSegements[data.frag.sn] = true;
                      }
                      var start = data.frag.start;
                      var end = start + data.frag.duration;
                      var wait = (start - video.currentTime) + data.frag.duration + 0.5;
                      sleep(wait * 1000).then(() => {
                        var fragmentLevels = hlsFragments.map(fragment => fragment.level);
                        var maxLevel = fragmentLevels.reduce((max, cur) => Math.max(max, cur), 0);
                        if (data.frag.level < maxLevel && dataSegements[data.frag.sn]) {
                          hls.trigger(Hls.Events.BUFFER_FLUSHING, {
                            startOffset: start,
                            endOffset: end
                          });
                          dataSegements[data.frag.sn] = false;
                        }
                      });
                    }
                });
              });
            }

            video_placeholder.removeClass('not-loaded').addClass('loaded');

            var width = data.width;
            var height = data.height;
            video_placeholder.before($('<style>.video-' + video_id + ' { aspect-ratio: ' + width + '/' + height + '; } </style>'))
          }
        });
      });

    }
  };

  // sleep time expects milliseconds
  function sleep (time) {
    return new Promise((resolve) => setTimeout(resolve, time));
  }

})(jQuery, Drupal);
