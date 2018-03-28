// ------------------------------------------------------------------------
// Check if element is in ViewPort
// ------------------------------------------------------------------------
(function($) {
    "use strict";
    $.belowthefold = function(element, settings) {
        var fold = $(window).height() + $(window).scrollTop();
        return fold <= $(element).offset().top - settings.threshold;
    };
    $.abovethetop = function(element, settings) {
        var top = $(window).scrollTop();
        return top >= $(element).offset().top + $(element).height() - settings.threshold;
    };
    $.rightofscreen = function(element, settings) {
        var fold = $(window).width() + $(window).scrollLeft();
        return fold <= $(element).offset().left - settings.threshold;
    };
    $.leftofscreen = function(element, settings) {
        var left = $(window).scrollLeft();
        return left >= $(element).offset().left + $(element).width() - settings.threshold;
    };
    $.inviewport = function(element, settings) {
        return !$.rightofscreen(element, settings) && !$.leftofscreen(element, settings) && !$.belowthefold(element, settings) && !$.abovethetop(element, settings);
    };
    $.extend($.expr[':'], {
        "below-the-fold": function(a, i, m) {
            return $.belowthefold(a, {
                threshold: 0
            });
        },
        "above-the-top": function(a, i, m) {
            return $.abovethetop(a, {
                threshold: 0
            });
        },
        "left-of-screen": function(a, i, m) {
            return $.leftofscreen(a, {
                threshold: 0
            });
        },
        "right-of-screen": function(a, i, m) {
            return $.rightofscreen(a, {
                threshold: 0
            });
        },
        "in-viewport": function(a, i, m) {
            return $.inviewport(a, {
                threshold: 0
            });
        }
    });
    // ------------------------------------------------------------------------
    // On window load
    // ------------------------------------------------------------------------
      $(window).load(function() {
        if ($('#preloader').length > 0) {
          $('#preloader').fadeOut('slow', function() {
              $(this).remove();
          });
        }

        if ($('.portfolio-sidebar.sidebar-list').length > 0) {
            var $sidebar = $(".portfolio-sidebar.sidebar-list"),
            $window = $(window),
            $listHeight = $(".portfolio-gallery-list").height(),
            offset = $sidebar.offset(),
            topPadding = 0;
            $sidebar.css('width',$sidebar.width());
            $window.scroll(function() {
                if ($window.scrollTop() > offset.top - 170) {
                    $sidebar.stop().addClass('fixed-sidebar');
                    if ($window.scrollTop() > offset.top + $listHeight - $sidebar.height() - 150 )  {
                           $sidebar.addClass('absolute-sidebar');
                    }
                    else {
                        $sidebar.removeClass('absolute-sidebar');
                    }

                }  else
                {
                    $sidebar.stop().removeClass('fixed-sidebar');
                }
            });
        }


    });

    $(document).ready(function() {

        if ($('#single-page, .page-template-portfolio .kd-portfolio-page, body.error404').length > 0) {
            $('body').addClass('single-post');
         }

        // ------------------------------------------------------------------------
        // Live Preview Customizer
        // ------------------------------------------------------------------------

        $("#customizer .switcher").click(function() {
        $("#customizer").toggleClass('active');
        });

        function draw() {
            requestAnimationFrame(draw);
            scrollEvent();
        }
        draw();

        // ------------------------------------------------------------------------
        // Fixed Footer
        // ------------------------------------------------------------------------
        if ($("#footer.fixed").length) {
            var footerHeight = $("#footer.fixed").height();
            $("#wrapper").css("margin-bottom", footerHeight);
        }

        // ------------------------------------------------------------------------
        // Custom Search Field
        // ------------------------------------------------------------------------
        $("#s").each(function(index, elem) {
            var eId = $(elem).attr("id");
            var label = null;
            if (eId && (label = $(elem).parents("form").find("label[for=" + eId + "]")).length == 1) {
                $(elem).attr("placeholder", 'Search');
                $(label).remove();
            }
        });
        $("#searchsubmit").val('');
        $(".woocommerce-product-search input[type='submit']").val('');
        // ------------------------------------------------------------------------
        // Contact Form Buttons
        // ------------------------------------------------------------------------
        $(document).on("click", ".section .wpcf7-not-valid-tip,.section .wpcf7-mail-sent-ok, .subscribe-form header .wpcf7-response-output, .modal-content-inner .wpcf7-not-valid-tip", function() {
            $(this).fadeOut();
        });
        // ------------------------------------------------------------------------
        // Reset reCaptcha for modal window
        // ------------------------------------------------------------------------
        if ($('.g-recaptcha-explicit').length > 0) {
          $(document).on("click", ".modal-menu-item", function() {
              grecaptcha.reset($('.g-recaptcha-explicit-id', this).data('grecaptcha_id'));
          });
        }
        // ------------------------------------------------------------------------
        // Main Menu One Page Links
        // ------------------------------------------------------------------------
        $('.navbar-nav > li.one-page-link > a:first-child, .footer_widget .menu li.one-page-link > a:first-child').each(function() {
            var href = $(this).attr("href");
            var link = href.replace(/\/$/, '');
            if ($("body").hasClass("home")) {
                var anchor = link.substr(link.lastIndexOf('/') + 1);
                if (anchor.indexOf("?page_id") >= 0) { anchor = '';}
                $(this).attr("href", "#" + anchor);
            } else {
                if (!$(this).hasClass("first")) {
                    var pos = link.lastIndexOf('/');
                    link = link.substring(0, pos) + '#' + link.substring(pos + 1);
                    $(this).attr("href", link);
                }
            }
        });

        $( "<span class='mobile-dropdown'></span>" ).appendTo( $( ".navbar-nav .menu-item-has-children" ) );

        $(".navbar-nav .menu-item-has-children .mobile-dropdown").click(function() {
        $(this).closest(".menu-item-has-children").toggleClass('mobile-visible');
        });

        // ------------------------------------------------------------------------
        // Main Smooth Scroll and Scroll Spy
        // ------------------------------------------------------------------------
        $('.navbar-collapse ul li a, .navbar-collapse .modal-menu-item.scroll-section').click(function() {
            $('.navbar-toggle:visible').click();
        });
        $(function() {
            $('.navbar-nav .one-page-link a, .footer_widget .menu li a, .scroll-section, .demo-button').bind('click', function(event) {
                if ($("body").hasClass("home")) {
                var $anchor = $(this);
                $('html, body').stop().animate({
                    scrollTop: $($anchor.attr('href')).offset().top - 100
                }, 1500, 'easeInOutExpo');
                event.preventDefault();
                }
            });
        });
        $(function() {
            $('.portfolio-demo-button').bind('click', function(event) {
                var $anchor = $(this);
                $('html, body').stop().animate({
                    scrollTop: $($anchor.attr('href')).offset().top - 250
                }, 1500, 'easeInOutExpo');
                event.preventDefault();
            });
        });
        $('body').scrollspy({
            offset: 100,
            target: '.navbar-fixed-top'
        });

        // ------------------------------------------------------------------------
        // WPGlobus compatibility - NavWalker conflict
        // ------------------------------------------------------------------------

        if ($('.wpglobus-selector-link').length > 0) {
            $('.wpglobus-selector-link .dropdown-menu').addClass('sub-menu');
        }

        // ------------------------------------------------------------------------
        // Slider scroll down button
        // ------------------------------------------------------------------------
        $('.slider-scroll-down a').click(function() {
            $('html, body').animate({
                scrollTop: $("#wrapper .section").offset().top - 68
            }, 1500, 'easeInOutExpo');
            event.preventDefault();
        });

        // ------------------------------------------------------------------------
        // Back to top button
        // ------------------------------------------------------------------------
        $(window).bind("scroll", function() {
            if ($(window).scrollTop() > $(window).height()) {
                $('.back-to-top').addClass('active');
            }
            if ($(window).scrollTop() < $(window).height()) {
                $('.back-to-top').removeClass('active');
            }
        });
        $('.back-to-top').click(function(event) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 500);
            return false;
        });

    });
    // ------------------------------------------------------------------------
    // Portfolio image slider
    // ------------------------------------------------------------------------

    if ($(".owlslider-portfolio").length) {
        $(".owlslider-portfolio").owlCarousel({
            navigation: false,
            pagination: true,
            singleItem: true,
            autoPlay: false,
            animateOut: 'fadeOut',
        });
    }

    // ------------------------------------------------------------------------
    // Portfolio photoSwipe init
    // ------------------------------------------------------------------------
    var PhotoSwipe = window.PhotoSwipe,
        PhotoSwipeUI_Default = window.PhotoSwipeUI_Default;

    $('body').on('click', 'a[data-size]', function(e) {
        if (!PhotoSwipe || !PhotoSwipeUI_Default) {
            return;
        }

        e.preventDefault();
        openPhotoSwipe(this);
    });

    var parseThumbnailElements = function(gallery, el) {
        var elements = $(gallery).find('a[data-size]').has('img'),
            galleryItems = [],
            index;

        elements.each(function(i) {
            var $el = $(this),
                size = $el.data('size').split('x'),
                caption;

            if ($el.next().is('.wp-caption-text')) {
                // image with caption
                caption = $el.next().text();
            } else if ($el.parent().next().is('.wp-caption-text')) {
                // gallery icon with caption
                caption = $el.parent().next().text();
            } else {
                caption = $el.attr('title');
            }

            galleryItems.push({
                src: $el.attr('href'),
                w: parseInt(size[0], 10),
                h: parseInt(size[1], 10),
                title: caption,
                msrc: $el.find('img').attr('src'),
                el: $el
            });
            if (el === $el.get(0)) {
                index = i;
            }
        });

        return [galleryItems, parseInt(index, 10)];
    };

    var openPhotoSwipe = function(element, disableAnimation) {
        var pswpElement = $('.pswp').get(0),
            galleryElement = $(element).parents('.gallery, .hentry, .main, body').first(),
            gallery,
            options,
            items, index;

        items = parseThumbnailElements(galleryElement, element);
        index = items[1];
        items = items[0];

        options = {
            index: index,
            getThumbBoundsFn: function(index) {
                var image = items[index].el.find('img'),
                    offset = image.offset();

                return {
                    x: offset.left,
                    y: offset.top,
                    w: image.width()
                };
            },
            showHideOpacity: true,
            history: false
        };

        if (disableAnimation) {
            options.showAnimationDuration = 0;
        }

        // Pass data to PhotoSwipe and initialize it
        gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.init();
    };


    // ------------------------------------------------------------------------
    // Classie Script
    // ------------------------------------------------------------------------
    (function(window) {

        function classReg(className) {
            return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
        }
        var hasClass, addClass, removeClass;
        if ('classList' in document.documentElement) {
            hasClass = function(elem, c) {
                return elem.classList.contains(c);
            };
            addClass = function(elem, c) {
                elem.classList.add(c);
            };
            removeClass = function(elem, c) {
                elem.classList.remove(c);
            };
        } else {
            hasClass = function(elem, c) {
                return classReg(c).test(elem.className);
            };
            addClass = function(elem, c) {
                if (!hasClass(elem, c)) {
                    elem.className = elem.className + ' ' + c;
                }
            };
            removeClass = function(elem, c) {
                elem.className = elem.className.replace(classReg(c), ' ');
            };
        }

        function toggleClass(elem, c) {
            var fn = hasClass(elem, c) ? removeClass : addClass;
            fn(elem, c);
        }
        var classie = {
            hasClass: hasClass,
            addClass: addClass,
            removeClass: removeClass,
            toggleClass: toggleClass,
            has: hasClass,
            add: addClass,
            remove: removeClass,
            toggle: toggleClass
        };
        if (typeof define === 'function' && define.amd) {
            define(classie);
        } else {
            window.classie = classie;
        }

    })(window);
    // ------------------------------------------------------------------------
    // Animated Header
    // ------------------------------------------------------------------------

    var cbpAnimatedHeader = (function() {
        var docElem = document.documentElement,
            header = document.querySelector('.navbar-default'),
            didScroll = false,
            changeHeaderOn = 50;

        function init() {
            window.addEventListener('scroll', function(event) {
                if (!didScroll) {
                    didScroll = true;
                    setTimeout(scrollPage, 100);
                }
            }, false);
            window.addEventListener('load', function(event) {
                if (!didScroll) {
                    didScroll = true;
                    setTimeout(scrollPage, 100);
                }
            }, false);
        }

        function scrollPage() {
            var sy = scrollY();
            if (sy >= changeHeaderOn) {
                classie.add(header, 'navbar-shrink');
            } else {
                classie.remove(header, 'navbar-shrink');
            }
            didScroll = false;
        }

        function scrollY() {
            return window.pageYOffset || docElem.scrollTop;
        }
        init();
    })();


})(jQuery);



function scrollEvent() {

    if (!is_touch_device()) {
        viewportTop = jQuery(window).scrollTop();

        if (jQuery(window).width())

            jQuery('.parallax-overlay').each(function() {
            elementOffset = jQuery(this).offset().top;
            distance = -(elementOffset - viewportTop) * 0.35;
            jQuery(this).css('transform', 'translate3d(0, ' + distance + 'px,0)');
        });

    }
}

function is_touch_device() {
    return 'ontouchstart' in window || 'onmsgesturechange' in window;
}
