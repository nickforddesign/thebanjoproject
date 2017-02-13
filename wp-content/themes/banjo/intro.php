<?php
/**
 * Template Name: Intro
 * Description: Time time for some maption
 */

get_header();
?>

<main>
  <a class="btn btn-back skip" href="<?php bloginfo('url'); ?>/">Back</a>
  <iframe class="video-intro" src="https://player.vimeo.com/video/108920711?autoplay=1&color=ea9e39&title=0&byline=0&portrait=0&api=1" width="580" height="326" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
  <script>
    jQuery(function($) {
      var player = $('.video-intro');
      var playerOrigin = '*';
      if (window.addEventListener) {
        window.addEventListener('message', onMessageReceived, false);
      } else {
        window.attachEvent('onmessage', onMessageReceived, false);
      }
      function onReady() {

        post('addEventListener', 'pause');
        post('addEventListener', 'finish');
        post('addEventListener', 'playProgress');
      }
      function onPause() {
        console.log('paused')
      }
      function onPlay() {
        if ($('.music-player').hasClass('playing')) playa.action('pause')
        console.log('playing')
      }
      function onFinish() {
        History.pushState({}, 'People', root + '/people')
      }
      function post(action, value) {
        var data = {
          method: action
        };

        if (value) {
            data.value = value;
        }

        var message = JSON.stringify(data);
        player[0].contentWindow.postMessage(data, playerOrigin);
      }
      function onMessageReceived(event) {
          // Handle messages from the vimeo player only
        if (!(/^https?:\/\/player.vimeo.com/).test(event.origin)) {
          return false;
        }

        if (playerOrigin === '*') {
          playerOrigin = event.origin;
        }

        var data = JSON.parse(event.data);

        switch (data.event) {
          case 'ready':
              onReady();
              break;

          case 'pause':
              onPause();
              break;

          case 'playProgress':
              onPlay();
              break;

          case 'finish':
              onFinish();
              break;
        }
      }
    });
  </script>
</main>