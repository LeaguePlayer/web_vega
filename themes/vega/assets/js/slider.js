$(document).ready(function(){

function sliderMain(){

var slider = $(this);

/* Основные параметры */
var scrollSpeed = 300;
var totalSlides = $('.slider .element').length;


/* Начальный элемент */
function sliderInit(slide) {
  $('.element').eq(slide).fadeIn();
  $('.slider .element table').eq(slide).clone().appendTo('.show_features');
  $('.slider .show_features').show();

}


/* Скролл вперед */
function scrollNext(){

  var currentSlide = $('.slider .element:visible').index();
  var nextSlide;

  if(currentSlide+1==totalSlides) {
    nextSlide = 0;
  } else {
    nextSlide=currentSlide+1;
  }

  $('.slider .show_features').slideUp(scrollSpeed, function(){
      $('.slider .show_features').html('');
      $('.slider .element table').eq(nextSlide).clone().appendTo('.show_features');
      $('.slider .show_features').slideDown(scrollSpeed);
      $('.slider .show_features').fadeIn(scrollSpeed);
      
  });


  $('.slider .element').eq(nextSlide).css({'left': '235px'}, function(){

  });
  $('.slider .element').eq(nextSlide).fadeIn(scrollSpeed);
  $('.slider .element').eq(nextSlide).animate({'left': '0px'}, scrollSpeed);

  $('.slider .element').eq(currentSlide).animate({'left': '-235px'}, scrollSpeed);
  $('.slider .element').eq(currentSlide).fadeOut(scrollSpeed);


  $('.slider2 .slides .slide:hidden').remove();

  $('.slider .element').eq(nextSlide).find('.materials .slide').each(function(){
      $(this).clone().appendTo('.slider2 .slides');
  });

  $('.slider2 .slide').eq(0).css({'left': '0px'});
  $('.slider2 .slide:visible').fadeOut(scrollSpeed);
  $('.slider2 .slide').eq(1).fadeIn(scrollSpeed);

}


/* Скролл назад */
function scrollPrevios(){

  var currentSlide = $('.slider .element:visible').index();
  var previosSlide;

  if(currentSlide<0) {
    previostSlide = totalSlides-1;
  } else {
    previosSlide=currentSlide-1;
  }

  $('.slider .show_features').slideUp(scrollSpeed, function(){
      $('.slider .show_features').html('');
      $('.slider .element table').eq(previosSlide).clone().appendTo('.show_features');
      $('.slider .show_features').slideDown(scrollSpeed);
      $('.slider .show_features').fadeIn(scrollSpeed);
      
  });

  $('.slider .element').eq(currentSlide).animate({'left': '235px'}, scrollSpeed);
  $('.slider .element').eq(currentSlide).fadeOut(scrollSpeed);

  $('.slider .element').eq(previosSlide).css({'left': '-235px'});
  $('.slider .element').eq(previosSlide).fadeIn(scrollSpeed);
  $('.slider .element').eq(previosSlide).animate({'left': '0px'}, scrollSpeed);

   $('.slider2 .slides .slide:hidden').remove();

  $('.slider .element').eq(previosSlide).find('.materials .slide').each(function(){
      $(this).clone().appendTo('.slider2 .slides');
  });

  $('.slider2 .slide').eq(0).css({'left': '0px'});
  $('.slider2 .slide:visible').fadeOut(scrollSpeed);
  $('.slider2 .slide').eq(1).fadeIn(scrollSpeed);

}

$(document).on('click', '.item_next', function(){
  if(!$('.slider .element, .show_features').is(':animated')){
    scrollNext();
  }
});

$(document).on('click', '.item_back', function(){
   if(!$('.slider .element, .show_features').is(':animated')){
    scrollPrevios();
  }
});

sliderInit(0);

}

sliderMain();

function sliderSecondary(){
  var scrollSpeed = 300;
  var totalSlides = $('.slider2 .slide').length;

  /* Начальный элемент */
  scrollNext();

/* Скролл вперед */
function scrollNext(){

  var currentSlide = $('.slider2 .slide:visible').index();
  var nextSlide;

  if(currentSlide+1==totalSlides) {
    nextSlide = 0;
  } else {
    nextSlide=currentSlide+1;
  }

  $('.slider2 .show_features').slideUp(scrollSpeed, function(){
      $('.slider2 .show_features').html('');
      $('.slider2 .slide table').eq(nextSlide).clone().appendTo('.show_features');
      $('.slider2 .show_features').slideDown(scrollSpeed);
      $('.slider2 .show_features').fadeIn(scrollSpeed);
      
  });

  $('.slider2 .slide').eq(nextSlide).css({'left': '235px'});
  $('.slider2 .slide').eq(nextSlide).fadeIn(scrollSpeed);
  $('.slider2 .slide').eq(nextSlide).animate({'left': '0px'}, scrollSpeed);

  $('.slider2 .slide').eq(currentSlide).animate({'left': '-250px'}, scrollSpeed);
  $('.slider2 .slide').eq(currentSlide).fadeOut(scrollSpeed);

}

/* Скролл назад */
function scrollPrevios(){

  var currentSlide = $('.slider2 .slide:visible').index();
  var previosSlide;

  if(currentSlide<0) {
    previostSlide = totalSlides-1;
  } else {
    previosSlide=currentSlide-1;
  }

  $('.slider2 .show_features').slideUp(scrollSpeed, function(){
      $('.slider2 .show_features').html('');
      $('.slider2 .slide table').eq(previosSlide).clone().appendTo('.show_features');
      $('.slider2 .show_features').slideDown(scrollSpeed);
      $('.slider2 .show_features').fadeIn(scrollSpeed);
      
  });

  $('.slider2 .slide').eq(currentSlide).animate({'left': '235px'}, scrollSpeed);
  $('.slider2 .slide').eq(currentSlide).fadeOut(scrollSpeed);

  $('.slider2 .slide').eq(previosSlide).css({'left': '-235px'});
  $('.slider2 .slide').eq(previosSlide).fadeIn(scrollSpeed);
  $('.slider2 .slide').eq(previosSlide).animate({'left': '0px'}, scrollSpeed);

}

$(document).on('click', '.item2_next', function(){
  if(!$('.slider2 .slide').is(':animated')){
    scrollNext();
  }
});

$(document).on('click', '.item2_back', function(){
  if(!$('.slider2 .slide').is(':animated')){
    scrollPrevios();
  }
});

}

sliderSecondary();

});