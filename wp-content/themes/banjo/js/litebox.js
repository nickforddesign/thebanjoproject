/* liteBox.js
   By Nick Ford
   nickforddesign@gmail.com
*/

var liteBox = {};

!function($){
  if (typeof jQuery == undefined) if (console in window) console.warn('liteBox.js requires jQuery')
  $.extend(liteBox, {
    init: function() {
      var lb = this.createElement('lb', 'div'),
          cover = this.createElement('lb-cover', 'div'),
          container = this.createElement('lb-container', 'div');
          preloader = this.createElement('preloader', 'div');
          navigation = this.createElement('lb-navigation');

      navigation.innerHTML = '<a class="arrow-left disabled no-ajaxy" href="#"><svg viewBox="0 0 56 56"><polygon fill="#FFFFFF" points="38.7,51.6 15.7,28.6 38.7,5.6 40.1,7 18.5,28.6 40.2,50.2"/></svg></a> <a class="arrow-right no-ajaxy" href="#"><svg viewBox="0 0 56 56"><polygon fill="#FFFFFF" points="17.1,51.6 40.2,28.6 17.1,5.6 15.7,7 37.3,28.6 15.7,50.2"/></svg></a>';

      lb.appendChild(preloader);
      lb.appendChild(cover);
      lb.appendChild(container);
      lb.appendChild(navigation);
      document.body.appendChild(lb);
      isTransitioning = false;
      this.initEventHandlers();
    },
    createElement: function(classes, element) {
      if (!element) element = 'div';
      var el = document.createElement(element);
      el.className = classes;
      return el;
    },
    constructModal: function(type, url, title, description) {
      if (typeof url == 'string') {

        if (type == 'video') {
          var id = '', embedUrl = '';

          $('.lb-container').addClass('video');

          if (url.indexOf('vimeo') > -1) {
            if (url.indexOf('player.vimeo.com/video/') > -1) {
              embedUrl = url;
            } else {
              id = url.split('.com/')[1];
              embedUrl = 'https://player.vimeo.com/video/' + id + '?autoplay=1&color=ea9e39&title=0&byline=0&portrait=0';
            }

          } else if (url.indexOf('youtu') > -1) {
            if (url.indexOf('youtu.be') > -1) {
              id = url.split('youtu.be/')[1].split('?')[0]; // should i allow time specific embedding?
            } else if (url.indexOf('youtube.com/watch?v=') > -1) {
              id = url.split('youtube.com/watch?v=')[1].split('?')[0];
            }
            embedUrl = 'https://www.youtube.com/embed/' + id;
          }

          var $iframeContainer = liteBox.createElement('lb-video', 'div'),
              $iframe = liteBox.createElement('lb-iframe', 'iframe'),
              $title = liteBox.createElement('lb-title', 'h3');

          $title.innerHTML = title;
          $iframe.src = embedUrl;
          $iframeContainer.appendChild($iframe);
          //$iframeContainer.appendChild($title);
          $('.lb-container').append($($iframeContainer)).append($($title));
          $('.lb-iframe').load(function(e) {
            $('.lb').addClass('active').removeClass('loading');
            liteBox.initVideoEvents();
          })

        } else if (type == 'images') {

          var $galleryContainer = liteBox.createElement('lb-gallery-container'),
              $gallery = liteBox.createElement('lb-gallery', 'ul');

          var imageArray = url.split(','), count = 0;

          imageArray.forEach(function(x) {
            var $listItem = liteBox.createElement('lb-image-li', 'li'),
                $image = liteBox.createElement('lb-image', 'img');
            if (count == 0) {
              $listItem.className += ' active';
              count++;
            } else {
              $('.lb').addClass('gallery');
            }
            $image.src = x;
            $image.draggable = false;
            $listItem.appendChild($image);
            $gallery.appendChild($listItem);
          });

          $galleryContainer.appendChild($gallery)
          $('.lb-container').append($($galleryContainer));
          $('.lb-container img').load(function() {
            $('.lb').addClass('active').removeClass('loading');
            liteBox.updateButtons();
          });

        } else if (type == 'html') {

          $.ajax(url + '?json=1')
            .done(function(data) {
              var content = data['post']['content'],
                  $textContainer = liteBox.createElement('lb-html', 'div'),
                  $titleContainer = liteBox.createElement('lb-title', 'h3'),
                  $lbFade = liteBox.createElement('lb-fade', 'div')

              $titleContainer.innerHTML = title;
              $textContainer.appendChild($titleContainer);
              $textContainer.innerHTML += content;
              $textContainer.appendChild($lbFade);

              $('.lb-container').html($textContainer).parent().addClass('active').removeClass('loading')
            });

        } else {
          if (console in window) console.error('First argument should be "video", "images", or "audio"');
        }
      } else {
        if (console in window) console.error('Expected value is a url in string format');
      }
    },
    updateButtons: function() {
      var $activeSlide = $('.lb-image-li.active'),
          $prevSlide = $activeSlide.prev(),
          $nextSlide = $activeSlide.next();

      $('.lb-navigation a').removeClass('disabled');

      if (!$prevSlide.length) {
        $('.arrow-left').addClass('disabled');
      } else if (!$nextSlide.length) {
        $('.arrow-right').addClass('disabled');
      }
    },
    nextSlide: function() {
      console.log('nextSlide()')
      if (!isTransitioning) {
        var $activeSlide = $('.lb-image-li.active');

        if (!$activeSlide.is(':last-child')) {
          isTransitioning = true;
          $activeSlide.animate({
            'margin-left': '-40px',
            'opacity': '0'
          }, {
            duration: 300,
            complete: function() {
              $activeSlide.css({'margin-left': '0','opacity': '1'}).removeClass('active').next().addClass('active');
              isTransitioning = false;
              liteBox.updateButtons();
            }
          });
        }
      }
    },
    prevSlide: function() {
      console.log('prevSlide()')
      if (!isTransitioning) {
        var $activeSlide = $('.lb-image-li.active');
        if (!$activeSlide.is(':first-child')) {
          isTransitioning = true;
          $activeSlide.animate({
            'margin-right': '-40px',
            'opacity': '0'
          }, {
            duration: 300,
            complete: function() {
              $activeSlide.css({'margin-right': '0','opacity': '1'}).removeClass('active').prev().addClass('active');
              isTransitioning = false;
              liteBox.updateButtons();
            }
          });
        }
      }
    },
    closeModal: function() {
      $('.lb-container').html('');
      $('.lb-container').removeClass('video');
      $('.lb').removeClass('active').removeClass('gallery')
      isTransitioning = false;
    },
    initEventHandlers: function() {

      $('html').on('click', 'a[data-litebox]', function(e) {
        e.preventDefault();
        $('.lb').addClass('loading');
      }).on('click', 'a[data-litebox="video"]', function(e) {
        var description = null;
        if ($(this)[0].hasAttribute('data-description')) description = $(this).attr('data-description');
        liteBox.constructModal('video', $(this)[0].href, $(this).attr('data-title'), description);
      }).on('click', 'a[data-litebox="images"]', function(e) {
        liteBox.constructModal('images', $(this).attr('data-images'));
      }).on('click', 'a[data-litebox="html"]', function(e) {
        liteBox.constructModal('html', $(this).attr('data-html'), $(this).attr('data-title'));
      }).on('click', '.lb-navigation a', function(e) {
        e.preventDefault();
        if ($(this).hasClass('arrow-left')) {
          liteBox.prevSlide();
        } else if ($(this).hasClass('arrow-right')) {
          liteBox.nextSlide();
        }
      });

      var lbis = document.getElementsByTagName('body')[0];
      var swipable = new Hammer(lbis, {
        touchAction: 'auto'
      });

      swipable.on('swipeleft swiperight tap', function(event) {
        if (event.target.className === 'lb-cover' || event.target.className === 'lb-image') {
          //console.log(event.type)
          if (!isTransitioning) {
            if (jQuery('.lb-image').length == 1) {
              liteBox.closeModal();
            }
            if (event.target.className === 'lb-cover' && event.type === 'tap') {
              liteBox.closeModal();
            } else {
              if (jQuery('.lb-image').length > 1) {
                if (event.type === 'swipeleft' || event.type === 'tap') {
                  liteBox.nextSlide();
                } else if (event.type === 'swiperight') {
                   liteBox.prevSlide()
                }
              }
            }
          }
        }
      });

      document.onkeydown = checkKey;

      function checkKey(event) {
        event = event || window.event;

        if ($('.lb-gallery').is(':visible')) {
          if (event.keyCode == '37') {
            event.preventDefault();
            liteBox.prevSlide();
          } else if (event.keyCode == '39') {
            event.preventDefault();
            liteBox.nextSlide();
          } else if (event.keyCode == '38') {
            event.preventDefault();
          } else if (event.keyCode == '40') {
            event.preventDefault();
          }
        }
      }
    },
    initVideoEvents: function() {
      jQuery(function($) {
        console.log('initVideoEvents')
        var player = $('.lb-iframe');
        var playerOrigin = '*';
        console.log(player)

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
          liteBox.closeModal();
        }
        function post(action, value) {
          var data = {
            method: action
          };

          if (value) data.value = value;
          var message = JSON.stringify(data);
          jQuery('.lb-iframe')[0].contentWindow.postMessage(data, playerOrigin);
        }
        function onMessageReceived(event) {
          if (!(/^https?:\/\/player.vimeo.com/).test(event.origin)) return false;
          if (playerOrigin === '*') playerOrigin = event.origin;
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
    }
  });

  $(document).ready(function() {
    liteBox.init();
  });
}(jQuery);
