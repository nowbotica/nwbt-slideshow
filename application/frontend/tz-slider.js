
console.log('tz-slider.js');
(function( $ ) {
 
    "use strict";
    $(function() {
     // $(document).foundation();
    		// console.log('page loaded');
    // Code here
		// On before slide change
		$('.slick-banner')
		.on('init', function(event, slick, currentSlide, nextSlide){
			console.log('init', event, currentSlide)
			// debugger;
			// $("[data-slick-index='"+0+"']").find('h2').css({
		 //  		'opacity': 1,
		 //  		'zoom'   : 1
		 //  	});
		})
		.on('beforeChange', function(event, slick, currentSlide, nextSlide){
		  	// $("[data-slick-index='"+currentSlide+"']").find('h2').css({
		  	// 	'opacity': 0,
		  	// 	'zoom'   : 0.6
		  	// });
		})
		.on('afterChange', function(event, slick, currentSlide, nextSlide){
		  	console.log(nextSlide);
		  	// $("[data-slick-index='"+currentSlide+"']").find('h2').css({
		  	// 	'opacity': 1,
		  	// 	'zoom'   : 1
		  	// });
		})
		// .on('lazyLoaded', function(event, slick, currentSlide, nextSlide){

		// })
		.slick({
			autoplay: true,
			autoplaySpeed: 2200,
			speed: 400,
			 arrows: false,
			 // lazyLoad: 'ondemand'
	    	// setting-name: setting-value
	  	});	




	}); 
})(jQuery);
