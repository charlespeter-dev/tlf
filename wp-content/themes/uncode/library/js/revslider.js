(function($) {
	"use strict";

	UNCODE.revslider = function() {
	var revSlider6In = function(){
		$('rs-module').each(function(){
			var $slider = $(this);

			$slider.on('revolution.slide.onloaded', function(e, data){
				if ( $(e.currentTarget).closest(".header-revslider").length ) {
					var style = $(e.currentTarget).find("rs-slide").eq(0).attr("data-skin"),
						scrolltop = $(document).scrollTop();
					if ( style != undefined ) {
						UNCODE.switchColorsMenu(scrolltop, style);
					}
				}
			});

			$slider.on('revolution.slide.onchange', function(e, data){
				if ( $(e.currentTarget).closest(".header-revslider").length ) {
					var style = $(e.currentTarget).find("rs-slide").eq(data.slideIndex - 1).attr("data-skin"),
						scrolltop = $(document).scrollTop();
					if ( style != undefined ) {
						UNCODE.switchColorsMenu(scrolltop, style);
					}
				}
			});

		});

	};
	revSlider6In();
	$(window).on("load", revSlider6In );

	document.addEventListener("sr.module.ready", function(e) { 
		var $slider = $('#'+e.id),
			settings = SR7.JSON[e.id];

		if ( typeof settings !== 'undefined' && settings !== null ) {
			if ( $slider.closest(".header-revslider").length ) {
				var style = settings.slides[1].slide.attr.data,
					scrolltop = $(document).scrollTop();
				style = style.match(/data-skin=[\'|\"](.*)[\'|\"]/);
				style = style[1];
				if ( style[1] != undefined ) {
					UNCODE.switchColorsMenu(scrolltop, style);
				}
			}
		}
	});

	document.addEventListener("sr.slide.afterChange", function(e) {
		var $slider = $('#'+e.id),
			settings = SR7.JSON[e.id];

		if ( typeof settings !== 'undefined' && settings !== null ) {
			if ( $slider.closest(".header-revslider").length ) {
				var style = settings.slides[e.current.id].slide.attr.data,
					scrolltop = $(document).scrollTop();
				style = style.match(/data-skin=[\'|\"](.*)[\'|\"]/);
				style = style[1];
				if ( style != undefined ) {
					UNCODE.switchColorsMenu(scrolltop, style);
				}
			}
		}
	});

};

})(jQuery);
