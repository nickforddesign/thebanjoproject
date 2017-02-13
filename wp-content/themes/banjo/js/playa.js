/* playa.js
   By Nick Ford
   Dependencies: jQuery, Soundcloud API
   nickforddesign@gmail.com
*/

var playa = {};

!function($){

  if (typeof jQuery == undefined) { if (console in window) { console.warn('playa.js requires jQuery'); return false; } }

  $.extend(playa, {

    init: function() {
      this.widget = {};

      var plugin = $('.music-player'),
          iframe = $('.music-player--iframe-container iframe')[0],
          songTitle = $('.music-player--song-title'),
          controls = document.querySelectorAll('.music-player--controls ul li a');

      playa.widget = SC.Widget(iframe);
      playa.currentSong = '';

      // Event bindings
      playa.widget.bind(SC.Widget.Events.READY, function() {

        playa.widget.bind(SC.Widget.Events.PLAY, function() {
          if (!plugin.hasClass('active')) plugin.trigger('show')
          plugin.addClass('playing');
          // console.log('playing');
        });

        playa.widget.getCurrentSound(function(sound){
          var title = sound.title;

          if (playa.currentSong !== title) {
            playa.currentSong = title;
          }
          songTitle.text(title);
        });

        playa.widget.bind(SC.Widget.Events.PAUSE, function() {
          $('.music-player').removeClass('playing');
        });

        playa.widget.bind(SC.Widget.Events.PLAY_PROGRESS, function(data) {
          var time = playa.msToTime(data.currentPosition);
          $('.music-player--time').text(time)
        });

        var hide;

        plugin.unbind().on('show', function() {
          plugin.addClass('active');
          // console.log('show event caught');
          hide = setTimeout(function() {
            plugin.removeClass('active');
            // console.log('hide');
          }, 3600);
        });

        plugin.on({
          mouseenter: function() {
            if (!plugin.hasClass('active')) plugin.trigger('show')
            // console.log('mouseenter');
            clearTimeout(hide);
          },
          mouseleave: function() {
            // console.log('mouseexit');
            hide = setTimeout(function() {
              plugin.removeClass('active');
              // console.log('hide');
            }, 3600);
          }
        })

      });

      // Player button events
      if (!this.loaded) {
        $('.music-player--controls li svg').on('click', function() {
          // console.log('click: ' + $(this).attr('data-action'))
          playa.widget[$(this).attr('data-action')]();
        });
        this.initEventHandlers();
      }

      this.loaded = true;
    },
    action: function(action) {
      if (action !== 'play' && action !== 'pause' && action !== 'toggle') {
        console.log('no')
        if (console in window) console.error('Invalid action. Use \'play\', \'pause\', or \'toggle\'.');
      } else {
        console.log('ok')
        playa.widget[action]();
      }
      /*
      switch (action) {
        case 'play' :
          playa.widget['play'];
          break;
        case 'pause' :
          playa.widget['pause']();
          break;
      }*/

    },
    msToTime: function(s) {
      var ms = s % 1000;
      s = (s - ms) / 1000;
      var secs = s % 60;
      s = (s - secs) / 60;
      var mins = s % 60;

      return (mins + ':' + ('0' + secs).slice(-2));
    },
    createElement: function(element, classes) {
      if (!element) element = 'div';
      var el = document.createElement(element);
      el.className = classes;
      return el;
    },
    loadMusic: function(path) { // expects a string like 'tracks/22010243'
      $('.music-player').removeClass('playing');
      $('.music-player--time').text('0:00');
      var newIframe = playa.createElement('iframe');
      newIframe.src = 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/' + path + '&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=false&amp;auto_play=true'
      $('.music-player--iframe-container').html('').append($(newIframe));
      playa.init();
    },
    initEventHandlers: function() {
      $(document).on('click', '.music-player--link' , function(e) {
        e.preventDefault();
        playa.loadMusic($(this).attr('data-music'));
        $('.music-player').trigger('show')
      });

      $(document).keydown(function(e) {
        if (e.keyCode == 65) { // a
          $('.music-player').toggleClass('active');
        } else if (e.keyCode == 32) {// space
          $('.music-player--toggle').click();
        }
      });
    },
    initVimeoListener: function() {

      var players = $('iframe[src*="vimeo.com"]');
      var playerOrigin = '*';

      console.log(players)
      console.log(players.length)

      if (window.addEventListener) {
        console.log('addEventListener');
        window.addEventListener('message', onMessageReceived, false);
      } else {
        window.attachEvent('onmessage', onMessageReceived, false);
      }

      function onReady() {
        console.log('onReady()')
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
        console.log('finished')
      }

      function post(action, value) {
        var data = {
          method: action
        };

        if (value) {
            data.value = value;
        }

        var message = JSON.stringify(data);
        players[0].contentWindow.postMessage(message, playerOrigin);
      }


      function onMessageReceived(event) {
        //console.log('onMessageReceived')
        // console.log(event)
          // Handle messages from the vimeo player only
        if (!(/^https?:\/\/player.vimeo.com/).test(event.origin)) {
          //return false;
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


      // end vimeo
    }
  });
  $(document).ready(function() {
    playa.init();
  });
}(jQuery);