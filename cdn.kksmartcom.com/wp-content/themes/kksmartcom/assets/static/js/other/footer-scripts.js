if (el('.stages-slider').length > 0) {
  const stageSwiper = new Swiper('.stages-slider', {
    // Optional parameters
    loop: false,
    spaceBetween: 40,
    slidesPerView: 1,
    speed: 1000,

    // Navigation arrows
    navigation: {
      nextEl: '.next',
      prevEl: '.prev',
    },
    on: {
      slideChange: function () {
        var ind = stageSwiper.realIndex;
        var currentSlide = stageSwiper.slides[ind]
        el('.slider-counter').html("0" + (ind + 1) + "<b> / " + ("0" + el('.stages-slider .swiper-slide').length + "</b>"));
      },
      init: function () {
        el('.slider-counter').html("01<b> / " + ("0" + el('.stages-slider .swiper-slide').length + "</b>"));

      },
    },
  });
}

if (el('.top-cases-slider').length > 0) {
  //$(document).ready(function(){
  const topSwiper = new Swiper('.top-cases-slider', {
    // Optional parameters
    loop: false,
    spaceBetween: 40,
    slidesPerView: 1,
    effect: 'fade',
    speed: 1000,

    // Navigation arrows
    navigation: {
      nextEl: '.next',
      prevEl: '.prev',
    },
    on: {
      slideChange: function () {
        var ind = topSwiper.realIndex;
        var currentSlide = topSwiper.slides[ind]
        el('.slider-counter').html("0" + (ind + 1) + "<b> / " + ("0" + el('.top-cases-slider .swiper-slide').length + "</b>"));
      },
      init: function () {
        el('.slider-counter').html("01<b> / " + ("0" + el('.top-cases-slider .swiper-slide').length + "</b>"));

      },
    },
  });
  //});
}

if (el('.cases-carousel-wrap').length > 0) {
  //window.onload = function(){
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
  //}

}
var revSwiper;
function rev_slider(reinit = false) {
  padd = (window.innerWidth - 1300) / 2;
  if (padd < 20) {
    padd = 20;
  }
  if (reinit == true) {
    revSwiper.destroy(true, true);
  }
  el('.reviews_slider')[0].style.paddingLeft = padd + 'px';
  el('.reviews_slider')[0].style.paddingRight = padd + 'px';
  revSwiper = new Swiper('.reviews_slider', {
    // Optional parameters
    loop: false,
    spaceBetween: 15,
    slidesPerView: 1,
    centeredSlides: false,
    autoHeight: true,
    // Navigation arrows
    navigation: {
      nextEl: '.next',
      prevEl: '.prev',
    },
    breakpoints: {
      992: {
        slidesPerView: 2,
        spaceBetween: 38,
        centeredSlides: false,
        autoHeight: false,
      },
    },
  });




}
if (el('.reviews_slider').length > 0) {
  //document.addEventListener('DOMContentLoaded', function(){
  rev_slider();
  window.addEventListener('resize', function () {
    //console.log(event)
    rev_slider(true);
  })
  //})

}


function setStageTitle(ind, sl, currentSlide, indx) {

  el('.top-head[data-slider="' + indx + '"]').html(((ind < 10) ? ('0' + ind) : (ind)) + '/' + ((sl < 10) ? ('0' + sl) : (sl)) + '. ' + [currentSlide].attr('data-heading'))
}

if (el('.swiper-stage-slider').length > 0) {
  //window.onload = function(){
  el('.swiper-stage-slider.swiper').each(function (selector) {
    var indx = selector.attr('data-slider')
    var sl = selector.findIn('.col').length;
    var swiper2 = new Swiper('.swiper.swiper-stage-slider[data-slider="' + indx + '"]', {
      // Optional parameters
      loop: false,
      spaceBetween: 37,
      slidesPerView: 1.1,
      on: {
        slideChange: function () {
          var ind = swiper2.realIndex;
          var currentSlide = swiper2.slides[ind]
          setStageTitle([currentSlide].attr('data-slide'), [currentSlide].attr('data-slides'), currentSlide, indx);
        },
        init: function () {
          setStageTitle(1, el('.swiper-stage-slider .col:nth-child(1)').attr('data-slides'), el('.swiper-stage-slider .col:nth-child(1)')[0], indx);
        },
      },
    });
  });
  // }

}

event('.stages-tabs-togglers button', 'click', function (selector) {
  ind = index(selector);
  el('.stages-tabs-togglers button:not(:nth-child(' + ind + '))').removeClass('active')
  el('.stages-tabs-togglers button:not(:nth-child(' + ind + '))').addClass('empty')
  selector.addClass('active');
  selector.removeClass('empty');
  el('.stages-tab').fadeOut(0);
  el('.stages-tab-' + ind).fadeIn(200);
  btmLineHeight();
})

function btmLineHeight() {
  if (el('.col .btm-line').length > 0) {
    el('.bottom-content .col:nth-of-type(odd)').each(function (selector) {
      if (selector.findIn('.btm-line').length > 0) {
        h = selector.findIn('.btm-line')[0].scrollHeight;
        h2 = h;
        if (selector.next().length > 0) {
          if (selector.next().findIn('.btm-line').length > 0) {
            h2 = selector.next().findIn('.btm-line')[0].scrollHeight
            if (h2 > h) {
              h = h2;
            }
            selector.next().findIn('.btm-line')[0].style.height = h + 'px';
          }
        }
        selector.findIn('.btm-line')[0].style.height = h + 'px';
        //console.log(selector.findIn('.btm-line')[0].scrollHeight)
      }
    })
  }
}
if (el('.col .btm-line').length > 0) {
  btmLineHeight();
  window.addEventListener('resize', function () {
    btmLineHeight();
  })
}

if (el('.swipe-card').length > 0) {
  event('.swipe-card .top', 'click', function (selector) {
    ind = index(selector.parent())
    parent = selector.parent()
    el('.stages-tab').visible().findIn('.swipe-cards').removeClass('ind-0')
    el('.stages-tab').visible().findIn('.swipe-cards').removeClass('ind-1')
    el('.stages-tab').visible().findIn('.swipe-cards').removeClass('ind-2')
    el('.stages-tab').visible().findIn('.swipe-cards').removeClass('ind-3')
    el('.stages-tab').visible().findIn('.swipe-cards').addClass('ind-' + (ind - 1));
    btm = selector.parent().findIn('.bottom')
    el('.stages-tab').visible().findIn('.swipe-card').removeClass('active')
    selector.parent().addClass('active')
    el('.stages-tab').visible().findIn('.swipe-card:not(:nth-child(' + ind + ')) .top').slideDown(200)
    el('.stages-tab').visible().findIn('.swipe-card:not(:nth-child(' + ind + ')) .bottom').slideUp(200)
    selector.slideUp(200)
    btm.slideDown(200)
    btmLineHeight();
    setTimeout(function () {
      // $('html, body').animate({
      //     scrollTop: parent.offset().top-70
      // }, 500);
      scrollTo(parent, 500, 70);
    }, 250)


  })
}







