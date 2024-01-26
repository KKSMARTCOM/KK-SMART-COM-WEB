function syncPosition(slide){
        $owl2 = $('.team_slider2');
        $owl3 = $('.team_slider3');
        $owl2.trigger('to.owl.carousel', slide+1);
        $owl3.trigger('to.owl.carousel', slide+2);
    }
    if ($('.team_slider').length>0){
        $(document).ready(function(){
            var $owl_team = $('.team_slider');

            $owl_team.children().each( function( index ) {
              $(this).attr( 'data-position', index ); 
            });
            $owl_team.owlCarousel({
              center: false,
              loop: true,
              rewind:true,
              autoplay:false,
              autoplayHoverPause:true,
                nav: true,
                dots:false,
                pagination : false,
                responsiveClass:true,
                animateIn: 'fadeIn', 
                animateOut: 'fadeOut', 
                navText: ['<img src="'+window.location.origin+'/wp-content/themes/phenomenon/img/nav_left.svg">', '<img src="'+window.location.origin+'/wp-content/themes/phenomenon/img/nav_right.svg">'],

                items:1,
                touchDrag  : false,
                mouseDrag  : false,
                

            })
            
            $owl_team.on('changed.owl.carousel',function(property){
                syncPosition(property.item.index ?? 0);
            })
        });
    }
    if ($('.team_slider2').length>0){
        $(document).ready(function(){
            var $owl2 = $('.team_slider2');

            $owl2.children().each( function( index ) {
              $(this).attr( 'data-position', index ); 
            });

            $owl2.owlCarousel({
              center: false,
              loop: true,
              rewind:true,
              autoplay:false,
              autoplayHoverPause:true,
                nav: false,
                dots:false,
                pagination : false,
                responsiveClass:true,
                animateIn: 'fadeIn', 
                animateOut: 'fadeOut', 
                items:1,
                touchDrag  : false,
                mouseDrag  : false,

            });
        });
    }
    if ($('.team_slider3').length>0){
        $(document).ready(function(){
            var $owl3 = $('.team_slider3');

            $owl3.children().each( function( index ) {
              $(this).attr( 'data-position', index ); 
            });

            $owl3.owlCarousel({
              center: false,
              loop: true,
              rewind:true,
              autoplay:false,
              autoplayHoverPause:true,
                nav: false,
                dots:false,
                pagination : false,
                responsiveClass:true,
                animateIn: 'fadeIn', 
                animateOut: 'fadeOut', 
                items:1,
                touchDrag  : false,
                mouseDrag  : false,

            });
        });
    }

    $(document).ready(function(){
        syncPosition(0);
    })

    function rev_slider(reinit=false){
        padd=($(window).width() - 1300) / 2;
        if (padd<20){
            padd=20;
        }
            var $owl = $('.reviews_slider');
            if (reinit==true){
                $owl.owlCarousel('destroy'); 
            }

            var $owl = $('.reviews_slider');
            $owl.children().each( function( index ) {
              $(this).attr( 'data-position', index );
            });

            $owl.owlCarousel({
              center: false,
              loop: false,
              stagePadding: padd,
                nav: false,
                dots:false,
                pagination : false,
                responsiveClass:true,
                items:2,
                margin:38,
                responsive:{
                    300:{
                        items:1,
                        autoHeight:true,
                        center:false,
                        margin:15,
                        navText: ['<img src="'+window.location.origin+'/wp-content/themes/phenomenon/img/nav_left.svg">', '<img src="'+window.location.origin+'/wp-content/themes/phenomenon/img/nav_right.svg">'],
                        nav:true,
                    },
                  768:{
                        items:2,
                    },

                    1110:{
                        items:2,
                    }
                }

            });

    }

    if ($('.reviews_slider').length>0){
        $(document).ready(function(){
            rev_slider();
        });
        $(window).on('resize',function(){
            rev_slider(true);
        })
    }
    if ($('.stages-slider').length>0){
        $(document).ready(function(){
            var $owl = $('.stages-slider');

            $owl.children().each( function( index ) {
              $(this).attr( 'data-position', index ); 
            });

            $owl.owlCarousel({
              center: false,
              loop: false,
              autoplay:false,
                nav: true,
                dots:false,
                pagination : false,
                responsiveClass:true,
                items:1,
                margin:40,
                navText: ['<img src="'+window.location.origin+'/wp-content/themes/phenomenon/img/nav_left.svg">', '<img src="'+window.location.origin+'/wp-content/themes/phenomenon/img/nav_right.svg">'],
                smartSpeed:1000,

            });
        });
        $('.stages-slider').on('initialized.owl.carousel changed.owl.carousel', function(e) {
            if (!e.namespace)  {
              return;
            }
            var carousel = e.relatedTarget;
            $('.slider-counter').html("0"+(carousel.relative(carousel.current()) +1) + "<b> / " + ("0"+carousel.items().length+"</b>"));
          })
    }

    if ($('.top-cases-slider').length>0){
        $(document).ready(function(){
            var $owl = $('.top-cases-slider');

            $owl.children().each( function( index ) {
              $(this).attr( 'data-position', index ); 
            });

            $owl.owlCarousel({
              center: false,
              loop: false,
              autoplay:false,
                nav: true,
                dots:false,
                pagination : false,
                responsiveClass:true,
                items:1,
                margin:40,
                navText: ['<img src="'+window.location.origin+'/wp-content/themes/phenomenon/img/nav_left.svg">', '<img src="'+window.location.origin+'/wp-content/themes/phenomenon/img/nav_right.svg">'],
                smartSpeed:1000,
                animateIn: 'fadeIn',
                animateOut: 'fadeOut',

            });
        });
        $('.top-cases-slider').on('initialized.owl.carousel changed.owl.carousel', function(e) {
            if (!e.namespace)  {
              return;
            }
            var carousel = e.relatedTarget;
            $('.slider-counter').html("0"+(carousel.relative(carousel.current()) +1) + "<b> / " + ("0"+carousel.items().length+"</b>"));
          });
    }

    if ($('.cases-carousel-wrap').length>0){
        $(document).ready(function(){
            const swiper = new Swiper('.swiper.cases-carousel-wrap', {
              // Optional parameters
              loop: false,
              spaceBetween: 32,
              slidesPerView: 1.099,

              // Navigation arrows
              navigation: {
                nextEl: '.next',
                prevEl: '.prev',
              },
              breakpoints: {
                    992: {
                      slidesPerView: 2.635,
                      spaceBetween: 40,
                    },
                },
            });
        })

    }

    function setStageTitle(ind,sl,currentSlide){
    
        $('.top-head').html(((ind<10) ? ('0'+ind) : (ind))+'/'+((sl<10) ? ('0'+sl) : (sl))+'. '+$(currentSlide).attr('data-heading'))
    }

    if ($('.swiper-stage-slider').length>0){
        $(document).ready(function(){
            var sl=$('.swiper-stage-slider .col').length;
            const swiper2 = new Swiper('.swiper.swiper-stage-slider', {
              // Optional parameters
              loop: false,
              spaceBetween: 37,
              slidesPerView: 1.1,
              on: {
                slideChange: function () {
                  const ind = swiper2.realIndex;
                  const currentSlide = swiper2.slides[ind]
                  setStageTitle($(currentSlide).attr('data-slide'),$(currentSlide).attr('data-slides'),currentSlide);
                },
                init: function () {
                  setStageTitle(1,$('.swiper-stage-slider .col:eq(0)').attr('data-slides'),$('.swiper-stage-slider .col:eq(0)')[0]);
                },
              },
            });
        })

    }

    if ($('.swipe-card').length>0){
        $('.swipe-card .top').click(function(){
            index=$(this).parent().index()
            parent=$(this).parent()
            $('.swipe-cards').removeClass('ind-0 ind-1 ind-2 ind-3')
            $('.swipe-cards').addClass('ind-'+index);
            btm=$(this).parent().find('.bottom')
            $('.swipe-card').removeClass('active')
            $(this).parent().addClass('active')
            $('.swipe-card .top').not(this).slideDown(200)
            $('.swipe-card .top').not(this).parent().find('.bottom').slideUp(200)
            $(this).slideUp(200)
            btm.slideDown(200)
            setTimeout(function(){
                $('html, body').animate({
                    scrollTop: parent.offset().top-70
                }, 500);
            },200)
        })
    }


function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(element.attr('data-text')).select();
    document.execCommand("copy");
    $temp.remove();
}

$(document).on('click','.copyme',function(){
    copyToClipboard($(this));
    return false;
})

    setTimeout(function(){
        getInstPosts();
    },1000);




