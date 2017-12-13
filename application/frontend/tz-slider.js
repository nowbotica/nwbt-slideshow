(function( $ ) {
 
    "use strict";
    $(function() {
		// On before slide change
		$('.slick-banner')
		.on('init', function(event, slick, currentSlide, nextSlide){
			// console.log('init', this, event, slick, currentSlide, nextSlide)
			// debugger;
			$("[data-slick-index='"+0+"']").find('h2').css({
		  		'opacity': 0,
		  		'zoom'   : 0.6
		  	});
		})
		.on('beforeChange', function(event, slick, currentSlide, nextSlide){
			console.log(slick.currentSlide)
		  	$("[data-slick-index='"+slick.nextSlide+"']").find('h2').css({
		  		'opacity': 0,
		  		'zoom'   : 0.6
		  	});
		})
		.on('afterChange', function(event, slick, currentSlide, nextSlide){
		  	console.log(nextSlide);
		  	$("[data-slick-index='"+slick.currentSlide+"']").find('h2').css({
		  		'opacity': 1,
		  		'zoom'   : 1
		  	});
		  	$("[data-slick-index='"+(slick.currentSlide-1)+"']").find('h2').css({
		  		'opacity': 0,
		  		'zoom'   : 0.6
		  	});
		})
		// .on('lazyLoaded', function(event, slick, currentSlide, nextSlide){
		// })
		.slick({
			autoplay: true,
			autoplaySpeed: 2200,
			speed: 400,
			arrows: false
			// ,
			// lazyLoad: 'ondemand',
			// fade: true,
  			// cssEase: 'linear'
	    	// setting-name: setting-value
	  	});	
	}); 
})(jQuery);
