(function($) {
	"use strict";

	UNCODE.multibg = function(){
	var $bgsWraps = $('.uncode-multi-bgs'),
		$body = $('body');
	$bgsWraps.each(function(){
		var $bgWrap = $(this),
			transition = $bgWrap.attr('data-transition'),
			transitionTime = $bgWrap.attr('data-transition-time'),
			dataPace = $bgWrap.attr('data-transition-pace'),
			dataThreshold = $bgWrap.attr('data-transition-threshold'),
			dataTime = $bgWrap.attr('data-carousel-time'),
			dataMobileCarousel = $bgWrap.attr('data-carousel-mobile'),
			random = $bgWrap.attr('data-multi-random'),
			_bgWrap = $bgWrap[0],
			$multiBgs = $('.multi-background', $bgWrap),
			imgFirst,
			bgAmount = $multiBgs.length,
			isInViewport = false,
			lastScrollTop = 0,
			counter = 0,
			totMove = 0,
			autoScroll = typeof dataPace === 'undefined' && transition === 'scroll',
			pace = typeof dataPace !== 'undefined' && dataPace !== '' ? parseFloat(dataPace) : ( transition === 'scroll' ? 20 : 200 ),
			threshold = typeof dataThreshold !== 'undefined' && dataThreshold !== '' ? parseFloat(dataThreshold) : 0,
			offX, offY,
			wait = typeof transitionTime !== 'undefined' && transitionTime !== '' ? parseFloat(transitionTime)+1 : 250,
			carouselTime = typeof dataTime !== 'undefined' && dataTime !== '' ? parseFloat(dataTime) : 5000,
			requestCarousel,
			is_header = $bgWrap.closest('#page-header').length,
			startTime = new Date();

		transition = typeof transition === 'undefined' ? '' : transition;

		if ( random === 'true' ) {
			UNCODE.shuffle($multiBgs);
		}

		$multiBgs.find('.background-inner').attr('data-active','false');

		imgFirst = $multiBgs.first().find('.background-inner').attr('data-active','true').css('background-image').replace('url(','').replace(')','').replace(/\"/gi, "");

		var loadFirst = function(){
			$multiBgs.first().attr('data-load', 'loaded').stop(true,false).animate({
				opacity: 1
			}, 250, 'easeInQuad').css({
				zIndex: 1
			});	
		}

		var loadError = function(){
			console.log('Multi BG loading error');
		}

		UNCODE.checkImgLoad( imgFirst, loadFirst, loadError );

		var loadSlides = function( $thisBg ){
			$thisBg.attr('data-load', 'loaded');	
		}

		$multiBgs.not('[data-load="loaded"]').each(function(ind, val){
			var $thisBg = $(val);

			if ( typeof $thisBg !== 'undefined' && $thisBg.length ) {

				var _imgBg = $(val).find('.background-inner').css('background-image');

				if ( typeof _imgBg === 'undefined' || _imgBg === '' || _imgBg === 'none' ) {
					$thisBg.attr('data-load', 'loaded');
				} else {
					_imgBg = _imgBg.replace('url(','').replace(')','').replace(/\"/gi, "");
					if ( typeof _imgBg !== 'undefined' ) {
						UNCODE.checkImgLoad( _imgBg, loadSlides, loadError, $thisBg );
					}
				}
			}
		});
					
		var multiCarousel = function(e) {

			clearRequestTimeout(requestCarousel);

			var checkTime = new Date();
			if ( checkTime - startTime >= carouselTime && isInViewport ) {
				var $checkEl = $multiBgs.eq((counter+1)%bgAmount);

				if ( $checkEl.attr('data-load') !== 'loaded' ) {
					requestTimeout(function(){
						multiCarousel();
					}, 100);
					return;
				}

				counter++;

				$multiBgs.each(function(ind, val){
					if ( ind === counter%bgAmount ) {
						$(val).stop(true,false).animate({
							opacity: 1
						}, wait, 'easeInQuad').css({
							zIndex: 1
						}).find('.background-inner').attr('data-active','true');
					} else {
						$(val).stop(true,false).animate({
							opacity: 0
						}, wait, 'easeInQuad', function(){
							console.log('check1', $(val));
							$(val).find('.background-inner').attr('data-active','false');
						}).css({
							zIndex: 0
						});
					}
				});

			}

			if ( transition === '' || UNCODE.wwidth <= UNCODE.mediaQueryMobile ) {
				requestCarousel = requestTimeout(multiCarousel, carouselTime);
			}
	
		};

		var multiMove = function(e) {
			if ( $body.hasClass('navbar-hover') ) {
				return;
			}
			var checkTime = new Date(),
                bound = $bgWrap[0].getBoundingClientRect(),
                windowTop = (window.clientYOffset || document.documentElement.scrollTop);
			if (offX) {
				totMove += Math.sqrt(
					Math.pow(offY - e.clientY, 2) + Math.pow(offX - e.clientX, 2)
				);
			}
			if (
                totMove >= pace && 
                checkTime - startTime >= wait &&
                e.clientX <= (bound.left + bound.width) &&
                e.clientX >= bound.left &&
                e.clientY <= (bound.top + bound.height) &&
                e.clientY >= bound.top
            ) {
				counter++;
				startTime = checkTime;
				$multiBgs.each(function(ind, val){
					if ( ind === counter%bgAmount ) {
						$(val).stop(true,false).animate({
							opacity: 1
						}, wait, 'easeInQuad').css({
							zIndex: 1
						}).find('.background-inner').attr('data-active','true');
					} else {
						$(val).stop(true,false).animate({
							opacity: 0
						}, wait, 'easeInQuad', function(){
							console.log('check2', $(val));
							$(val).find('.background-inner').attr('data-active','false');
						}).css({
							zIndex: 0
						});
					}
				});

				totMove = 0;
			}
			
			offX = e.clientX;
			offY = e.clientY;
			
		};

		if ( transition === 'mouse' && UNCODE.wwidth > UNCODE.mediaQueryMobile ) {
			document.addEventListener("mousemove", multiMove);
		}

		window.addEventListener('resize', function() {
			if ( transition === 'mouse' && UNCODE.wwidth > UNCODE.mediaQueryMobile ) {
				document.removeEventListener("mousemove", multiMove);
				document.addEventListener("mousemove", multiMove);
			}
		});

		var lightLoopRAF = function() {

			var scrollTop = (window.clientYOffset || document.documentElement.scrollTop) - (document.documentElement.clientTop || 0),
				checkTime = new Date(),
				bound = $bgWrap[0].getBoundingClientRect(),
				thresholdHeader = is_header ? 100 : threshold,
				rePace = ((bound.height - bound.height * threshold / 100) / 100 * pace)+((bound.height - bound.height * thresholdHeader / 100) / 100 * pace);
			if ( Math.abs(lastScrollTop - scrollTop) >= rePace ) {
				counter++;
				lastScrollTop = scrollTop;
				startTime = checkTime;

				$multiBgs.each(function(ind, val){
					if ( ind === counter%bgAmount ) {
						$(val).stop(true,false).animate({
							opacity: 1
						}, wait, 'easeInQuad').css({
							zIndex: 1
						}).find('.background-inner').attr('data-active','true');
					} else {
						$(val).stop(true,false).animate({
							opacity: 0
						}, wait, 'easeInQuad', function(){
							console.log('check3', $(val));
							$(val).find('.background-inner').attr('data-active','false');
						}).css({
							zIndex: 0
						});
					}
				});

			}

			requestAnimationFrame(function() {
				if ( isInViewport ) {
					lightLoopRAF();
				}
			});
		};

		var currentInd = 0;

		var loopRAF = function() {

			var multiBgRotate = function(slideInd){
				currentInd = slideInd;
				$multiBgs.each(function(ind, val){
					if ( ind === slideInd ) {
						$(val).stop(true,false).animate({
							opacity: 1
						}, wait, 'easeInQuad').css({
							zIndex: 1
						}).find('.background-inner').attr('data-active','true');
					} else {
						$(val).stop(true,false).animate({
							opacity: 0
						}, wait, 'easeInQuad', function(){
							console.log('check', $(val));
							$(val).find('.background-inner').attr('data-active','false');
						}).css({
							zIndex: 0
						});
					}
				});
			};
			var bound = $bgWrap[0].getBoundingClientRect(),
				thresholdPX = UNCODE.wheight * threshold / 100,
				totMove = (UNCODE.wheight + bound.height) - (thresholdPX * (parseFloat(2 - is_header))),
				movePX = totMove / bgAmount,
				moving = UNCODE.wheight - (bound.top + thresholdPX),
				slideInd = 0;

			if ( (UNCODE.wheight-bound.top) < thresholdPX && 0 !== currentInd ) {
				multiBgRotate(0);
			} else if ( bound.top + bound.height < thresholdPX && bgAmount-1 !== currentInd ) {
				multiBgRotate(bgAmount-1);
			} else {
				slideInd = Math.ceil(moving/movePX) - 1;
				if ( slideInd < 0 ) {
					slideInd = 0;
				} else if ( slideInd >= bgAmount ) {
					slideInd = bgAmount-1;
				}
				if ( slideInd !== currentInd ) {
					multiBgRotate(slideInd);
				}
			}

			requestAnimationFrame(function() {
				if ( isInViewport ) {
					loopRAF();
				}
			});
		};

		if( 'IntersectionObserver' in window ) {
		    var observer = new IntersectionObserver(function(entries) {
  
                entries.forEach(function(entry){
                    if ( entry.isIntersecting ) {
                        isInViewport = true;
						if ( transition === 'scroll' ) {
							if ( autoScroll ) {
								loopRAF();
							} else {
								lightLoopRAF();
							}
						} else {
							multiCarousel();
						}
                    } else {
                        isInViewport = false;
                   }
                });
  
		    }, { 
				root: document,
				rootMargin: is_header ?  '-' + threshold + '% 0px 100% 0px' : '-' + threshold + '% 0px'
		  	});

			if ( transition !== 'mouse' || ( transition === 'mouse' && UNCODE.wwidth <= UNCODE.mediaQueryMobile && dataMobileCarousel === "yes" ) ) {
		  		observer.observe(_bgWrap);
			}

			window.addEventListener('resize', function() {
				observer.unobserve(_bgWrap);
				if ( transition !== 'mouse' || ( transition === 'mouse' && UNCODE.wwidth <= UNCODE.mediaQueryMobile && dataMobileCarousel === "yes" ) ) {
					observer.observe(_bgWrap);
				}
			});
	
		}

	});
}


})(jQuery);
