var playa={};!function($){return void 0==typeof jQuery&&console in window?(console.warn("playa.js requires jQuery"),!1):($.extend(playa,{init:function(){this.widget={};var e=$(".music-player"),a=$(".music-player--iframe-container iframe")[0],n=$(".music-player--song-title"),t=document.querySelectorAll(".music-player--controls ul li a");playa.widget=SC.Widget(a),playa.currentSong="",playa.widget.bind(SC.Widget.Events.READY,function(){playa.widget.bind(SC.Widget.Events.PLAY,function(){e.hasClass("active")||e.trigger("show"),e.addClass("playing")}),playa.widget.getCurrentSound(function(e){var a=e.title;playa.currentSong!==a&&(playa.currentSong=a),n.text(a)}),playa.widget.bind(SC.Widget.Events.PAUSE,function(){$(".music-player").removeClass("playing")}),playa.widget.bind(SC.Widget.Events.PLAY_PROGRESS,function(e){var a=playa.msToTime(e.currentPosition);$(".music-player--time").text(a)});var a;e.unbind().on("show",function(){e.addClass("active"),a=setTimeout(function(){e.removeClass("active")},3600)}),e.on({mouseenter:function(){e.hasClass("active")||e.trigger("show"),clearTimeout(a)},mouseleave:function(){a=setTimeout(function(){e.removeClass("active")},3600)}})}),this.loaded||($(".music-player--controls li svg").on("click",function(){playa.widget[$(this).attr("data-action")]()}),this.initEventHandlers()),this.loaded=!0},action:function(e){"play"!==e&&"pause"!==e&&"toggle"!==e?(console.log("no"),console in window&&console.error("Invalid action. Use 'play', 'pause', or 'toggle'.")):(console.log("ok"),playa.widget[e]())},msToTime:function(e){var a=e%1e3;e=(e-a)/1e3;var n=e%60;e=(e-n)/60;var t=e%60;return t+":"+("0"+n).slice(-2)},createElement:function(e,a){e||(e="div");var n=document.createElement(e);return n.className=a,n},loadMusic:function(e){$(".music-player").removeClass("playing"),$(".music-player--time").text("0:00");var a=playa.createElement("iframe");a.src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/"+e+"&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=false&amp;auto_play=true",$(".music-player--iframe-container").html("").append($(a)),playa.init()},initEventHandlers:function(){$(document).on("click",".music-player--link",function(e){e.preventDefault(),playa.loadMusic($(this).attr("data-music")),$(".music-player").trigger("show")}),$(document).keydown(function(e){65==e.keyCode?$(".music-player").toggleClass("active"):32==e.keyCode&&$(".music-player--toggle").click()})},initVimeoListener:function(){function e(){console.log("onReady()"),i("addEventListener","pause"),i("addEventListener","finish"),i("addEventListener","playProgress")}function a(){console.log("paused")}function n(){$(".music-player").hasClass("playing")&&playa.action("pause"),console.log("playing")}function t(){console.log("finished")}function i(e,a){var n={method:e};a&&(n.value=a);var t=JSON.stringify(n);s[0].contentWindow.postMessage(t,l)}function o(i){!/^https?:\/\/player.vimeo.com/.test(i.origin),"*"===l&&(l=i.origin);var o=JSON.parse(i.data);switch(o.event){case"ready":e();break;case"pause":a();break;case"playProgress":n();break;case"finish":t()}}var s=$('iframe[src*="vimeo.com"]'),l="*";console.log(s),console.log(s.length),window.addEventListener?(console.log("addEventListener"),window.addEventListener("message",o,!1)):window.attachEvent("onmessage",o,!1)}}),void $(document).ready(function(){playa.init()}))}(jQuery);
//# sourceMappingURL=./playa-min.js.map