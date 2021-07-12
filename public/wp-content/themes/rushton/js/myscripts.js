/*--------------------------------------------------------------
THE FUNCTIONS
--------------------------------------------------------------*/
/**
 * @desc checks if current element exists
 * @param none
 * @return int - the number of elements present, > 0 = element exists
 */
jQuery.fn.exists = function () {
  return this.length > 0;
};

/**
 * @desc Adds browser class to html
 * @param none
 * @return none
 */
function addBrowserClass() {
  var chrome = $.browser.mozilla();
  var firefox = $.browser.firefox();
  var safari = $.browser.safari();

  if (chrome) {
    $("html").addClass("chrome");
  }
  if (firefox) {
    $("html").addClass("firefox");
  }
  if (safari) {
    $("html").addClass("safari");
  }
}

function mobileMenu() {
  var menucontain = $(".mobile-menu-container").height();
  var headerheight = $(".mobile-menu").height();
  $(".mobile-menu-container").css("top", (menucontain + headerheight) * -1);
  $(".mobile-menu-btn").click(function () {
    $(this).toggleClass("active");
    if ($(this).hasClass("active"))
      $(".mobile-menu-container").css("top", $(".mobile-menu").height());
    else
      $(".mobile-menu-container").css("top", (menucontain + headerheight) * -1);
    $(".back-cover").toggleClass("active");
  });
}

function OG() {
  //fix for form focus bug on iphones
  if (
    "iPad" == navigator.platform ||
    "iPhone" == navigator.platform ||
    "iPod" == navigator.platform ||
    "Linux armv6l" == navigator.platform
  ) {
    var $body = jQuery("body");
    $(document)
      .on("focus", "input", function () {
        $body.addClass("fixfixed");
      })
      .on("blur", "input", function () {
        $body.removeClass("fixfixed");
      });
  }
}

//clicking this element will cause the screen to animate the size of the screen
function scrollClick(toClick) {
  var pageHeight = $(window).height();
  $(toClick).click(function () {
    $("html, body").animate({ scrollTop: pageHeight + "px" }, 1000);
  });
}

// This function gets the element, and the parent element and roughly centers it in the middle of the screen. You could probbaly change the -5 to be something else, or even improve this?
function centerElement(target, parent, yBreakpoint, headerOffset) {
  if ($(window).outerWidth() > yBreakpoint) {
    var yTarget = $(target).height();
    var yParent = $(parent).height();
    var offset = 0;
    if (headerOffset) {
      if (Foundation.MediaQuery.atLeast("large")) {
        offset = $(".desktop-menu").height();
      } else {
        offset = $(".mobile-menu").height();
      }
    }
    $(target).css(
      "marginTop",
      (yParent - offset) / 2 - yTarget / 2 + offset + "px"
    );
  }
  $(window).resize(function () {
    if ($(window).outerWidth() > yBreakpoint) {
      var yTarget = $(target).height();
      var yParent = $(parent).height();
      var offset = 0;
      if (headerOffset) {
        if (Foundation.MediaQuery.atLeast("large")) {
          offset = $(".desktop-menu").height();
        } else {
          offset = $(".mobile-menu").height();
        }
      }
      $(target).css(
        "marginTop",
        (yParent - offset) / 2 - yTarget / 2 + offset + "px"
      );
    } else {
      $(target).css("marginTop", "");
    }
  });
}

function fullHeight(theElement, headerOffset, footerOffset, mobile, prop) {
  if ((Foundation.MediaQuery.atLeast("large") && !mobile) || mobile) {
    var pgHeight = $(window).height();
    var offset = 0;
    if (headerOffset) {
      if (Foundation.MediaQuery.atLeast("large"))
        offset += $(".desktop-menu").height();
      else offset += $(".mobile-menu .menu-bar").height();
    }
    if (footerOffset) offset += $("footer").height();
    $(theElement).css(prop, pgHeight - offset);
  }
  $(window).resize(function () {
    if ((Foundation.MediaQuery.atLeast("large") && !mobile) || mobile) {
      var pgHeight = $(window).height();
      var offset = 0;
      if (headerOffset) {
        if (Foundation.MediaQuery.atLeast("large"))
          offset += $(".desktop-menu").height();
        else offset += $(".mobile-menu .menu-bar").height();
      }
      if (footerOffset) offset += $("footer").height();
      $(theElement).css(prop, pgHeight - offset);
    } else {
      $(theElement).css(prop, "");
    }
  });
}

// back to top button
function toTop() {
  if ($(window).scrollTop() > 200) {
    $("#to-top").addClass("show");
    $("#to-top").css("z-index", 99);
  }
  $(window).scroll(function () {
    if ($(window).scrollTop() > 200) {
      $("#to-top").addClass("show");
      $("#to-top").css("z-index", 99);
    } else {
      $("#to-top").removeClass("show");
      setTimeout(function () {
        if ($(window).scrollTop() <= 200) $("#to-top").css("z-index", -1);
      }, 500);
    }
  });
}

// used for scroll buttons, addoffset if you want to compensate for top navigation
function scrollToSection(target, dest, speed, addOffset) {
  $(target).click(function () {
    var offset = 0;
    if (addOffset) {
      if (Foundation.MediaQuery.atLeast("large"))
        offset = $(".desktop-menu").height();
      else offset = $(".mobile-nav-menu").height();
    }
    $("html,body").animate(
      {
        scrollTop: $(dest).offset().top - offset,
      },
      speed
    );
  });
}

// used to cover up page while it is loading
function fadeBigCover() {
  setTimeout(function () {
    $("#bigcover").addClass("fade-out");
  }, 100);
  setTimeout(function () {
    $("#bigcover .loader").addClass("fade-out");
  }, 100);
  setTimeout(function () {
    $("#bigcover").css("display", "none");
  }, 1200);
}

// subtle vertical shift of background images on targetted elements when scrolling desktop view (for background images)
function parallaxScrollBG(target, initPos) {
  if (Foundation.MediaQuery.atLeast("large")) {
    var scrolled = $(window).scrollTop(); // Scroll position from top
    var offsetFromTop = $(target).offset().top;
    var scrolledAbout =
      initPos - (parseInt(scrolled) - parseInt(offsetFromTop)) / 25;
    $(target).css("background-position", "50% " + scrolledAbout + "%");
    if ($(window).outerWidth() > 0) {
      $(window).scroll(function () {
        scrolled = $(window).scrollTop(); // Scroll position from top
        offsetFromTop = $(target).offset().top;
        scrolledAbout =
          initPos - (parseInt(scrolled) - parseInt(offsetFromTop)) / 25;
        $(target).css("background-position", "50% " + scrolledAbout + "%");
      });
    }
  }
}

// subtle vertical shift of background images on targetted elements when scrolling desktop view
function parallaxScroll(target, initPos, divider) {
  if (Foundation.MediaQuery.atLeast("large")) {
    var scrolled;
    var offsetFromTop;
    var scrolledAbout;
    if ($(window).outerWidth() > 0) {
      scrolled = $(window).scrollTop(); // Scroll position from top
      offsetFromTop = $(target).offset().top;
      scrolledAbout =
        initPos - (parseInt(scrolled) - parseInt(offsetFromTop)) / divider;
      $(target).css("top", initPos + scrolledAbout + "px");
    }
    $(window).scroll(function () {
      if ($(window).outerWidth() > 0) {
        scrolled = $(window).scrollTop(); // Scroll position from top
        offsetFromTop = $(target).offset().top;
        scrolledAbout =
          initPos - (parseInt(scrolled) - parseInt(offsetFromTop)) / divider;
        $(target).css("top", initPos + scrolledAbout + "px");
      } else {
        $(target).css("top", "");
      }
    });
  }
}
// adds transition class for contents of fullpage.js content
function fpSlideTran(index, slideEnter) {
  if (Foundation.MediaQuery.atLeast("large")) {
    if (slideEnter) {
      $(".sect-" + index + " .tran").removeClass("start");
    } else {
      $(".sect-" + index + " .tran").addClass("start");
    }
  }
}

// disable fullpage.js locked scroll on certain breakpoints
function fpResponsive(xBreakpoint, yBreakpoint) {
  $(window).resize(function () {
    if (
      $(window).outerWidth() > xBreakpoint &&
      $(window).height() > yBreakpoint
    ) {
      $.fn.fullpage.setResponsive(false);
      $(".section").removeClass("height-auto");
      $(".fp-tableCell").removeClass("height-full");
    } else if (
      $(window).outerWidth() <= xBreakpoint ||
      $(window).height() <= yBreakpoint
    ) {
      $.fn.fullpage.setResponsive(true);
      $(".section").addClass("height-auto");
      $(".fp-tableCell").addClass("height-full");
    }
  });
  $(window).trigger("resize");
}

function fpFooterSect() {
  $(".footer-sect .fp-tableCell").height(
    $(".footer-sect .fp-tableCell").height() - $("footer").height()
  );
  $(window).resize(function () {
    $(".footer-sect .fp-tableCell").height(
      $(".footer-sect .fp-tableCell").height() - $("footer").height()
    );
  });
}

// hide border for reCAPTCHA container
function reCaptchaValid() {
  $(".g-recaptcha > div").removeClass("is-invalid-captcha");
}

// custom validation for checkboxes
function checkboxValidation(elem) {
  if ($(elem).hasClass("check-required")) {
    $("#submit").click(function () {
      if ($(elem).find("input:checked").length === 0) {
        $(elem).find("label input").attr("required", true);
        $(elem).find("label").addClass("is-invalid-label");
      }
    });
    $(elem)
      .find("label")
      .click(function () {
        $(elem).find("label").removeClass("is-invalid-label");
        $(elem).find("label input").removeAttr("required");
      });
  }
}

function clearRadioLabel(elem) {
  $(elem)
    .find("label")
    .click(function () {
      $(elem).find("label").removeClass("is-invalid-label");
      $(elem).find("label input").removeAttr("required");
    });
}

function condText(type, elem, ext, val) {
  $(elem).addClass("closed");
  $(elem).find(".cond-holder").addClass("squish");
  // RADIO ||
  if (type === "radio") {
    $(elem).find(".cond-holder").prop("disabled", true);
    $(elem)
      .find("fieldset > label")
      .click(function () {
        if ($(this).find("input").hasClass("show-textfield")) {
          $(elem).find(".cond-holder").removeClass("squish");
          $(elem).find(".cond-holder").prop("disabled", false);
          if ($(elem).find(".cond-holder").hasClass("reqfield")) {
            $(elem).find(".cond-holder").attr("required", true);
          }
        } else {
          condClose($(elem));
        }
      });
    // SELECT ||
  } else if (type === "select") {
    $(elem).find(".cond-holder").css("overflow", "hidden");
    $(ext).change(function () {
      if ($(ext).val() === val) {
        $(elem).find(".cond-holder").css("overflow", "");
        $(elem).removeClass("closed");
        // $(elem).height($(elem).find("input").height());
        $(elem).find(".cond-holder").removeClass("squish");
        $(elem).find(".cond-holder").prop("disabled", false);
        if ($(elem).find(".cond-holder").hasClass("reqfield")) {
          $(elem).find("input").attr("required", true);
        }
      } else {
        condClose($(elem));
        // $(elem).css("height", 0);
        if ($(elem).find(".cond-holder").hasClass("reqfield")) {
          $(elem).find("input").attr("required", false);
        }
        setTimeout(function () {
          $(elem).find(".cond-holder").css("overflow", "hidden");
        }, 250);
      }
    });
  }
}

function condClose(elem) {
  console.log(elem);
  $(elem).find(".cond-holder").val("");
  $(elem).addClass("closed");
  $(elem).find(".cond-holder").addClass("squish");
  $(elem).find(".cond-holder").prop("disabled", true);
  if ($(elem).find(".cond-holder").hasClass("reqfield")) {
    $(elem).find(".cond-holder").removeAttr("required");
    $(elem).find(".cond-holder").removeAttr("data-invalid");
    $(elem).find(".cond-holder").removeClass("is-invalid-input");
  }
}

function disableSubmitButton(target) {
  if ($(target).hasClass("auto-disable")) {
    $(target).prop("disabled", true);
    setTimeout(function () {
      $(target).prop("disabled", false);
    }, 5000);
  }
}

function mobileClick(elem, time) {
  $(elem).click(function () {
    if (!Foundation.MediaQuery.atLeast("large")) {
      $(elem).addClass("mobile-click");
      setTimeout(function () {
        $(elem).removeClass("mobile-click");
      }, time);
    }
  });
}

function desktopHover(elem) {
  if (Foundation.MediaQuery.atLeast("large")) {
    $(elem).addClass("hover");
  } else {
    $(elem).removeClass("hover");
  }
  $(window).resize(function () {
    if (Foundation.MediaQuery.atLeast("large")) {
      $(elem).addClass("hover");
    } else {
      $(elem).removeClass("hover");
    }
  });
}

function serialAnimate(target, intialDelay, sequenceDelay) {
  if (Foundation.MediaQuery.atLeast("xsmall")) {
    if (!$(target).hasClass("animate")) {
      if ($(target).isInCenterOfScreen()) {
        var delay = intialDelay;
        $(target).each(function () {
          serialAnimator($(this), delay);
          delay += sequenceDelay;
        });
      }
      $(window).scroll(function () {
        if ($(target).isInCenterOfScreen()) {
          var delay = intialDelay;
          $(target).each(function () {
            serialAnimator($(this), delay);
            delay += sequenceDelay;
          });
        }
      });
    }
  }
}

function serialAnimator(target, delay) {
  setTimeout(function () {
    $(target).addClass("animate");
  }, delay);
}

function delayRedirect(target) {
  if (target !== undefined && $(target).text() !== "Map Data") {
    if (
      !$(target).attr("href").includes("mailto:") &&
      !$(target).attr("href").includes("tel:") &&
      !$(target).attr("target")
    ) {
      $(target).click(function (e) {
        e.preventDefault();
        $("#bigcover").css("top", 0);
        $("#bigcover").removeClass("fade-out");
        setTimeout(function () {
          window.location.href = $(target).attr("href");
        }, 500);
      });
    }
  }
}

function categories() {
  $(".categories a").each(function () {
    $(this).addClass("text-link black");
    if (Foundation.MediaQuery.atLeast("large")) {
      $(this).addClass("hover");
    }
    if ($(this).text() === $(".header-row h1").text()) {
      $(this).addClass("active");
    }
  });
}

function clearCheckboxes(target) {
  $(target).click(function () {
    $(this).parent().find(".is-invalid-label").removeClass("is-invalid-label");
  });
}

function changeSelectState(target) {
  $(target).change(function () {
    if ($(target).find(":selected").val() === "") {
      $(target).addClass("ph");
    } else {
      $(target).removeClass("ph");
    }
  });
}

function customPlaceholder(target) {
  $(target)
    .find(".placeholder")
    .click(function () {
      $(target).find("input").focus();
      $(target).addClass("test");
      console.log($(target).attr("class"));
    });
  if ($(target).has("input")) {
    $(target)
      .find("input")
      .keyup(function () {
        if ($(this).val() !== "") {
          $(target).find(".placeholder").addClass("vis");
        } else {
          $(target).find(".placeholder").removeClass("vis");
        }
      });
    $(target)
      .find("input")
      .change(function () {
        if ($(this).val() !== "") {
          $(target).find(".placeholder").addClass("vis");
        } else {
          $(target).find(".placeholder").removeClass("vis");
        }
      });
  }
  if ($(target).has("select")) {
    $(target)
      .find("select")
      .change(function () {
        if ($(this).val() !== "") {
          $(target).find(".placeholder").addClass("vis");
        } else {
          $(target).find(".placeholder").removeClass("vis");
        }
      });
  }
}

function animatePlaceholder() {
  $(".form-field").each(function () {
    var target = $(this);
    if (
      $(target).find(".placeholder").length > 0 &&
      !$(target).hasClass("checkfield") &&
      !$(target).hasClass("radiofield")
    ) {
      customPlaceholder(target);
    }
  });
}

document.addEventListener("touchstart", function () {}, true);
//Submenu
function submenu() {
  // Cache selectors
  var lastId,
    topMenu = $("#sub-menu"),
    topMenuHeight = topMenu.outerHeight() + 1,
    // All list items
    menuItems = topMenu.find("a"),
    // Anchors corresponding to menu items
    scrollItems = menuItems.map(function () {
      var item = $($(this).attr("href"));
      if (item.length) {
        return item;
      }
    });

  menuItems.click(function (e) {
    var href = $(this).attr("href"),
      offsetTop = href === "#" ? 0 : $(href).offset().top - 96;
    $("html, body").stop().animate(
      {
        scrollTop: offsetTop,
      },
      850
    );
    e.preventDefault();
  });

  // Bind to scroll
  $(window).scroll(function () {
    // Get container scroll position
    var fromTop = $(this).scrollTop() + 100;

    // Get id of current scroll item
    var cur = scrollItems.map(function () {
      if ($(this).offset().top < fromTop) return this;
    });
    // Get the id of the current element
    cur = cur[cur.length - 1];
    var id = cur && cur.length ? cur[0].id : "";

    if (lastId !== id) {
      lastId = id;
      // Set/remove active class
      menuItems
        .parent()
        .removeClass("active")
        .end()
        .filter("[href=#" + id + "]")
        .parent()
        .addClass("active");
    }
  });
}

//Load More Posts
var page_cat = 1;

function load_more_posts_cat(cat) {
  page_cat++;
  $.ajax({
    url: ajaxpagination.ajaxurl,
    type: "post",
    data: {
      action: "more_posts_cat",
      page: page_cat,
      cat: cat,
    },
    success: function (result) {
      var $data = $(result);
      if ($data.length) {
        $(".results .post-row").append($data);
        $(".load-more-cat").attr("disabled", false);
      } else {
        $(".load-more-cat").attr("disabled", true);
      }
    },
  });
  return false;
}

$(".load-more-cat").on("click", function () {
  // When btn is pressed.
  $(this).attr("disabled", true); // Disable the button, temp.
  var cat = $(this).data("category");
  load_more_posts_cat(cat);
});

/*--------------------------------------------------------------
WINDOW RESIZE
--------------------------------------------------------------*/

/*--------------------------------------------------------------
DOCUMENT READY
--------------------------------------------------------------*/
$(document).ready(function () {
  addBrowserClass();
  OG();
  // disables hover states on mobile (must have :hover state inside .hover class in you SASS)
  // toggles .mobile-click class for mobile elements that would normally have hover states on desktop

  // --- BACK TO TOP --- //
  /* toTop();
  scrollToSection($('#to-top'), $('html, body'), 1000, false); */
  scrollToSection($(".in-the-loop"), $(".row-form-right"), 1000, false);

  // --- fullpage.js --- //
  if ($("#fullpage").length > 0) {
    var sectCount = 0;
    var sectAnchors = [];
    $("#fullpage > .section").each(function () {
      sectCount++;
      sectAnchors.push($(this).attr("sect"));
    });
    $("#fullpage").fullpage({
      menu: "#menu",
      anchors: sectAnchors,
      navigation: true,
      slidesNavigation: true,
      css3: true,
      navigationPosition: "left",
      afterLoad: function (anchorLink, index) {
        $(".sect-" + index + " .tran").addClass("initial");
        fpSlideTran(index, true);
      },
      onLeave: function (index, nextIndex, direction) {
        fpSlideTran(index, false);
        if (nextIndex === sectCount) {
          $(".next-sect").fadeOut();
        }
        if (nextIndex !== sectCount) {
          $(".next-sect").fadeIn();
        }
        // add active class on next index, use css tranny delay for effects
      },
      afterRender: function () {
        inter = setInterval(function () {
          $.fn.fullpage.moveSlideRight();
        }, 5000);
      },
      onSlideLeave: function (
        anchorLink,
        index,
        slideIndex,
        direction,
        nextSlideIndex
      ) {
        clearInterval(inter);
        inter = setInterval(function () {
          $.fn.fullpage.moveSlideRight();
        }, 5000);
      },
    });
    $(".slide").each(function () {
      if (!$(this).hasClass("initial")) {
        $(this).addClass("start");
      }
    });
    fpResponsive(0, 900);

    $(".next-sect").click(function () {
      $.fn.fullpage.moveSectionDown();
    });
  }
  if ($("body").hasClass("page-home")) {
  } else if ($("body").hasClass("page-slick")) {
  } else if ($("body").hasClass("page-signup")) {
    // customValidation();
  }
  // show placeholder text on all devices and browsers
  $("input, textarea").placeholder();
  categories();
});

/*--------------------------------------------------------------
AFTER DOCUMENT LOADS
--------------------------------------------------------------*/
$(window).load(function () {
  mobileMenu();
  $(".text-link").each(function () {
    mobileClick($(this), 500);
  });
  // ----------|  Animate in View |---------- //
  function check_if_in_view() {
    var e = $window.height(),
      t = $window.scrollTop(),
      n = t + e;
    $.each($animation_elements, function () {
      var e = $(this),
        o = e.outerHeight(),
        a = e.offset().top + 40;
      a + o >= t && a <= n && e.addClass("in-view");
    });
  }

  var $animation_elements = $(".animate"),
    $window = $(window);
  $window.on("scroll resize", check_if_in_view),
    $window.trigger("scroll"),
    jQuery.extend(jQuery.easing, {
      easeInOutExpo: function (e, t, n, o, a) {
        return 0 == t
          ? n
          : t == a
          ? n + o
          : (t /= a / 2) < 1
          ? (o / 2) * Math.pow(2, 10 * (t - 1)) + n
          : (o / 2) * (2 - Math.pow(2, -10 * --t)) + n;
      },
    });

  //  $('.prim-nav a').addClass('text-link black');
  desktopHover(".text-link");
  if ($("#wrapper").length > 0) {
    //    fullHeight($('#wrapper'), false, true, true, 'min-height');
  }
  if ($("body").hasClass("page-home")) {
  } else if ($("body").hasClass("page-fullpage")) {
    // --- fullpage --- //
    setTimeout(function () {
      $(".in.cent").each(function () {
        // fpFooterSect();
        centerElement($(this), $(this).parent(), 0, false);
      });
    }, 100);
  } else if ($("body").hasClass("page-slick")) {
    // --- slick --- //
    setTimeout(function () {
      $(".slick-slide").each(function () {
        centerElement($(this).find("h2"), $(this), 0, false);
      });
    }, 50);
  } else if (
    $("body").hasClass("page-thank-you") ||
    $("body").hasClass("error404")
  ) {
    centerElement($(".box"), $("#wrapper"), 0, false);
  }

  // ----------|  SLICK |---------- //
  if ($(".slick-acf").length > 0) {
    var slickCount = 1;
    $(".slick-acf").each(function () {
      var slick = $(this);
      $(this).addClass("slick-count-" + slickCount);
      if ($(slick).attr("speed")) {
        var speed = $(slick).attr("speed");
      }
      $(slick).slick({
        arrows: false,
        infinite: true,
        autoplay: $(slick).hasClass("autoplay") ? true : false,
        autoplaySpeed: speed > 0 ? speed : 5000,
        lazyLoad: "progressive",
        draggable: $(slick).hasClass("draggable") ? true : false,
        fade: $(slick).hasClass("fade") ? true : false,
        dots: $(slick).hasClass("dots") ? true : false,
        slide: ".slick-slide",
        asNavFor:
          ".slick-count-" + slickCount + " + .nav-slider-wrap .nav-slider",
      });
      // arrows
      if ($(slick).hasClass("arrows")) {
        $(slick)
          .find(".slick-button.slick-left")
          .click(function () {
            $(slick).slick("slickPrev");
          });
        $(slick)
          .find(".slick-button.slick-right")
          .click(function () {
            $(slick).slick("slickNext");
          });
      }
      // centered
      if ($(slick).hasClass("centered") && !$(slick).hasClass("captioned")) {
        $(slick)
          .find(".slick-slide")
          .each(function () {
            centerElement($(this).find(".slide-content"), $(slick), 0, false);
          });
      }
      // image position
      var slideHeight = 0;
      if (!$(slick).hasClass("background")) {
        if (!$(slick).hasClass("captioned")) {
          slideHeight = $(slick).height();
        } else {
          slideHeight = $(slick).find(".cap-wrap").height();
        }
        $(slick)
          .find(".slick-slide")
          .each(function () {
            $(this)
              .find("img")
              .css(
                "marginTop",
                slideHeight / 2 - $(this).find("img").height() / 2 + "px"
              );
          });
        $(slick).on("breakpoint", function (event, slick, breakpoint) {
          if (!$(slick).hasClass("captioned")) {
            slideHeight = $(slick).height();
          } else {
            slideHeight = $(slick).find(".cap-wrap").height();
          }
          $(slick)
            .find(".slick-slide")
            .each(function () {
              $(this)
                .find("img")
                .css(
                  "marginTop",
                  slideHeight / 2 - $(this).find("img").height() / 2 + "px"
                );
            });
        });
      }
      // fullheight
      if ($(slick).hasClass("fullheight")) {
        fullHeight($(".slick"), true, false, true, "height");
      }
      slickCount++;
    });
    var slickNavCount = 1;
    $(".slick.nav-slider").each(function () {
      var slickNav = $(this);
      $(slickNav).addClass();
      $(slickNav).slick({
        autoplay: false,
        slidesToShow: 12,
        slidesToScroll: 1,
        centerMode: false,
        focusOnSelect: true,
        arrows: false,
        infinite: true,
        swipe: false,
        asNavFor: ".slick-count-" + slickNavCount,
        slidesToShow: 6,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 5,
            },
          },
          {
            breakpoint: 640,
            settings: {
              slidesToShow: 4,
            },
          },
          {
            breakpoint: 480,
            // settings: 'unslick'
          },
        ],
      });
      slickNavCount++;
    });

    $(".slick-acf").on("lazyLoaded", function (e, slick, image, imageSource) {
      $(image).css("display", "none");
      $(image)
        .parent(".bg")
        .css("background-image", 'url("' + imageSource + '")');
      // only run once
      /*if (!$('.animation-loader').hasClass('hide-loader')) {
        $('.animation-loader').addClass('hide-loader');
      }*/
    });
  }

  // ----------|  ACF FORM CODE |---------- //
  if ($(".acf-form").length > 0) {
    // adds custom checkbox
    $('input[type="checkbox"], input[type="radio"]').iCheck();
    $(".iradio").on("ifToggled", (e) => {
      console.log("test");
      e.target.parentNode.parentNode.classList.toggle("checked");
    });
    $(".select-arrow").attr("disabled", "disabled");
    // reCAPTCHA as a requied form element
    if ($(".g-recaptcha").length == 1) {
      $("#g-recaptcha-response").attr("required", "true");
      var elem = new Foundation.Abide($("form"));

      // reCAPTCHA validation for Foundation 6 forms
      $(document).on("forminvalid.zf.abide", function (ev, frm) {
        if (!$("#g-recaptcha-response").val()) {
          ev.preventDefault();
          $(".recaptchafield").addClass("is-invalid-captcha");
        } else {
          $(".recaptchafield").removeClass("is-invalid-captcha");
        }
      });
    }
    $("#registerform-step2").addClass("shrink");

    $(document).on("submit", "form", function (ev) {
      if ($("#registerform-step1").hasClass("form-two-step")) {
        ev.stopPropagation();
        ev.preventDefault();
        if ($(this).attr("id") == "registerform-step1") {
          var formData = $("#registerform-step1").serialize();
          formData += "&stepnum=1";
          disableSubmitButton($("#step_one_submit"));
          $.ajax({
            type: "POST",
            url: $("#registerform-step1").attr("action"),
            data: formData,
            dataType: "json",
          }).done(function (response) {
            if (response.success) {
              $("#registerform-step2 #dbid").val(response.retmsg);
              $("#registerform-step2 #emailsteptwo").val(
                $("#registerform-step1 #email").val()
              );
              $("#registerform-step1").height(
                $("#registerform-step1").height()
              );
              $("#registerform-step1").addClass("shrink");
              setTimeout(function () {
                $("#registerform-step1").css("display", "none");
                $("#registerform-step2").show();
                $("#registerform-step2 .cond-text").each(function () {
                  condText("radio", $(this));
                  clearRadioLabel($(this));
                });
                $("#registerform-step1").height(
                  $("#registerform-step1").height()
                );
                $("#registerform-step2").removeClass("shrink");
              }, 500);
              $("#registerform-step1").css("height", "");
            } else {
              alert(response.retmsg);
            }
          });
          return true;
        }

        if ($(this).attr("id") == "registerform-step2") {
          var formData = $("#registerform-step2").serialize();
          formData += "&stepnum=2";
          disableSubmitButton($("#submit"));
          $.ajax({
            type: "POST",
            url: $("#registerform-step2").attr("action"),
            data: formData,
            dataType: "json",
          }).done(function (response) {
            if (response.success) {
              if (response.success) {
                if (response.consent === "yes") {
                  $(".acf-success .msg.consent-yes").css("display", "block");
                }

                $(".acf-form").css("display", "none");
                $(".acf-success").css("z-index", "1");
                $(".acf-success").addClass("active");

                $(".acf-success p.response").text(response.retmsg);
              }
              //window.location.href = response.retmsg;
            } else {
              alert(response.retmsg);
            }
          });
          return true;
        }
      }

      if (Array.from(ev.target.classList).includes("is-ajax-form")) {
        ev.preventDefault();

        if ($("#honeypot").length === 1 && $("#honeypot").val() !== "") {
          ev.preventDefault();
        } else {
          var formData = $(ev.target).serialize();
          disableSubmitButton($("#submit"));
          $.ajax({
            type: "POST",
            url: $(ev.target).attr("action"),
            data: formData,
            dataType: "json",
          }).done(function (response) {
            const { formID } = response;
            if (response.success) {
              $(`.acf-success[data-id="${formID}"] .msg.consent-yes`).css(
                "display",
                "block"
              );

              $(`.acf-form[data-id="${formID}"]`).css("display", "none");
              $(`.acf-success[data-id="${formID}"]`).css("z-index", "1");
              $(`.acf-success[data-id="${formID}"]`).addClass("active");
              $(".required").remove();

              $(`.acf-success[data-id="${formID}"] p.response`).text(
                response.retmsg
              );

              /* uncomment and add analytics code to do virtual page view tracking
                gtag('config', 'UA-#########-#', {
                  'page_title' : 'Thank You for Registering',
                  'page_path': '/thank-you/'
                });
                */
              dataLayer.push({
                event: "VirtualPageview",
                virtualPageURL: "/thank-you/",
                virtualPageTitle: "Thank You for Registering",
              });
            } else {
              alert(response.retmsg);
            }
          });
        }
      }
    });

    // Clear iCheckbox errors after being clicked
    $(".check-field").each(function () {
      checkboxValidation($(this));
    });

    // Clear iRadio errors after being clicked
    $(".radio-field").each(function () {
      clearRadioLabel($(this));
    });

    // Clear error border over required checkbox field when clicked
    $(".form-field.check-field, .form-field.single-check").each(function () {
      clearCheckboxes($(this).find("fieldset > label"));
    });

    // Display different style for dropdowns based on whether it has default selection or not
    $(".select-wrap select").addClass("ph");
    $(".select-wrap select").each(function () {
      changeSelectState($(this));
    });

    $(".acf-form").each(function () {
      // Code for conditional radio textfiel
      if (!$(this).is($("#registerform-step2"))) {
        $(this)
          .find(".cond-text")
          .each(function () {
            condText("radio", $(this));
            clearRadioLabel($(this));
          });
      }
      // Code for conditional text fields
      $(this)
        .find(".text-field.conditional")
        .each(function () {
          condText(
            "select",
            $(this),
            "#" + $(this).attr("cond-ext"),
            $(this).attr("cond-val")
          );
        });
    });

    if ($(".acf-form").hasClass("custom-placeholders")) {
      animatePlaceholder();
    }
  }
  fadeBigCover();

  $(".play-button").click(function () {
    $("#video-modal").foundation("open");
    loadVideo();
  });

  $(".sterlinglogo").addClass("active");
  setTimeout(function () {
    setTimeout(function () {
      $(".sterlinglogo").removeClass("small-logo");
      setTimeout(function () {
        $.each($(".grid-images-wrapper > div > div"), function (index, value) {
          $(value).addClass("active");
        });
        setTimeout(function () {
          $(".go-up").addClass("active");
          $(".man-coffee-dog").addClass("active");
        }, 1700);

        setTimeout(function () {
          $(".brush-bg").addClass("active");
          //$('.go-up').removeClass('active');
          //$('.man-coffee-dog').removeClass('active');
          $(".sterlinglogo").addClass("small-logo-mobile");
          $(".sterlinglogo").addClass("final-logo");
          $(".intro-text").addClass("active");
          $(".scroll-indicator").addClass("active");
          $.each(
            $(".grid-images-wrapper > div > div"),
            function (index, value) {
              $(value).addClass("activevideo");
            }
          );
          /*setTimeout(function() {
            $('.play-button').addClass('active');
          }, 800);*/

          setTimeout(function () {
            $(".in-the-loop").addClass("active");
          }, 1000);
        }, 2000);
      }, 1500);
    }, 750);
  }, 500);

  $(".yt-overlay-click").click(function () {
    if (player.getPlayerState() == YT.PlayerState.PLAYING) {
      player.pauseVideo();
    } else {
      player.playVideo();
    }
  });
});

$(document).on("closed.zf.reveal", "[data-reveal]", function () {
  if (playerInit) {
    player.pauseVideo();
  }
});

function loadVideo() {
  if (!playerInit) {
    // Load the IFrame Player API code asynchronously.
    var tag = document.createElement("script");
    tag.src = "https://www.youtube.com/player_api";
    var firstScriptTag = document.getElementsByTagName("script")[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
  } else {
    player.playVideo();
  }
}

$(function () {
  $("#ytplayer").css({
    width: $(window).innerWidth() + "px",
    height: $(window).innerHeight() + "px",
  });

  $(window).resize(function () {
    $("#ytplayer").css({
      width: $(window).innerWidth() + "px",
      height: $(window).innerHeight() + "px",
    });
  });
});

// Replace the 'ytplayer' element with an <iframe> and
// YouTube player after the API code downloads.
var player,
  playerInit = false;
function onYouTubePlayerAPIReady() {
  player = new YT.Player("ytplayer", {
    height: "360",
    width: "640",
    videoId: "l_PjX93-Le8",
    playerVars: {
      rel: 0,
      controls: 0,
      modestbranding: 0,
      showinfo: 0,
    },
    events: {
      onReady: onPlayerReady,
    },
  });
  playerInit = true;
}
function onPlayerReady(event) {
  //player.mute();player.setVolume(0);
  player.playVideo();
}
