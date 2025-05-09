(function($) {
	"use strict";

	UNCODE.tabs = function() {

	var tabSwitcher = function($el){
		$('.tab-switch, .tab-active-anim, .nav-tabs.tab-no-border:not(.tabs-vertical):not(.tab-switch)', $el).each(function(key, value){
			var $navs = $(value),
				$active = $('li.active', $navs),
				$active_a = $('> a', $active),
				$active_span = $('> span', $active_a),
				vertical = $navs.closest('.vertical-tab-menu').length;
			
			if ( ! $('.switcher-cursor', $navs).length && ! vertical ) {
				$navs.append('<span class="switcher-cursor" aria-hidden="true" tabindex="-1" />');
			}

			var $cursor = $('.switcher-cursor', $navs),
				active_w = $('a', $active).outerWidth(),
				span_w = $active_span.outerWidth(),
				active_pos = $active.position(),
				active_a_pos = $active_a.position(),
				span_pos = $active_span.position(),
				cursor_w = $navs.hasClass('tab-no-border') && !$navs.hasClass('tab-switch') ? span_w : active_w,
				cursor_left = $navs.hasClass('tab-no-border') && !$navs.hasClass('tab-switch') ? active_pos.left + span_pos.left : active_pos.left;

			cursor_left = cursor_left + active_a_pos.left + parseInt($active_a.css('marginLeft'), 10);

			if ( ! vertical ) {
				$cursor.css({
					left: cursor_left,
					width: cursor_w
				});
			}

			$navs.addClass('switch-init');
		});
	};

	var $body = $('body');
	tabSwitcher($body);

	var tabHoverIntent = function(){
		var setHover;
		$('.tab-hover [data-toggle="tab"], .tab-hover [data-toggle="pill"]')
			.on('mouseover', function(e){
				var $this = $(e.target);
				setHover = requestTimeout(function() {
					$this.trigger('hover-int');
				}, 50);
			})
			.on('mouseout', function(){
				clearRequestTimeout(setHover);
			});
	};
	tabHoverIntent();

	var tabInit = function(){
		$('[data-toggle="tab"], [data-toggle="pill"]').on('click.bs.tab.data-api hover-int', function(e) {
			e.preventDefault()
			var $el = $(this);
			$el.tab('show');
			var $container = $el.closest('.uncode-tabs');
			tabSwitcher($container);
			requestTimeout(function() {
				window.dispatchEvent(UNCODE.boxEvent);
				var $tabs = $(e.currentTarget).closest('.uncode-tabs');

				if ( $tabs.hasClass('tabs-trigger-box-resized') ) {
					window.dispatchEvent(new CustomEvent('boxResized'));
				} else if ( $tabs.hasClass('tabs-trigger-window-resize') ) {
					window.dispatchEvent(new Event('resize'));
					$(window).trigger('uncode.re-layout');
				} 

				var $active_panel = $('.tab-pane.active', $tabs);

				$.each($('.animate_when_almost_visible:not(.start_animation):not(.t-inside):not(.drop-image-separator), .index-scroll .animate_when_almost_visible, .tmb-media .animate_when_almost_visible:not(.start_animation), .animate_when_almost_visible.has-rotating-text, .custom-grid-container .animate_when_almost_visible:not(.start_animation)', $active_panel), function(index, val) {
					var element = $(val),
						delayAttr = element.attr('data-delay');
					if (delayAttr == undefined) delayAttr = 0;
					requestTimeout(function() {
						element.addClass('start_animation');
					}, delayAttr);
				});

			}, 300);

			var $li = $el.closest('li'),
				mQuery = $el.closest('.tab-tablet-bp').length ? UNCODE.mediaQuery : UNCODE.mediaQueryMobile;
			$('li', $container).not($li).find('.tab-pane').slideUp(250)
			$('.tab-pane', $li).slideDown(250);
			var completeSlideDown = requestTimeout(function(){
				if ( UNCODE.wwidth <= mQuery && typeof e.originalEvent !== 'undefined' ) {
					var pos = $el.offset(),
						rect = $el[0].getBoundingClientRect(),
						$masthead = $('#masthead > .menu-container'),
						considerMenu = $('.menu-wrapper .is_stuck').length && $('.menu-wrapper .is_stuck > div').offset().top > 50 ? UNCODE.menuMobileHeight : 0;

					if ( ( ( rect.top ) - considerMenu ) < 0 || ( rect.bottom ) > (window.innerHeight || document.documentElement.clientHeight) ) {
						$('html, body').animate({
							scrollTop: ( pos.top ) - considerMenu
						},{
							easing: 'easeInOutQuad',
							duration: 250
						});
					}
				}
			}, 260);
		});
	}
	tabInit();

	var tabResponsive = function(){
		if ( SiteParameters.is_frontend_editor ) {
			return true;
		}
		var $tabContainers = $('.tab-container.tabs-breakpoint');
		$tabContainers.each(function(){
			var $tabContainer = $(this),
				$tabContent = $('.tab-content', $tabContainer),
				$nav = $('.nav-tabs', $tabContainer),
				mQuery = $tabContainer.hasClass('tab-tablet-bp') ? UNCODE.mediaQuery : UNCODE.mediaQueryMobile;

			$('> li', $nav).each(function(){
				var $li = $(this),
					dataID = $li.attr('data-tab-id');

				if ( UNCODE.wwidth <= mQuery ) {
					if ( ! $('.tab-pane', $li ).length ) {
						var $append_pane = $('[data-id="' + dataID + '"], #' + dataID, $tabContent);
						$tabContainer.addClass('tabs-appended');
						$li.append($append_pane);
					}

					if ( $li.hasClass('active') ) {
						$('> a', $li).click();
					}
				} else {
					if ( ! $('[data-id="' + dataID + '"]', $tabContent ).length ) {
						var $append_pane = $('[data-id="' + dataID + '"], #' + dataID, $nav);
						$tabContainer.removeClass('tabs-appended');
						$tabContent.prepend($append_pane.removeAttr('style'));
					}
				}
			});
		});

	}
	tabResponsive();

	$(window).on('wwResize', function(){
		tabHoverIntent();
		tabSwitcher($body);
	});

	var setCTA;
	$(window).on( 'resize', function(){
		clearRequestTimeout(setCTA);
		setCTA = requestTimeout( tabResponsive, 100 );
	});

	$('.nav-tabs').each(function(){
		var $nav = $(this),
			$lis = $('> li:not(.active)', $nav),
			$links = $('.tab-excerpt-link', $nav);
		$('.tab-excerpt', $lis).slideUp(400, function(){
			$(this).addClass('init');
		});

		$links.each(function(){
			var $link = $(this),
				$par_a = $link.closest('a'),
				href = $link.attr('data-href'),
				target = $link.attr('data-target');
			$par_a.addClass('inner-link');
			$link.on('click', function(){
				var _link = document.createElement('a');
				_link.href = href;
				_link.target = typeof target === 'undefined' || target === '' ? '_self' : target;
				_link.click();
			});
		});
		$nav.addClass('tab-init');
	});

	$('.uncode-tabs.tabs-no-lazy').each(function(){
		var $tabs = $(this),
			$panes = $('.tab-pane:not(.active)', $tabs);
		$panes.each(function(){
			var $pane = $(this),
				$imgs = $('img[loading="lazy"]', $pane);
			$imgs.removeAttr('loading');
			$imgs.removeAttr('decoding');
		});
	});

};

})(jQuery);
