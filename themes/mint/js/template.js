(function () {
  "use strict";
  var isMobileWidth = false,
      animateSpeed = 250,
      menuVisible = false;

  function checkMobileWidth(){
    isMobileWidth = ($(window).width() <= 768);
  }


  function setHeaderHeight() {
    setTimeout(function(){
      var pageHeaderHeight = $('.page-header').outerHeight();
      $('.header-content').css('height',pageHeaderHeight + 'px');
    },200);
  }

  // function setCtaHeight() {
  //   setTimeout(function(){
  //     var catHeight = $('.img-wrapper img').outerHeight();
  //     $('#call-to-actions').css('height',catHeight + 'px');
  //   },200);
  // }

  function setCtaheightNew(){
    var imageToCheck=$('.img-wrapper img');
    $(imageToCheck).load(function(){
      var catHeight=$(imageToCheck).outerHeight();
      $('#call-to-actions').css('height',catHeight + 'px');
    });
  }

  function setHomeCtaHeight() {
    setTimeout(function(){
      var catHeight = $('.img-wrapper img').outerHeight();
      if(isMobileWidth) {
        $('.home #call-to-actions').css('height', '560px');
      } else {
        $('.home #call-to-actions').css('height',catHeight + 'px');
      }
    },200);
  }

  function menuItems(){
    // Fade in menu, one li at a time
    var currentDelay = 0;
    $('.menu').find('.menu-item').each(function(i){
      // currentDelay = 150 + (i * 125);

      currentDelay = 150;
      var self = $(this);
      setTimeout(
          (function(s) {
            return function() {
              s.addClass('loaded');
            }
          })(self), currentDelay);
    });
  }

  function toggleMainMenu(){
    // Fade in menu
    $("#main-menu-wrapper").fadeToggle();

    menuVisible = !menuVisible;
    // If menu is being shown
    if (menuVisible){
      // Slide down background
      $("#menu-background").fadeIn(animateSpeed * 2, function(){
        // Then fade in menu box
        $("#main-menu-container").fadeIn(animateSpeed, function(){
          // Lazy load menu items
          menuItems();
        });
      });
    } else {
      // Fade out menu
      $("#main-menu li").fadeTo(animateSpeed, 0, function(){
        // Fade out menu box
        $("#main-menu-container").fadeOut(animateSpeed, function(){
          // Slide up background
          $("#menu-background").fadeOut(animateSpeed);
        });
      });
    }
  }

  // Menu actions
  function menu() {
    // var scrollHeight = $(window).scrollTop();
    $("#nav-trigger").on({
      click:function(){
        $("#navigation").toggleClass('open');
        // $("body").toggleClass('fixed');
        $(this).toggleClass('active inactive');
        toggleMainMenu();
      }
    });

    // Remove 'loaded' class when closing menu to reset loading animation.
    $("#nav-trigger").on({
      click:function(){
        if($(this).hasClass('inactive')){
          $("#navigation .menu-item").removeClass('loaded');
        }
      }
    });
  }

  function jumpLink(){
    $('.jump').on({
      click:function(e){
        e.preventDefault();
        $('html, body').animate({
          scrollTop: $( $.attr(this, 'href') ).offset().top
        }, 750);
      }
    });
  }

  function slideoutTriggerHeight() {
    var containerHeight = $("#slideout .slideout-inner-wrap").outerHeight(),
        trigger = $("#slideout .button-text-wrap"),
        text = $("#slideout .button-text-wrap .button").width();

        // console.log(text);

    // Set height, depeding on which is taller.
    if(containerHeight >= text) {
      trigger.css('height',(containerHeight - 4) + 'px');
    } else if (containerHeight < text) {
      trigger.css('height',(text - 4) + 'px');
    }
  }

  function slideout(){
    var trigger = $("#slideout .button-wrap"),
        slideout = $("#slideout"),
        container = $("#slideout .slideout-inner-wrap"),
        containerHeight = $("#slideout .slideout-inner-wrap").outerHeight(),
        text = $("#slideout .button-text-wrap .button").width();

      // Set height, depeding on which is taller.
      if(containerHeight >= text) {
        container.css('height',(containerHeight + 30) + 'px');
      } else if (containerHeight < text) {
        container.css('height',(text + 30) + 'px');
      }  

    trigger.on({
      click:function(){
        slideout.toggleClass("active");
        $('#slideout span').toggleClass("icon-special-trigger icon-close"); // Swap icon
      }
    });

    $("#slideout .icon-close").on({
      click:function(){
        slideout.toggleClass("active");
      }
    });
  }

  // Responsive images
  function resizeImages(){
    // Responsive background image
    $('.responsive-bg').each(function(){
      var desktop = $(this).attr('data-d'),
          tablet = $(this).attr('data-t'),
          mobile = $(this).attr('data-m');
      if($(window).width() < 767) {
        $(this).attr('style', 'background-image: url("'+ mobile +'")');
      } else if($(window).width() < 1023) {
        $(this).attr('style', 'background-image: url("'+ tablet +'")');
      } else {
        $(this).attr('style', 'background-image: url("'+ desktop +'")');
      }
    });

    // Responsive Image
    $('.responsive-img').each(function(){
      var desktop = $(this).attr('data-d'),
          tablet = $(this).attr('data-t'),
          mobile = $(this).attr('data-m');
      if($(window).width() < 767) {
        $(this).attr('src', mobile );
      } else if($(window).width() < 1023) {
        $(this).attr('src', tablet );
      } else {
        $(this).attr('src', desktop );
      }
    });
  }

  function list_tricks() {
    // Fade in the list items.
    $('.list-content ul li').addClass('fade-in');

    // Make Parent LI have an extra class.
    $('.list-content li').each(function(){   
      var parent = $(this).parent().parent();
      if(parent.is('li')){
        parent.addClass('bold');
      } 
    });
  }

  function highlight_radio() {
    $('.frm_opt_container label').on({
      click:function(){
        $('.frm_opt_container label').removeClass('clicked');
        $(this).addClass('clicked');
      }
    });
  }

  function animateHeader() {
    var header = $('#header'),
        navTrigger = $('#nav-trigger'),
        scroll = $(document).scrollTop();
    if(scroll > 0){
      header.addClass('scroll');
      navTrigger.addClass('scroll');
    } else if(scroll < 25) {
      header.removeClass('scroll');
      navTrigger.removeClass('scroll');
    }
  }

  function lazyLoad(){
    var fadeIn = $('.fade-in:in-viewport');
    $(fadeIn).each(function() {
      var trigger = $(this);
      setTimeout(function(){
        $(trigger).addClass('loaded');
      },50);
    });
  }

  function setScheduleATourLinks(){
      $('.schedule-a-tour-button').on(
          "click",function(){
              $("#schedule-tour-modal").show();
          }
      );
  }

  // Do stuff on document ready
  $(document).ready(function(){
    // Initiate goodies.
    
    list_tricks();
    jumpLink();
    slideout();
    menu();
    checkMobileWidth();
    resizeImages();
    highlight_radio();

    // Set some heights
    setHeaderHeight();
    // setCtaHeight();
    setHomeCtaHeight();
    slideoutTriggerHeight();
    if($(window).width() <= 768){
      setCtaheightNew();
    }
    setScheduleATourLinks();
  });

  // Do stuff on Window resize
  $(window).on({
    resize:function(){
      checkMobileWidth();
      resizeImages();

      // Update some heights;
      setHeaderHeight();
      // setCtaHeight();
      setHomeCtaHeight();
      slideoutTriggerHeight();
      if($(window).width() <= 768){
        setCtaheightNew();
      }
    },

    // Do stuff on Window scroll
    scroll:function(){
      animateHeader();
      lazyLoad();
    }
  })

  $(window).load(function(){
    if($(window).height() > $(window).scrollTop()){
      setTimeout(function(){
        lazyLoad();
      },140);
    }
  });

}());

// Do stuff after contact form validation.
function frmThemeOverride_frmAfterSubmit(formReturned, pageOrder, errObj, object){
    if(typeof(formReturned) == 'undefined'){
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: 'formSubmissionSuccess',
            formId: 'contactForm'
        });
    }
}