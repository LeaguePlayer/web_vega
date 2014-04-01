$(document).ready(function(){

			var view;

			$('.prod .pick_buttons .picks').click(function(){

	    	if(!$(this).hasClass('active')){
    			$(this).addClass('active').text('Вернуться к описанию товара');
    			
    			view = $(this).parent().parent().find('.view').html();
    			$(this).parent().parent().find('.view').html($('.accessories').fadeIn(300));

				$(".materials").jCarouselLite({
			        btnNext: ".next",
			        btnPrev: ".prev",
			        visible: 1,
			        mouseWheel: true,
			        speed: 150,
			        beforeStart: function(slide) {
			        	var index = slide.data('id');
			        	$('.plinths .plinth').hide();
						$('.plinths .plinth').eq(index).show();
			        	
				    },
				    afterEnd: function(slide) {

				    }
		    	});

				$(".pcolors").jCarouselLite({
			        btnNext: ".cnext",
			        btnPrev: ".cprev",
			        visible: 1,
			        mouseWheel: true,
			        speed: 150,
		    	});

	    	} else {
	    		$(this).removeClass('active').text('Подобрать плинтус');
	    		$(this).parent().parent().find('.view').html(view);
	    	}

		});

$('.prod_table').perfectScrollbar();

});