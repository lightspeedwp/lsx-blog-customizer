/*
 * lsx-blog-customizer.js
 */
var LSX_Blog_Customizer = {
	initSlickSlider: function() {
		var isMobile = window.matchMedia('only screen and (max-width: 600px)').matches;

		if (isMobile) {
			var $sliders = jQuery(
				'.lsx-blog-customizer-posts-slider, .lsx-blog-customizer-terms-slider, .lsx-related-posts-wrapper'
			);
		} else {
			var $sliders = jQuery('.lsx-blog-customizer-posts-slider, .lsx-blog-customizer-terms-slider');
		}

		$sliders.on('init', function(event, slick) {
			if (slick.options.arrows && slick.slideCount > slick.options.slidesToShow)
				$sliders.addClass('slick-has-arrows');
		});

		$sliders.on('setPosition', function(event, slick) {
			if (!slick.options.arrows) $sliders.removeClass('slick-has-arrows');
			else if (slick.slideCount > slick.options.slidesToShow) $sliders.addClass('slick-has-arrows');
		});

		$sliders.slick({
			draggable: false,
			infinite: true,
			swipe: false,
			cssEase: 'ease-out',
			dots: true,
			responsive: [
				{
					breakpoint: 992,
					settings: {
						slidesToScroll: 1,
						draggable: true,
						arrows: true,
						swipe: true,
					},
				},
				{
					breakpoint: 768,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						draggable: true,
						arrows: false,
						swipe: true,
					},
				},
			],
		});

		var $categoriesSlider = jQuery('#categories-slider');

		$categoriesSlider.on('init', function(event, slick) {
			if (slick.options.arrows && slick.slideCount > slick.options.slidesToShow)
				$categoriesSlider.addClass('slick-has-arrows');
		});

		$categoriesSlider.on('setPosition', function(event, slick) {
			if (!slick.options.arrows) $categoriesSlider.removeClass('slick-has-arrows');
			else if (slick.slideCount > slick.options.slidesToShow)
				$categoriesSlider.addClass('slick-has-arrows');
		});

		if ($categoriesSlider.length) {
			var totalSlides = $categoriesSlider.find('.item').length,
				showSlides = {
					lg: 0,
					md: 0,
					sm: 0,
					xs: 0,
				};

			showSlides.xs = totalSlides >= 3 ? 2 : totalSlides;
			showSlides.sm = totalSlides >= 4 ? 3 : totalSlides;
			showSlides.md = totalSlides >= 5 ? 4 : totalSlides;
			showSlides.lg = totalSlides >= 6 ? 5 : totalSlides;

			$categoriesSlider.slick({
				draggable: false,
				infinite: true,
				swipe: false,
				cssEase: 'ease-out',
				dots: true,
				slidesToScroll: showSlides.lg,
				slidesToShow: showSlides.lg,
				responsive: [
					{
						breakpoint: 1200,
						settings: {
							slidesToScroll: showSlides.md,
							slidesToShow: showSlides.md,
						},
					},
					{
						breakpoint: 992,
						settings: {
							slidesToScroll: showSlides.sm,
							slidesToShow: showSlides.sm,
							draggable: true,
							arrows: false,
							swipe: true,
						},
					},
					{
						breakpoint: 768,
						settings: {
							slidesToScroll: showSlides.xs,
							slidesToShow: showSlides.xs,
							draggable: true,
							arrows: false,
							swipe: true,
						},
					},
				],
			});
		}
	},

	createCookie: function(name, value, days) {
		var expires;

		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
			expires = '; expires=' + date.toGMTString();
		} else {
			expires = '';
		}

		document.cookie = name + '=' + value + expires + '; path=/';
	},

	readCookie: function(name) {
		var nameEQ = name + '=';
		ca = document.cookie.split(';');

		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];

			while (c.charAt(0) == ' ') {
				c = c.substring(1, c.length);
			}

			if (c.indexOf(nameEQ) == 0) {
				return c.substring(nameEQ.length, c.length);
			}
		}

		return null;
	},

	eraseCookie: function(name) {
		this.createCookie(name, '', -1);
	},

	initLayoutSwitcher: function() {
		if (typeof tooltip !== 'undefined' && $.isFunction(tooltip)) {
			jQuery('.lsx-layout-switcher-option[data-toggle="tooltip"]').tooltip();
		}

		jQuery(document).on('click', '.lsx-layout-switcher-option', function(e) {
			e.preventDefault();

			var _this = jQuery(this),
				_layout = _this.data('layout'),
				_pageKey = _this.parent('.lsx-layout-switcher-options').data('page');

			_this.siblings('.lsx-layout-switcher-option.active').removeClass('active');
			_this.addClass('active');

			if (jQuery('body').hasClass('.blog')) {
				LSX_Blog_Customizer.blogLayoutSwitcher(_layout);
			}

			LSX_Blog_Customizer.createCookie('lsx-' + _pageKey + '-layout', _layout, 30);
			location.reload();
		});
	},

	blogLayoutSwitcher: function(layout) {
		jQuery('body')
			.removeClass('lsx-body-grid-layout')
			.removeClass('lsx-body-half-grid-layout')
			.removeClass('lsx-body-list-layout');

		if ('default' !== layout) {
			jQuery('body').addClass('lsx-blog-' + layout + '-layout');
		}
	},
};

jQuery(document).ready(function() {
	LSX_Blog_Customizer.initSlickSlider();
	LSX_Blog_Customizer.initLayoutSwitcher();
});
