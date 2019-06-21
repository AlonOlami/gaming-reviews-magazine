// On Document Load
jQuery(window).load(function(){
    //site loader
    jQuery('#wraploader').hide();
});

// On Document Ready
jQuery(document).ready(function ($) {
    // Full Navigation
    // right menu
    $('#menu-toggle').click(function(){
      $('#site-header-menu').addClass('open').css({'transform':'scale(1)'});
    });

    $('#menu-toggle-close').click(function(){
      if( $('#site-header-menu').hasClass('open') ) {
        $('#site-header-menu').removeClass('open').css({'transform':'scale(0)'});
      }

    });
    $("button#sec-menu-toggle").click(function(){
    $(".nav-buttons").toggle();
    });

    // // left menu
    //     $('#sec-menu-toggle').click(function(){
    //         $( '#sec-site-header-menu' ).addClass('open').css({'transform':'scale(1)'});
    //     });
    //     $('#mobile-menu-toggle-close').click(function(){
    //       if( $('#sec-site-header-menu').hasClass('open') ) {
    //           $( '#sec-site-header-menu' ).removeClass('open').css({'transform':'scale(0)'});
    //         }
    //   });
 /**
 * sub menu script
 */
    $("li.menu-item-has-children > a").each(function(){$(this).append( "<i class='fa fa-angle-down'></i>" );});
    $('li.menu-item-has-children .fa').click(function(e) {
      e.preventDefault();
      $(this).siblings().toggle();
      e.stopPropagation();
    })


    // hoverdir
    jQuery(' #da-thumbs section > li ').each( function() { 
      $(this).hoverdir();
    });
   
    //hide and show search 
         $(".button-search").click(function(){
             $("#top-search").slideToggle("400");
          });
   // add toggle class to search icon
          $(".button-search").click(function () {
           $("i.fa.fa-search").toggleClass("fa-close");   
          });

          //hide and show nav 
         $("button#sec-menu-toggle").click(function(){
             $("div#sec-site-header-menu").slideToggle("1500");
          });


    // slick jQuery 
    jQuery('.carousel-group').slick({
      autoplay: true,
      autoplaySpeed: 3000,
      dots: false,
      slidesToShow: 4,
      slidesToScroll: 1,
      lazyLoad: 'ondemand',
      responsive: [
         {
           breakpoint: 1024,
           settings: {
             slidesToShow: 3,
             slidesToScroll: 3,
             infinite: true,
             dots: false
           }
         },
         {
           breakpoint: 768,
           settings: {
             slidesToShow: 2,
             slidesToScroll: 2
           }
         },
         {
           breakpoint: 481,
           settings: {
             slidesToShow: 1,
             slidesToScroll: 1
           }
         }
         // You can unslick at a given breakpoint now by adding:
         // settings: "unslick"
         // instead of a settings object
       ]
    });

    // back to top animation

    $('#gotop').click(function(){
      $('body').animate({scrollTop: '0px'},1000);
    });

    // header fix
      
      var fixedBackgroundColor       = '#2d2d2d',
          fixedBackgroundTransparent = 'transparent',
          scrollTopPosition          = $('body').scrollTop(),
          selectedHeader             = $('.wrap-nav'),
          containerselectedHeader    = $('.wrap-nav .container'),
          fixedBackgroundNoSlider    = selectedHeader.hasClass('fixed-nav');
         
          var waypoint = new Waypoint({
            element: selectedHeader,
            offset: '0',
            handler: function(direction) {
              if( "down" == direction ){
                containerselectedHeader.css({'maxWidth':'100%', 'paddingLeft': '0', 'paddingRight': '0'});
                selectedHeader.addClass('fixed-nav');                
              } else {
                containerselectedHeader.css({'maxWidth':'1170px', 'paddingLeft': '15px', 'paddingRight': '15px'});
                selectedHeader.removeClass('fixed-nav');    
              }
             
            } 
          });

      // back to top animation

      $('#gotop').click(function(){
        $('body').animate({scrollTop: '0px'},1000);
      });

      $(window).scroll(function() {
        var scrollTopPosition = $('html,body').scrollTop();
        if( scrollTopPosition > 240 ) {
          $('#gotop').css({'bottom': 25});
        } else {
          $('#gotop').css({'bottom': -100});
        }
      });
});