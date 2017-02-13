/* banjo.js
   By Nick Ford
   nickforddesign@gmail.com
*/

// @codekit-prepend "thirdparty.js"
// @codekit-prepend "litebox.js"
// @codekit-prepend "playa.js"
// @codekit-prepend "mapbox.js"
// @codekit-prepend "soundcloud.js"

var banjo = {};

String.prototype.capitalize = function() {
  return this.charAt(0).toUpperCase() + this.slice(1);
};

jQuery(function($) {

  $.extend(banjo, {

    init: function(r) {
    	if ($('body').hasClass('error404')) return false;
      // Root global
      root = r;

      // Is Touch Device?

      if (!('ontouchstart' in document.documentElement)) $('body').addClass('no-touch');

      // Cleanup url if there was a query
      var initUrl = window.location.href,
          initPage = this.getPage();

      // Codekit url fixes
      if (initUrl.indexOf('&') > -1) initUrl = initUrl.substring(0, initUrl.indexOf('&'));
      if (initUrl.indexOf('?ckcachecontrol=') > -1) initUrl = initUrl.split('?ckcachecontrol=')[0];

      initPath = initUrl.replace(root + '/', '');
      initUrl = initUrl.split('?');
      initQuery = initUrl[1];
      queryList = initPath.split(new RegExp('[/?]', 'g'));

      // console.log(initPath)
      // console.log(initPage)
      // console.log(initQuery)

      $(window).one('statechangecomplete', function() {
        banjo.bodyClass();
        banjo.initPages();
        banjo.initEventHandlers();
        banjo.loaded = true;
        banjo.toggleLoad();
      });

      // If there is a query string
      if (initQuery) {

        if (initPage == 'region') {
          if (queryList.length > 2) {
            queryList.splice(0, 1);
            banjo.sneakState(initPage + '/' + queryList.join('/'));
            banjo.mapQuery = initQuery;
            $(window).trigger('statechangecomplete')
          }
        } else {
          // Replace URL with the query minus the ?
          if (initPage == 'people') {
            banjo.sneakState(initPage + '/' + initQuery);
            $(window).trigger('statechangecomplete')
          } else {
            History.replaceState({}, '', root + '/' + initPage + '/' + initQuery);
          }
        }
      } else {
        History.replaceState({}, '', root + '/' + initPath);
        $(window).trigger('statechangecomplete')
        //banjo.toggleLoad();
      }
      this.navClass();
    },

    initPages: function() {
      switch (banjo.getPage()) {
        case 'people':
          banjo.navClass();
				  console.log(jQuery('img.lazy'))
				  jQuery("img.lazy").lazyload({
				    effect : "fadeIn",
				    container: jQuery("#lineup-list")
				  });
          !banjo.getQuery() ? banjo.loadRandomPerson() : banjo.loadPerson(banjo.getQuery(), banjo.toggleLoad);
          break;
        case 'timeline':
          banjo.tocify();
          banjo.initTimelineEvents();
          if (banjo.getQuery()) {
            var query = banjo.getQuery() + '';
            if (!banjo.loaded) {
              queryMain = $('#timeline-container *[data-slug="' + query + '"]');
              querySidebar = $('.timeline-sidebar *[data-slug="' + query + '"]');
              $('main').animate({
                scrollTop: queryMain.position().top - 30
              });
              $('.timeline-sidebar-container').animate({
                scrollTop: querySidebar[0].offsetTop - 30
              });
            }
          }
          break;
        case 'videos' :
          banjo.initArchiveEvents();
          break;
        case 'timelines':
          var urlFix = root + '/timeline/' + banjo.getQuery();
          History.pushState({}, '', urlFix);
          break;
        case 'search':
          banjo.toggleLoad();
          break;
      }
      $('main').scrollTop(0);
    },

    getPage: function() {
      var h = window.location.href;
          h = h.replace(root, '').split('/')[1].split('?')[0];
      return h;
    },

    getQuery: function() {
      var h = window.location.href;
      h = h.replace(root, '').split('/');
      h.splice(0, 2);
      if (h == '') h = false;
      return h;
    },

    sneakState: function(slug, title) {
      title ? title += ' | ' : title = '';
      // Passing the slug object so ajaxify wont fire (custom feature)
      History.replaceState({slug : ''}, title + 'The Banjo Project', root + '/' + slug);
    },

    getRandomFromArray: function(array) {
      return array[Math.floor(Math.random() * array.length)];
    },

    loadPerson: function(slug, callback) {
      $('#lineup-list').addClass('active');
      $('#lineup-list li').removeClass('active');
      $('#lineup-list li[data-slug="' + slug + '"]').addClass('active');
      $('.lineup-loader').addClass('loading');


      var $active = $('#lineup-list li.active')[0];

      if ($active.getBoundingClientRect().left < 0 || $active.getBoundingClientRect().right > $(window).width() ) {
        var offset = $('#lineup-list li.active').offset().left - $(window).width()/2 - $('#lineup-list li.active').outerWidth()/2;
        $('#lineup-list').scrollLeft(offset);
      }

      slug = slug + '';
      var jsonUrl = root + '/peoples/' + slug;
      $.ajax({
        url: jsonUrl,
        success: function(data, textStatus, jqXHR) {
          // convert to dom element and extract html
          var hh = document.createElement('div');
          hh = $(hh).append($(data));
          var newContent = hh.find('.content').children();
          $('.content').html(newContent);
          $('.lineup-loader').removeClass('loading');
        },
        complete: function() {
          if (typeof callback == 'function') callback();
        }
      });
      var title = slug.split('-')[0].capitalize() + ' ' + slug.toString().split('-')[1].capitalize();
      banjo.sneakState('people/' + slug, title);
    },

    loadRandomPerson: function() {
      $('#lineup-list li').removeClass('active');
      this.loadPerson($(this.getRandomFromArray($('#lineup-list li'))).attr('data-slug'));
    },

    /*makeLoop: function() {
      $('#lineup-list').bind('scroll', function() {
        //console.log($('#lineup-list li.active')[0].getBoundingClientRect())
      });
      /*loopWidth = $("#lineup-list")[0].scrollWidth;
      var dupe = $('#lineup-list li').clone();
      dupe.appendTo('#lineup-list');
      dupe.clone().appendTo('#lineup-list');

      $("#lineup-list").bind('scroll', function() {
        console.log($(this).scrollLeft())
        if ($(this).scrollLeft() > loopWidth-80) {
          $(this).scrollLeft(148);
        } else if ($(this).scrollLeft() < 148) {
          $(this).scrollLeft(loopWidth-81);
        }
      }).scrollLeft(148);
    },*/

    tocify: function() {
      $('.event').each(function(){
        var offset = $(this).offset().top,
            s = $(this).attr('data-slug');

        if (offset > -($(this).outerHeight()/2.8)) {
          var title = "Timeline";
          if (banjo.loaded) banjo.sneakState('timeline/' + s, title)
          $('.timeline-sidebar .sidebar-event:not([data-slug="' + s + '"])').removeClass('active');
          $('.timeline-sidebar *[data-slug="' + s + '"]').addClass('active');
          return false;
        }
      })
    },

    stickySidebar: function() {
      if ($('main').scrollTop() + $(window).height() > $('main')[0].scrollHeight - $('footer').outerHeight()) {
        $('.timeline-sidebar-container').css({
          'height': $(window).height() - (($('main').scrollTop() + $(window).height()) - ($('main')[0].scrollHeight - $('footer').outerHeight())) + 'px'
        });
      } else {
        $('.timeline-sidebar-container').css({
          'height': $(window).height() - 65 + 'px'
        });
      }
      banjo.tocify();
    },

    bodyClass: function() {
    	var newClass = banjo.getPage() || 'home';
      $('body').removeClass(function(index, css) {
        return (css.match (/(^|\s)banjo-\S+/g) || []).join(' ');
      }).addClass('banjo-' + newClass);
    },

    toggleLoad: function() {
      $('body').removeClass('loading');
    },

    navClass: function() {
      var url = window.location.href;
      $('header nav a').each(function() {
        if ($(this)[0].href == url) {
          $(this).addClass('active')
        } else if (url.indexOf('region') > -1 && $(this).text() == 'Map' || url.indexOf('people') > -1 && $(this).text() == 'People' || url.indexOf('search') > -1 && $(this).text() == 'Stories' || url.indexOf('topic') > -1 && $(this).text() == 'Stories' || url.indexOf('style') > -1 && $(this).text() == 'Stories') {
          $(this).addClass('active')
        } else {
          $(this).removeClass('active')
        }
      });
    },

    mapFocus: function(slug) {
      markers.eachLayer(function(m) {
        if (m.feature.properties.slug == slug) {
          m.openPopup();
          map.panTo(m.getLatLng());
          map.panBy([0, -200])
        }
      });
    },

    initEventHandlers: function() {
      // console.log('initEventHandlers()')
      // init Page functions on page change
      History.Adapter.bind(window, 'statechange', function(event){
        if (!History.getState().data.hasOwnProperty('slug')) {
          //console.log('page change: ' + banjo.getPage());
          banjo.navClass();
          $('header').removeClass('active');
          $(window).one('statechangecomplete', function() {
            banjo.bodyClass();
            banjo.initPages();
          })
        }
      });

      //var tickerLoop = setTimeout(animateTicker, 20), offset;

      // This is intentionally scoped this way
      function animateTicker() {
      	console.log('animateTicker()')
        var i = $('#lineup-list').scrollLeft();
        i = i + offset;
        $('#lineup-list').scrollLeft(i);
        tickerLoop = setTimeout(animateTicker, 20);
      }

      // Event delegation
      $(document)
      .on('click', function(e) {
        if ($('header').hasClass('active')) if (!$(e.target).is('header, header a, .no-ajaxy')) $('header').removeClass('active');
      })
      .on('click', '.nav-toggle', function(e) {
          e.preventDefault();
          var h = $('header');
          h.hasClass('active') ? h.removeClass('active') : h.addClass('active')
          //$('header').toggleClass('active');
      })
      .on('click', '.banjo-home .grid > div', function(e) {
        $(e.target).parent().addClass('active').siblings().removeClass('active').addClass('collapse').parent().addClass('active');
        $(e.target).siblings().removeClass('active').addClass('collapse');
        $(e.target).removeClass('collapse').addClass('active').parent().addClass('active');

        if ($(e.target).attr('data-url')) {
          if ($(e.target).attr('data-url').indexOf('intro') > -1) {
            $('body').addClass('banjo-intro');
          }
          setTimeout(function() { // should probably do this differently
            History.pushState({}, '', $(e.target).attr('data-url'));
          }, 1010);
        }
      })
      .on('click', '#lineup-list li, .people-link', function(e) {
        e.preventDefault();
        if (!$(this).hasClass('active')) {
          $('.lineup-loader').addClass('loading');
          var slug = $(this).attr('data-slug');
          banjo.loadPerson(slug, $(this));
        }
      })
      .on('mouseenter', '.lineup .btn-nav', function() {
      	console.log('mouseover')
        offset = $(this).hasClass('btn-left') ? -4 : 4;
        tickerLoop = setTimeout(animateTicker, 10);
      })
      .on('mouseleave', '.lineup .btn-nav', function() {
      	console.log('mouseleave')
        offset = 0;
        clearTimeout(tickerLoop);
      })
      .on('click', '.lineup .btn-nav', function() {
        var jump = $(this).hasClass('btn-left') ? -100 : 100;
        $('#lineup-list').scrollLeft($('#lineup-list').scrollLeft() + jump);
      })
      .on('click', '.btn-action', function(e) {
        e.preventDefault();
      })
      .on('click', '#timeline-container .event, .timeline-sidebar .sidebar-event', function() {
        banjo.timelineFocus($(this).attr('data-slug'))
      })
      .on('submit', '.search-form', function(e) {
        e.preventDefault();
        var query = $('.search-field').val();
        History.pushState({}, '', root + '/search/' + query)
      })

      // Window events
      $('main').on({
        scroll: function() {
          if (banjo.getPage() == 'timeline') banjo.stickySidebar();
        }
      });
      $(window).on({
        resize: function() {
          if (banjo.getPage() == 'timeline') banjo.stickySidebar();
        }
      });

      // Temporary state toggle for index REMOVE FOR PRODUCTION
      $(document).keydown(function(e) {
        if (e.keyCode == 16) {
          $('.grid').removeClass('active').find('.active').removeClass('active');
          $('.grid').find('.collapse').removeClass('collapse');
          $('body').removeClass('banjo-intro');
        }
      });
    },
    timelineFocus: function(query) {
    	  var queryMain = $('#timeline-container *[data-slug="' + query + '"]');
        var querySidebar = $('.timeline-sidebar *[data-slug="' + query + '"]');

        $('main').animate({
          scrollTop: queryMain.position().top - 30
        });
        $('.timeline-sidebar-container').animate({
          scrollTop: querySidebar[0].offsetTop - 30
        });
        $('.timeline-sidebar').animate({
          scrollLeft: querySidebar[0].offsetLeft
        });
    },
    initArchiveEvents: function() {
      $('.post-filters').append($('.post-filters-container'));
      $('input[type=radio][name=filter]').change(function(){
        var value = $(this).val();
        $('.post-filters .chosen-container-single').removeClass('active');
        $('.post-filters--' + value).addClass('active');
        var query = $('.post-filters--' + value).val();
        banjo.filter(query, $('.post-single'));
      });
      $('label').click(function(e) {
      	var match = $('#' + $(this).attr('for'));
      	if (match.is(':checked')) {
      		e.preventDefault();
      		setTimeout(function() {
      			match[0].checked = false;
      			banjo.filter('default', $('.post-single'));
      			var value = match.val();
      			$('.post-filters--' + value + '.active').removeClass('active')
      		}, 200)
      	}
      })
      $('.post-filters select').chosen({
        disable_search_threshold: 30,
        inherit_select_classes: true
      });
      $('.post-filters select').change(function() {
      	if (!$("input[name='filter']:checked").val()) {
      		$('label[for=radio-people').click();
      	}
        var query = $(this).val();
        banjo.filter(query, $('.post-single'));
        activeFilter = query;
      });
    },
    initTimelineEvents: function() {
			$('.event').each(function() {
				var filters = $(this).children('.event-meta').attr('data-filters');
				var slug = $(this).attr('data-slug');
				$('.timeline-sidebar *[data-slug="' + slug + '"]').attr('data-filters', filters);
				$(this).attr('data-filters', filters);
			});

      $('.post-filters').append($('.post-filters-container'));
      $('input[type=radio][name=filter]').change(function(){
        var value = $(this).val();
        $('.post-filters .chosen-container-single').removeClass('active');
        $('.post-filters--' + value).addClass('active');
        var query = $('.post-filters--' + value).val();
        console.log('filtering')
        banjo.filter(query, $('.event'));
        banjo.filter(query, $('.timeline-sidebar .sidebar-event'));
      });
      $('label').click(function(e) {
      	var match = $('#' + $(this).attr('for'));
      	if (match.is(':checked')) {
      		e.preventDefault();
      		setTimeout(function() {
      			match[0].checked = false;
      			banjo.filter('default', $('.event'));
        		banjo.filter('default', $('.timeline-sidebar .sidebar-event'));
      			var value = match.val();
      			$('.post-filters--' + value + '.active').removeClass('active')
      		}, 200)
      	}
      })
      $('.post-filters select').chosen({
        disable_search_threshold: 30,
        inherit_select_classes: true
      });
      $('.post-filters select').change(function() {
      	if (!$("input[name='filter']:checked").val()) {
      		$('label[for=radio-people').click();
      	}
        var query = $(this).val();
        console.log(query)
        banjo.filter(query, $('.event'));
        banjo.filter(query, $('.timeline-sidebar .sidebar-event'));
        activeFilter = query;
      });
    },
    filter: function(query, array) {
      var queue = [];
      array.hide();
      $.each(array, function() {
        if (query == 'default') {
          queue.push($(this))
        } else {
          if ($(this).attr('data-filters').split(',').indexOf(query) > -1) queue.push($(this))
        }
      });

      count = 0;
      $.each(queue, function() {
        $(this).delay((count) * 80).queue(function(next) {
          $(this).fadeIn('fast');
          next();
        });
        count++;
      });
    }
  });
});