// Diese Datei wird mit Gulp an Ihre korrekte Position
// automatisiert aus dem Klick-Dummy ausgespielt.
// Daher bitte Änderungen an dieser Datei nur im Klick-Dummy vornehmen
// und mit Gulp rendern und deployen lassen.

function isTouchDevice() {
  return (('ontouchstart' in window) ||
    (navigator.maxTouchPoints > 0) ||
    (navigator.msMaxTouchPoints > 0));
}

// JQuery
$(document).ready(function () {

  // Scroll to anchor and 130px back cause of navigation bar
  if (window.location.href.search(/\#/i) != -1) {
    my_current_location = window.location.href.split("#")[1].split("?")[0];
    if ($("#" + my_current_location).length > 0) {
      $('html,body').stop(true, true).delay(500).animate({
        scrollTop: $("#" + my_current_location).offset().top - 130
      }, 500);

      setTimeout(function () {
        $('html,body').stop(true, true).delay(500).animate({
          scrollTop: $("#" + my_current_location).offset().top - 130
        }, 500);
      }, 500);
    }
  }


  $("#mainnavAchtung > ul > li").hover(function () {
    index = $(this).index();

    $("#subnavAchtung").addClass("loaded");
    $("#subnavAchtung").addClass("open");
    $("#subnavAchtung ul").removeClass("active");
    $("#subnavAchtung ul").eq(index).addClass("active");
  }, function () {
    $("#subnavAchtung").removeClass("open");
  });


  $("#subnavAchtung").hover(function () {
    $("#subnavAchtung").addClass("open");
  }, function () {
    $("#subnavAchtung").removeClass("open");
  });


  if (isTouchDevice()) {
    // 1st click, add "clicked" class, preventing the location change. 2nd click will go through.
    $("#mainnav-content .lvl1 > a").click(function (event) {
      // Perform a reset - Remove the "clicked" class on all other menu items
      $("#mainnav-content .lvl1 > a").not(this).removeClass("clicked");
      $(this).toggleClass("clicked");
      if ($(this).hasClass("clicked")) {
        event.preventDefault();
      }
    });
  }


  // Change Text of Marker ###AMOUNT###
  $("h1:contains('###AMOUNT###')").html(function (i, h) {

    var amount = $('main').data('amount');

    return h.replace(/###AMOUNT###/, "<span class=\"text-unbold\">(" + amount + ")</span>");
  });

  // Focuspoint
  $('.focuspoint').focusPoint();

  // Slick Slider
  $('.slider').slick(
    // prevArrow = ''
  );

  // Unveil
  // Lazy loading of images by loading them 200 miliseconds after initialization of img element
  // by fake calling unveil via manual triggering of main functionality (more possibilities for debugging)
  $("img").unveil(200, function () {

    $(this).trigger("unveil");
    // alert($(this).attr('src') + " unveiled!");
  });

  // Collapse
  $('.akkordeon').collapse();

  // Popup-Content
  // Add Parameters via JS to archive functionality
  $('.link-popup, .link-popup-btn').attr('data-toggle', 'lightbox');
  $('.link-popup, .link-popup-btn').attr('data-type', 'html');
  $('.link-popup, .link-popup-btn').attr('data-width', '940');
  $('.link-popup, .link-popup-btn').attr('data-max-width', '940');
  $('.link-popup, .link-popup-btn').attr('data-title', '');
  $('.link-popup, .link-popup-btn').attr('data-footer', '');

  // Enables printing of images with lazy loading
  if ('onbeforeprint' in window) {

    var printEventListener = function () {

      window.removeEventListener('beforeprint', printEventListener);

      $("img").unveil(0, function () {

        $(this).trigger("unveil");
        // alert($(this).attr('src') + " unveiled!");
        $('.focuspoint').focusPoint();
      });
    };

    window.addEventListener('beforeprint', printEventListener);

  } else if (window.matchMedia) {

    var mediaQueryList = window.matchMedia('print');

    var mediaQueryListener = function (mql) {

      if (mql.matches) {

        mediaQueryList.removeEventListener("change", mediaQueryListener);

        $("img").unveil(0, function () {

          $(this).trigger("unveil");
          // alert($(this).attr('src') + " unveiled!");
          $('.focuspoint').focusPoint();
        });
      }
    }

    mediaQueryList.addEventListener("change", mediaQueryListener);
  }

  // Event AFTER a dropdown from main-navigation is showen
  $('#main-navigation').on('shown.bs.dropdown', function (event) {

    // Unveil images in Dropdown, e.g. from news
    $("#main-navigation img").unveil(0, function () {

      $(this).trigger("unveil");
      // alert($(this).attr('src') + " unveiled!");
      $('.focuspoint').focusPoint();
    });
  });

  // Event AFTER a dropdown from tool-navigation is showen
  $('#tool-navigation').on('shown.bs.dropdown', function (event) {

    $('#ke_search_sword_top').focus();
  });

  $('#tool-navigation [data-toggle=dropdown]').on('click', function (event) {
    event.preventDefault();
    event.stopPropagation();
    $('#tool-navigation [data-toggle=dropdown]').dropdown('toggle');

  });

  // Collapse-Fake with effect in desktop dropdown

  // $('#main-navigation [data-toggle=dropdown]').on('click', function(event) {
  //
  //     event.preventDefault();
  //     event.stopPropagation();
  //
  //     $('#main-navigation .dropdown-menu.open').slideToggle(0);
  //
  //     $(this).next('.dropdown-menu').slideToggle(1000);
  //     $(this).next('.dropdown-menu').toggleClass('open');
  // });


  // Collapse-Fake with effect in mobile dropdown
  $('#mobile-navigation .first-level .dropdown-toggle').each(function (dtIndex, dtItem) {

    $(this).next('ul.second-level').removeClass('opened').addClass('closed');
    $(this).next('ul.second-level').hide();

    $(dtItem).on('click', function (event) {

      event.preventDefault();
      event.stopPropagation();

      if ($(this).next('ul.second-level').hasClass('opened')) {

        $(this).next('ul.second-level').removeClass('opened').addClass('closed');
        $(this).next('ul.second-level').slideUp();

      } else {

        $(this).next('ul.second-level').removeClass('closed').addClass('opened');
        $(this).next('ul.second-level').slideDown();
      }

      return true;
    });
  });

  // Collapse Icon Switch
  $('.akkordeon, .tx_mask.collapse-button').each(function (aIndex, accordion) {

    $(accordion).on('click', '.btn-collapse', { accordion: accordion }, function (event) {

      event.preventDefault();

      if (!$(event.data.accordion).hasClass("independent"))
        $(event.data.accordion).find('.btn-collapse').not(this).removeClass('btn-minus').addClass('btn-plus');

      if ($(this).hasClass('collapsed')) {

        $(this).removeClass('btn-plus').addClass('btn-minus');

      } else {

        $(this).removeClass('btn-minus').addClass('btn-plus');
      }
    });

    $(accordion).on('click', '.card-header h3', function () {

      $(this).next('.btn-collapse').trigger('click');
    });
  });

  // Carousel
  $('.carousel').carousel({});

  // Card rotation
  $('.btn-rotate').click(function () {

    var $container = $(this).parents('.rotate-container').first();
    $container.find('.card-front').toggleClass('rotate-card-front');
    $container.find('.card-back').toggleClass('rotate-card-back');
  });

  // Akkordeon
  // This event is fired when a collapse element has been made visible to the user (will wait for CSS transitions to complete).
  $('.akkordeon').on('shown.bs.collapse', function () {

    $('.focuspoint').adjustFocus();
  })

  // Dropdown
  // Occurs when the dropdown is fully shown (after CSS transitions have completed)
  $('.navbar').on('shown.bs.dropdown', function () {

    $('.focuspoint').adjustFocus();
  });

  // Carousel
  // Set all carousel-items to display block because focuspoint can't adjust
  // focus on hidden images!
  $('.carousel').carousel('pause');
  $('.carousel').find('.carousel-item').css('display', 'block');
  $('.focuspoint').adjustFocus();
  $('.carousel').carousel('cycle');

  // Social-Sharer Popup
  $("#socialPolicy").on('show.bs.modal', function (eventOpen) {

    var socialButtonClicked = $(eventOpen.relatedTarget);

    shareTitle = $(socialButtonClicked).attr("title");
    shareUrl = $(socialButtonClicked).attr("href");
    sharePlatform = $(socialButtonClicked).attr("data-share-platform");
    ogImagePath = $('meta[property="og:image"]').attr('content');

    if (sharePlatform == 'pinterest') {

      shareUrl += ogImagePath;
    }

    $(this).find('#socialPolicyBtn').bind('click', function (eventClick) {

      var width = 600,
        height = 460,
        left = ($(window).width() - width) / 2,
        top = ($(window).height() - height) / 2,
        url = shareUrl,
        opts = 'status=1' +
          ',width=' + width +
          ',height=' + height +
          ',top=' + top +
          ',left=' + left;

      window.open(url, sharePlatform, opts);

    });
  });

  $("#socialPolicy").on('hide.bs.modal', function (eventOpen) {

    $(this).find('#socialPolicyBtn').unbind('click');
  });

  // SVG Image class handling
  $('.has-file-extension-svg').each(function (svgItemIndex, svgItem) {

    $(this).parents('.ce-gallery').addClass('has-file-extension-svg');
    $(this).removeClass('has-file-extension-svg');
  });

  // Hover Effect on Themenspecial cards
  $('.card-items .card').hover(
    // mouseenter
    function () {

      $(this).next('.card-background-1').addClass('hovered');
      $(this).next().next('.card-background-2').addClass('hovered');
    },

    // mouseleave
    function () {

      $(this).next('.card-background-1').removeClass('hovered');
      $(this).next().next('.card-background-2').removeClass('hovered');
    }
  );

  // Check empty value in Newsletter-Box in footer
  $('#subscribe-newsletter').submit(function (e) {

    if ($(this).find('input').val().length === 0) {

      alert('Bitte geben Sie eine gültige E-Mail-Adresse an');
      return false;
    }
  });

  // Keep social buttons in view when page is scrolled
  var elementPosition = $('.news-item__teaser-desc #social_buttons').offset();

  $(window).scroll(function () {

    // Scroll Social Navigation with the page but only if Screen width is > 992 (bootstraps "lg-min")
    var hasSocialButtons = $('.news-item__teaser-desc #social_buttons');

    if (hasSocialButtons.length) {

      if ($(window).width() > '992' && $(window).scrollTop() > (elementPosition.top - $('header.sticky-top').height())) {

        //$('.news-item__teaser-desc #social_buttons').css('position', 'fixed').css('bottom', '1.7rem').css('left', '1.7rem');

      } else {

        $('.news-item__teaser-desc #social_buttons').css('position', 'static');
      }
    }

    // Hide autocomplete on scroll
    $('.ui-autocomplete').hide();

    // Show to top button if scrolled more then 100 pixels
    if ($(this).scrollTop() > 100) {

      $('#totop').fadeIn();

    } else {

      $('#totop').fadeOut();
    }
  });

  // Scroll to top if #totop is clicked
  $('#totop').click(function () {

    $('body,html').animate({

      scrollTop: 0
    }, 800);

    return true;
  });

  // Smooth scrolling for anchor links
  $("a[href^='#']").click(function (event) {
    var url = $(this).attr('href');

    // If link has a hash (Anchor)
    if (this.hash) {

      event.preventDefault();
      elem = $(this.hash);

      if ($(this.hash).length > 0) {
        tabmodul = $(this.hash).parents(".achtsamkeittabs");
        if (tabmodul.length > 0) {
          mobile_tab = tabmodul.find(".mobile-content:visible " + this.hash);
          if (mobile_tab.length > 0) {
            tab = mobile_tab.parents(".nav-item").find('.nav-link');
            elem = mobile_tab;
          } else {
            desktop_tab = tabmodul.find(".tab-content:visible " + this.hash);
            tab = desktop_tab.parents(".tab-pane").attr("id");
            if (tab) {
              tab = tab.replace("content", "");
              tab = tabmodul.find("#" + tab);
              elem = desktop_tab;
            }
          }

        }
        if (typeof tab !== 'undefined') {
          if (!tab.hasClass("active"))
            tab.tab('show');

          setTimeout(function () {
            calculatedOffset = elem.offset().top - $('header').height() - 30;
            $('html, body').animate({ scrollTop: calculatedOffset }, 1000);
          }, 200);
        } else {

          if (elem.offset().top > 0) {
            calculatedOffset = elem.offset().top - $('header').height() - 30;
            $('html, body').animate({ scrollTop: calculatedOffset }, 1000);
          }
        }
      }
    }
  });
});

// Lightbox
// http://ashleydw.github.io/lightbox/
// https://getbootstrap.com/docs/3.4/javascript/
$(document).on('click', '[data-toggle="lightbox"]', function (event) {

  event.preventDefault();

  $(this).ekkoLightbox({

    alwaysShowClose: true,

    onContentLoaded: function onContentLoaded() {
      // WACON Internet GmbH: Why do we need this?
      //alert(1);

      // Check if we have a css selector on data-footer
      dataFooterTarget = $($(this._$element).attr("data-footer"));

      if (dataFooterTarget.length > 0) {
        $(this._$modal).addClass("has-footer");

        // Then move the target html to ekko modal footer
        let modalFooter = $($(this._$modal)).find(".modal-footer");

        if (modalFooter.length > 0) {
          modalFooter.html("");
          modalFooter.append(dataFooterTarget.clone());
          modalFooter.children(".d-none").removeClass("d-none");
        }
      }
    }
  });

});

// Durchstarter-Kampagne: Terminauswahl im Content (Popup) via Click auf Link
$(document).on('click', '.closeAndSelect a', function (event) {

  event.preventDefault();

  var formFieldId = $(this).parent().parent().data('target-form-field');
  var selectValue = $(this).html();

  $('#' + formFieldId).val(selectValue);
  $('html, body').animate({ scrollTop: $('#' + formFieldId).offset().top - 180 });
  $('.ekko-lightbox').modal('toggle');

  return void (0);
});


/*
 * Video 2 Click
 */
function htmlspecialchars(str) {
  return str.replace('&', '&amp;').replace('"', '&quot;').replace("'", '&#039;').replace('<', '&lt;').replace('>', '&gt;');
}

function htmlspecialchars_decode(str) {
  return str.replace('&amp;', '&').replace('&quot;', '"').replace('&#039;', "'").replace('&lt;', '<').replace('&gt;', '>');
}

// function for setting cookies
function setWXCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// function for reading cookies
function getWXCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

// function to unlock all youtube videos on the whole site
function unlockYoutube() {
  // set wantYoutube = 1 for 30 days
  if (wx_isCookieAllowed("wantYoutube", true)) setWXCookie("wantYoutube", "1", "30");

  $(".twoclick_video_wrapper_youtube").each(function () {
    $(this).addClass("embed-responsive-item");
    $(this).parent().addClass("embed-responsive-16by9");
    $(this).find('.twoclick_video_trigger').hide();
    var url;
    var video_layer = $(this).find('.twoclick_video_layer');
    var ifrm = document.createElement("iframe");
    if (video_layer.data("type") == "playlist")
      url = "https://www.youtube-nocookie.com/embed/videoseries/?autoplay=0w&list="
    else
      url = "https://www.youtube-nocookie.com/embed/"
    ifrm.setAttribute("src", url + video_layer.data("youtube"));
    ifrm.setAttribute("allowfullscreen", "");
    ifrm.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen";
    ifrm.setAttribute("class", "embed-responsive-item");
    ifrm.setAttribute("frameborder", "0");
    video_layer.append(ifrm);
    video_layer.show();
  });
}

// function to unlock all vimeo videos on the whole site
function unlockVimeo() {
  // set wantVimeo = 1 for 30 days
  if (wx_isCookieAllowed("wantVimeo"), true) setWXCookie("wantVimeo", "1", 30);

  $(".twoclick_video_wrapper_vimeo").each(function () {
    $(this).addClass("embed-responsive-item");
    $(this).parent().addClass("embed-responsive-16by9");
    $(this).find('.twoclick_video_trigger').hide();
    var video_layer = $(this).find('.twoclick_video_layer');
    var iframe = htmlspecialchars_decode(video_layer.data("vimeo"));
    video_layer.html(iframe);
    video_layer.show();
  });
}

$(document).ready(function () {
  if ($('.twoclick_video_wrapper_youtube').length > 0) {
    if (getWXCookie("wantYoutube") != "1") {
      // if youtube is still locked, add event listener for unlock action
      $('.twoclick_video_wrapper_youtube').each(function () {
        _wrapper = $(this);
        _wrapper.children('.twoclick_video_trigger').find('input[type="button"]').click(function () {
          unlockYoutube();
        });
        _wrapper.removeClass("embed-responsive-item");
        _wrapper.parent().removeClass("embed-responsive-16by9");
      });
    } else {
      unlockYoutube();
    }
  }

  if ($('.twoclick_video_wrapper_vimeo').length > 0) {
    if (getWXCookie("wantVimeo") != "1") {
      // if vimeo is still locked, add event listener for unlock action
      $('.twoclick_video_wrapper_vimeo').each(function () {
        _wrapper = $(this);
        _wrapper.children('.twoclick_video_trigger').children('input[type="button"]').click(function () {
          unlockVimeo();
        });
        _wrapper.removeClass("embed-responsive-item");
        _wrapper.parent().removeClass("embed-responsive-16by9");
      });
    } else {
      unlockVimeo();
    }
  }
});


$(".auto-count-up").each(function (index, element) {
  var count = $(this),
    zero = { val: 0 },
    num = count.text(),
    duration = count.data("duration") / 1000,
    split = (num + "").split("."),
    decimals = split.length > 1 ? split[1].length : 0;

  gsap.to(zero, {
    val: num,
    duration: duration,
    ease: "circ.inOut",
    scrollTrigger: element,
    onUpdate: function () {
      count.text(zero.val.toFixed(decimals));
    }
  });
});


// Scroll to next slot
$('.bg-white-intro .arrowbox').click(function () {

  event.preventDefault();

  height = $(this).closest(".gib8").height();


  $('body,html').animate({

    scrollTop: height
  }, 800);

  return true;
});


$(".grid-collapse .grid-collapse-button").click(function () {
  event.preventDefault();

  var gridcollapse = $(this).parent().parent();
  gridcollapse.toggleClass("closed");

  if (gridcollapse.hasClass("closed")) {
    gridcollapse.find(".grid-collapse-dynamic").stop(true, true).slideUp();
  } else {
    gridcollapse.find(".grid-collapse-dynamic").stop(true, true).slideDown();
  }
});


var rotator = $("#displayanzeige_rotator");
if (rotator.length == 1) {
  var slides = $("#displayanzeige_rotator > .displayanzeige");
  var slide = slides.eq(0);
  var index = 0;
  slides.eq(0).css("zIndex", "10").css("opacity", "1");

  window.setTimeout(nextSlide, (rotationtime * 1000 * 60))
  //pagereload um 9:00
  scheduleReload(9, 0);

  function nextSlide() {
    index++;
    if (index > slides.length - 1) index = 0;

    slides.eq(index).css("left", "100%").css("opacity", "1").css("zIndex", "11");

    slide.animate({ left: "-100%" }, 1000, function () {
      $(this).css("opacity", "0").css("zIndex", "0")
    });
    slides.eq(index).animate({ left: 0 }, 1000, function () {
      $(this).css("zIndex", "10")
    });

    slide = slides.eq(index);

    window.setTimeout(nextSlide, (rotationtime * 1000 * 60))
  }
}


/**
 * Berechnet die verbleibende Zeit bis zur nächsten Zielzeit.
 * @param {number} targetHour - Die Zielstunde (0-23).
 * @param {number} targetMinute - Die Zielminute (0-59).
 * @returns {number} - Verbleibende Zeit in Millisekunden.
 */
function getTimeUntil(targetHour, targetMinute) {
  const now = new Date();
  let targetTime = new Date();

  targetTime.setHours(targetHour, targetMinute, 0, 0);

  // Wenn die Zielzeit bereits vergangen ist, setze sie auf den nächsten Tag
  if (now > targetTime) {
    targetTime.setDate(targetTime.getDate() + 1);
  }

  return targetTime - now;
}

/**
 * Setzt einen Timeout für den Seitenreload zur Zielzeit.
 * @param {number} hour - Die Zielstunde (0-23).
 * @param {number} minute - Die Zielminute (0-59).
 */
function scheduleReload(hour, minute) {
  const timeout = getTimeUntil(hour, minute);
  console.log(`Seitenreload in ${timeout / 1000 / 60} Minuten.`);

  setTimeout(function () {
    //console.log(`Seite wird um ${hour}:${minute < 10 ? '0' + minute : minute} neu geladen.`);
    location.reload();

    // Optional: Nach dem Reload erneut planen
    scheduleReload(hour, minute);
  }, timeout);
}


function loadSoundCloudPodcasts() {
  $(".soundcloud_podcast").each(function (index) {
    const elem = $(this);
    elem.css("padding", "0", "background-image", "none");
    elem.html('<iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/' + elem.data("link") + '&color=%2390b728&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe>');

  })
}


/*

    <div id="soundcloud_podcasts_{data.uid}" class="soundcloud_podcast">
        <div class="d-none d-lg-block">
            Zum Aktivieren der Podcasts klicken Sie bitte <a href="javascript:loadSoundCloudPodcasts();">» den Link</a>. Wir möchten Sie darauf hinweisen, dass mit der Aktivierung Daten an Soundcloud übermittelt werden. Weitere Informationen finden Sie in unserer Datenschutzerklärung.
        </div>
        <div class="d-block d-lg-none">
            <a href="javascript:loadSoundCloudPodcasts();">Zum Aktivieren der Podcasts klicken Sie bitte hier</a>. Bitte beachten Sie, dass mit der Aktivierung Daten an Soundcloud übermittelt werden. Weitere Informationen finden Sie in unserer Datenschutzerklärung.
        </div>
    </div>

*/


function loadPodigeePodcasts() {
  $("[data-podigee]").each(function (index) {
    const elem = $(this);
    elem.css("padding", "0", "background-image", "none");
    elem.html('<script class="podigee-podcast-player" src="https://player.podigee-cdn.net/podcast-player/javascripts/podigee-podcast-player.js" data-configuration="' + elem.data("link") + '"></script>');

  })
}


/*

    <div id="podigee_podcasts_{data.uid}" class="podigee_podcast">
        <div class="d-none d-lg-block">
            Zum Aktivieren der Podcasts klicken Sie bitte <a href="javascript:loadPodigeePodcasts();">» den Link</a>. Wir möchten Sie darauf hinweisen, dass mit der Aktivierung Daten an Podigee übermittelt werden. Weitere Informationen finden Sie in unserer Datenschutzerklärung.
        </div>
        <div class="d-block d-lg-none">
            <a href="javascript:loadPodigeePodcasts();">Zum Aktivieren der Podcasts klicken Sie bitte hier</a>. Bitte beachten Sie, dass mit der Aktivierung Daten an Podigee übermittelt werden. Weitere Informationen finden Sie in unserer Datenschutzerklärung.
        </div>
    </div>


*/


class wxPlaylist {

  /*
   * id = unique id of the player
   * next = autoplay next media after one ended
   * loop = start at 0 after the last media
   * mode (group) = loop through the active playlist
   * mode (full)  = loop through all playlists
   *
   */

  constructor(id, next, loop, mode) {
    this.playerId = id;
    this.player = $("#" + id);
    this.videoPlayer = this.player.find("video");
    this.activePlaylistIndex = 0;
    this.activePlaylistItemIndex = 0;
    this.playlist = [];
    this.next = next;
    this.loop = loop;
    this.mode = mode;


    if (this.player.length == 0) {
      console.log("Player not found: " + this.playerId);
      return false;
    }

    this.playlists = $(".playlist[data-player=" + this.playerId + "]");

    if (this.playlists.length == 0) {
      console.log("Playlist not found for player: " + this.playerId);
      console.log("... hiding player")
      this.player.hide();
      return false;
    }

    this.init();
  }

  init() {
    var _self = this;

    _self.videoPlayer.on('loadedmetadata', function () {
      _self.getPlaylistItemByIndex(_self.activePlaylistIndex, _self.activePlaylistItemIndex).find(".duration").text(_self.calcVideoTime(_self.videoPlayer[0].duration));
    });

    _self.videoPlayer.on('timeupdate', function () {
      var duration = _self.videoPlayer[0].duration;
      var currentTime = _self.videoPlayer[0].currentTime;
      var countdown = duration - currentTime;
      var percentage = 100 * currentTime / duration; //in %

      _self.getPlaylistItemByIndex(_self.activePlaylistIndex, _self.activePlaylistItemIndex).find(".duration").text(_self.calcVideoTime(countdown));
      _self.getPlaylistItemByIndex(_self.activePlaylistIndex, _self.activePlaylistItemIndex).find(".timelinebar").css('width', percentage + '%');
    });

    if (_self.next) {
      _self.videoPlayer.on('ended', function () {
        _self.nextVideo();
      });
    }

    _self.buildPlaylist();

    const videoelem = _self.getPlaylistItemByIndex(0, 0);
    _self.changeVideo(_self.getVideoFromPlaylistItem(videoelem), _self.getTitleFromPlaylistItem(videoelem));
    _self.videoPlayer[0].pause();
  }

  buildPlaylist() {
    var _self = this;
    this.playlists.each(function () {
      var playlist = $(this);
      var playlistItems = playlist.find(".playlist-item");

      var playlist_array = [];
      var playlistname = $(this).attr("class").replace(" ", ".");

      var duplicate = _self.getPlaylistByName(playlistname);
      if (duplicate.length > 0) return false;

      playlist_array["name"] = playlistname;
      playlist_array["media"] = [];

      playlistItems.each(function () {
        var playlistitem = $("." + $(this).attr("class").replace(" ", "."));

        playlistitem.attr("data-playlist", _self.playlist.length);
        playlistitem.attr("data-playlist-item", playlist_array["media"].length);

        playlist_array["media"].push(playlistitem);

        playlistitem.on("click", ".info.enabled", function (e) {
          e.preventDefault();
          $(this).toggleClass("active");
          if ($(this).hasClass("active")) {
            playlistitem.find(".description").slideDown().toggleClass("open");
          } else {
            playlistitem.find(".description").slideUp().toggleClass("open");
          }
        });


        playlistitem.on("click", ".playbutton", function (e) {
          e.preventDefault();
          _self.scrollToPlayer();
          _self.activePlaylistIndex = playlistitem.data("playlist");
          _self.activePlaylistItemIndex = playlistitem.data("playlist-item");

          _self.changeVideo(_self.getVideoFromPlaylistItem(playlistitem), _self.getTitleFromPlaylistItem(playlistitem));
        });


      });

      _self.playlist.push(playlist_array);

    });

  }

  handlerVideoClick(e, elem) {

    this.activePlaylistItemIndex = this.playlistItems.indexOf(elem);
    this.changeVideo(this.getVideoFromPlaylistItem(elem), this.getTitleFromPlaylistItem(elem));
  }

  getVideoFromPlaylistItem(elem) {
    var video = elem.data("video");
    return video
  }

  getTitleFromPlaylistItem(elem) {
    var title = elem.data("title");
    return title
  }

  getPlaylistByName(name) {
    return jQuery.grep(this.playlist, function (playlist) {
      return playlist.name == name
    });
  }

  getPlaylistItemByIndex(playlist, item) {
    return this.playlist[playlist].media[item];
  }

  changeVideo(video, title) {
    var extension = video.substr(video.lastIndexOf('.') + 1);

    this.videoPlayer.find("source").remove();
    this.videoPlayer.attr("title", title);
    this.videoPlayer.append('<source src="' + video + '" type="video/' + extension + '">')
    this.videoPlayer[0].load();
    this.videoPlayer[0].play();
  }

  nextVideo() {

    if (this.activePlaylistItemIndex < this.playlist[this.activePlaylistIndex].media.length - 1) {
      this.activePlaylistItemIndex++;
    } else {
      if (this.mode == "groups") {
        if (this.loop) this.activePlaylistItemIndex = 0;
        else return false;
      } else if (this.mode == "full") {
        if (this.activePlaylistIndex < this.playlist.length - 1) {
          this.activePlaylistIndex++;
          this.activePlaylistItemIndex = 0;
        } else {
          if (this.loop) {
            this.activePlaylistIndex = 0;
            this.activePlaylistItemIndex = 0;
          } else {
            return false;
          }
        }
      }
    }
    /*
            if(this.activePlaylistItemIndex < this.playlistItems.length - 1){
                this.activePlaylistItemIndex = this.activePlaylistItemIndex + 1;
            }else{
                this.activePlaylistItemIndex = 0;
            }
    */
    const videoelem = this.getPlaylistItemByIndex(this.activePlaylistIndex, this.activePlaylistItemIndex)
    const nextvideoSrc = this.getVideoFromPlaylistItem(videoelem);
    const nextvideoTitle = this.getTitleFromPlaylistItem(videoelem);
    this.changeVideo(nextvideoSrc, nextvideoTitle);
  }

  calcVideoTime(input) {
    var minutes = parseInt(input / 60, 10);
    var seconds = parseInt(input % 60);
    return minutes + ":" + (seconds >= 10 ? seconds : "0" + seconds);
  }

  scrollToPlayer() {
    var calculatedOffset = this.player.offset().top - $('header').height() - 30;
    $('html, body').animate({ scrollTop: calculatedOffset }, 1000);
  }
}

/*
 * Terminauswahl auf 3 einzelfelder verteilen um in Antwortmail und -Text die Parameter verwenden zu können.
 * Funktioniert nur, wenn die 4 Felder/Marker entsprechend benannt sind:
 *  - terminauswahl
 *  - termin_date
 *  - termin_time
 *  - termin_location
 */
$(document).ready(function () {
  if ($("[id^=powermail_field_termin]").length == 4) {
    $("form button").on("click", function () {
      value = $("#powermail_field_terminauswahl option:checked").text().split(" - ");
      $("#powermail_field_termin_date").val(value[0]);
      $("#powermail_field_termin_time").val(value[1]);
      $("#powermail_field_termin_location").val(value[2]);
    });
  }
});


/*
 * Playbutton für Videocontainer
 */
$(document).ready(function () {
  $(".videoplayer").on("click", function () {
    id = $(this).attr("id").replace("videoplay_", "");
    // only find video container in same DOM parent element
    $(this).parent().find("#video_" + id).get(0).play();
    $(this).removeClass("active");
  });
});


/*
 * AOK Arztsuche
 */
$(document).ready(function () {
  $(".aok_arztsuche").on("change, input", ".form-control", function () {
    var link = "https://www.aok.de/pk/cl/uni/medizin-versorgung/arztsuche/suche/?radius=10000&initial_search=true";
    var parent = $(this).closest(".aok_arztsuche");
    var search_val = parent.find(".aok_arztsuche_search").val()
    var location_val = parent.find(".aok_arztsuche_location").val()
    var a = parent.find("a")

    if (search_val != "")
      link += "&medical_specialist_group_FAG" + search_val + "=true";

    if (location_val != "")
      link += "&location=" + location_val;

    a.attr("href", link);
  });
});


/**
 * ADHS Kampagne
 */
document.addEventListener('DOMContentLoaded', function () {
    const slider = $('.slick-slider');
    const indexContainer = $('.slick-index-container');
    const dotsContainer = $('.slick-dots-container');
    const prevArrow = $('.slick-prev');
    const nextArrow = $('.slick-next');

    if (slider.length) {
        slider.slick({
            dots: !!dotsContainer.length,
            arrows: !!prevArrow.length && !!nextArrow.length,
            appendDots: dotsContainer.length ? dotsContainer : null,
            prevArrow: prevArrow.length ? prevArrow : null,
            nextArrow: nextArrow.length ? nextArrow : null,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            fade: true,
            autoplaySpeed: 5000,
        });

        function updateIndex(currentSlide, totalSlides) {
            if (indexContainer.length) {
                const formattedCurrent = String(currentSlide + 1).padStart(2, '0');
                const formattedTotal = String(totalSlides).padStart(2, '0');
                indexContainer.text(`${formattedCurrent}/${formattedTotal}`);
            }
        }

        updateIndex(0, slider.slick('getSlick').slideCount);

        slider.on('afterChange', function (event, slick, currentSlide) {
            updateIndex(currentSlide, slick.slideCount);

            // Videos pausieren und nur im aktiven Slide abspielen
            slider.find('video').each(function () {
                this.pause();
            });

            slider.find('.slick-active video').each(function () {
                this.play();
            });
        });

        // Initial alle Videos pausieren und das aktive Video abspielen
        slider.on('init', function () {
            slider.find('video').each(function () {
                this.pause();
            });

            slider.find('.slick-active video').each(function () {
                this.play();
            });
        });

        // Trigger Slick 'init' Event, da es erst nach der Initialisierung greifen muss
        slider.slick('refresh');
    }

    function handleResponsiveChanges() {
        if (window.innerWidth <= 768) {
            dotsContainer.hide();
            indexContainer.show();
        } else {
            dotsContainer.show();
            indexContainer.hide();
        }
    }

    if (dotsContainer.length && indexContainer.length) {
        handleResponsiveChanges();
        window.addEventListener('resize', handleResponsiveChanges);
    }
});


function shareToClipboard() {
  // Den aktuellen Link ermitteln:
  var currentUrl = window.location.href;
  // Ein temporäres Input-Feld erstellen:
  var dummy = document.createElement("input");
  document.body.appendChild(dummy);
  dummy.value = currentUrl;
  dummy.select();
  // Den Kopiervorgang ausführen:
  document.execCommand("copy");
  document.body.removeChild(dummy);


  showOverlayMessage("Link in die Zwischenablage kopiert!");
 }

function showOverlayMessage(text) {
  // Erstelle ein Overlay-DIV und style es
  var $overlay = $('<div id="overlayMessage"></div>');
  $overlay.css({
    position: 'fixed',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    backgroundColor: 'rgba(0, 0, 0, 0.7)',
    color: '#fff',
    padding: '20px 40px',
    borderRadius: '5px',
    fontSize: '16px',
    textAlign: 'center',
    zIndex: '9999',
    display: 'none'
  });

  // Setze den übergebenen Text als Inhalt
  $overlay.text(text);

  // Füge das Overlay zum Body hinzu
  $('body').append($overlay);

  // Blend das Overlay ein, warte kurz, und blende es wieder aus (mit Callback zum Entfernen)
  $overlay.fadeIn(500).delay(2000).fadeOut(500, function(){
    $(this).remove();
  });
}
