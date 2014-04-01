$(document).ready(function(){

jQuery.extend(jQuery.fancybox.defaults, {
  padding: '0px',
  'scrolling': 'no',
  autoSize: true,
  maxWidth: '2000px'
});

var accessories;
var openedProduct;
var descHeight;
var scrHeight;
var descHeight;
var descMargin;

$('.navigation a, .basket').hover(function(){
	if(!$(this).is('.opened')) {
		$(this).stop(true, true).animate({
				marginTop: -3,
				paddingTop: 3,
				paddingBottom: 3,
			}, 100); 
	}
}, function(){
	if(!$(this).is('.opened')) {
	$(this).stop(true, true).animate({
			marginTop: 3,
			paddingTop: 0,
			paddingBottom: 0,
		}, 100);
}
});


$('.type_menu .item').click(function(){
	var target = $(this).attr('href');
	openItem(target);
	return false;
});

$('.type_menu .item').hover(function(){
	if(!$(this).is('.item_active')) {
		$(this).stop(true, true).animate({width: 100}, 100);
	}
	
}, function(){
	if(!$(this).is('.item_active')) {
		$(this).stop(true, true).animate({width: 94}, 100);
	}
});

$('.type_menu ul li').hover(function(){
	if(!$(this).find('a').is('.sub_active')) {
		$(this).find('a').stop(true, true).animate({width: 74}, 100);
	}
}, function(){
	if(!$(this).find('a').is('.sub_active')) {
		$(this).find('a').stop(true, true).animate({width: 70}, 100);
	}
});

$('.type_menu ul li a').click(function(){
	if(!$(this).is('.sub_active')) {
		$('.type_menu ul li .sub_active').stop(true, true).animate({width: 70, backgroundColor: 'RGBA(255, 255, 255, 0.6)'});
		$('.type_menu ul li .sub_active').removeClass('sub_active');
		$(this).stop(true, true).animate({width: 74, backgroundColor: 'RGBA(255, 255, 255, 1)'});
		$(this).addClass('sub_active');
	}

	return false;
});

$('.products .product').click(function(){
	
	$.fancybox.open($('.descriptions .prod:eq('+$(this).index()+')'));

	/*var pid = $(this).index();
	descHeight = $(this).find('.description').height();
	scrHeight = $(window).height();
	descMargin = (scrHeight-descHeight)/2;
	productBox(pid, descMargin);*/
});

/*function openItem(target) {

	var opened = '#'+$('.type_menu ul:visible').attr('id');

	if(target!=opened) {
		$('.type_menu ul:visible').slideUp(150);
		$('.type_menu ul:visible').prev().animate({width: 94, backgroundColor: 'RGBA(255, 255, 255, .6)'}, 150);
		$('.type_menu ul:visible').prev().removeClass('item_active');
		$(target).prev().animate({width: 100, backgroundColor: 'RGBA(255, 255, 255, 1)'}, 150);
		$(target).prev().addClass('item_active');

		$(target+' li:first').find('a').animate({backgroundColor: 'RGBA(255, 255, 255, 1)', width: 74} ,150);
		$(target+' li:first').find('a').addClass('sub_active');

		$(target).slideDown(150);
	}
}*/

/*function productBox(pid, margin) {

	var product = $('.product').eq(pid);

	console.log(margin);

	accessories = $('.accessories');
	openedProduct = pid;

	product.find('.description_bg').css({'margin-top': 100}).fadeIn(300);
	product.find('.acc_place').html(accessories);

	$('.shadow').fadeIn(300);
	$('.parameters_table').mCustomScrollbar({
		mouseWheelPixels: 100,
		scrollInertia: 0,
	});

	scrollLock();
}

$('.product_close').click(function(){

	var closeId = $(this).attr('href');
	
	$('.description_bg:eq('+closeId+')').fadeOut(250);
	$('.description:eq('+closeId+')').animate({'margin-top': '0px'}, 250, function(){
		scrollUnlock();
	});
	$('.shadow').fadeOut(300);

	$('.parameters_table').mCustomScrollbar('destroy');
	$('.auth_box').fadeOut(300);

	return false;
});*/

/*$('.pick1').click(function(){
	$('.product[pid='+openedProduct+']').find('.acc_place, .accessories').show().html();
	$('.product[pid='+openedProduct+']').find('.acc_place, .accessories').animate({left: 0}, 300);
	return false;
});

$(document).on('click', '.acc_back', function(){
	$('.acc_place').animate({'left': '-365px'}, 300);
	return false;
});*/



/* Клик по пункту меню */
$("#accordion > li > div").click(function(){
 
    if(false == $(this).next().is(':visible')) {
        $('#accordion ul').slideUp(150);
        $(this).animate({'background-color': 'RGBA(255, 255, 255, 1)', 'margin-left': '-4px'},150);
        $(this).attr('state', 'active');
        $('#accordion ul:visible').prev().animate({'background-color': 'RGBA(255, 255, 255, .6)', 'margin-left': '0px'},150, function(){
        	$('#accordion ul li').animate({'margin-left': '0px', 'background-color': 'RGBA(255, 255, 255, .6)'}, 300);
        });
        $('#accordion ul:visible').prev().attr('state', 'none');
    } else {
    	$(this).animate({'background-color': 'RGBA(255, 255, 255, .6)', 'margin-left': '0px'},150);
    }
    $(this).next().slideToggle(150);
    });
 
/*$('#accordion ul:eq(0)').show();*/

/* Клик по подпункту меню */

var subIndex;

$('#accordion ul li').click(function(){

	$(this).parent().find('li').eq(subIndex).animate({'background-color': 'RGBA(255, 255, 255, 0.6)', 'margin-left': '0px'});
	$(this).parent().find('li').eq(subIndex).attr('state', 'none');

	$(this).stop(false, true).animate({'background-color': 'RGBA(255, 255, 255, 1)'}, 100, function(){
		$(this).attr('state', 'active');
		subIndex = $(this).index();
	});


});

/* Наведение на пункт меню */
$("#accordion > li > div").hover(function(){
	$(this).stop(false, false).animate({'margin-left': '-4px'}, 100);
},function(){
	if($(this).attr('state')!='active') {
		$(this).stop(false, false).animate({'margin-left': '0px'}, 100);
	}
});

/* Наведение на пункт подменю */
 $('#accordion ul li').hover(function(){
 	$(this).stop(false, true).animate({'margin-left': '-4px'}, 100);
 }, function(){
 	if($(this).attr('state')!='active') {
 		$(this).stop(false, true).animate({'margin-left': '0px'}, 100);
 	}
 });

 /*$(window).resize(function(){

	scrHeight = $(window).height();
	descMargin = (scrHeight-descHeight)/2;
 	
	$('.description').css({'marginTop': descMargin}, 300);

 });*/

$('.auth').click(function(){
	$.fancybox.open($('.auth_box'));
	return false;
});

/*function authBox() {

	var winWidth  = $(window).width();
	var authWidth = $('.auth_box').outerWidth();
	var authLeft = winWidth/2 - authWidth/2;
	var winHeight  = $(window).height();
	var authHeight = $('.auth_box').outerHeight();
	var authTop = winHeight/2 - authHeight/2;

	$('.auth_box').css({'left': authLeft, 'top': authTop});
	$('.auth_box').fadeIn(300);

	scrollLock();
	$('.shadow').fadeIn(300);
}*/

$('.register').click(function(){
	$.fancybox.open($('.register_box'));
	return false;
});
	
$('.meters a').click(function(){
	$(this).parent().find('input').prop('disabled', false).focus();

	return false;
});

$('.product label').click(function(){
	if($(this).prev().is(':checked')) {
		$(this).parent().parent().find('td').css({'background-color': '#fbfaf6'});
	} else {
		$(this).parent().parent().find('td').css({'background-color': '#f6efe5'});
	}
});


if($('#avatar')) {
	$('#avatar').change(function(){
		var fileName = $(this).val().split('/').pop().split('\\').pop();
		$(this).parent().find('.filename').val(fileName);
	});
}

$('.orange_button').click(function(){
	$.fancybox.open($('.thanks_box'));
	return false;
});

$('select').styler();

});