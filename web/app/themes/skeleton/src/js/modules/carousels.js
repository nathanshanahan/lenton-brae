import Swiper from 'swiper';
import { Navigation, Pagination, A11y, Autoplay, EffectFade } from 'swiper/modules';

export function initCarousel() {
	// Usage examples:
	// Initialize the home latest showcase carousel
	initSwiperCarousel('.m-home-latest__showcase-swiper', {
		pagination: {
			enabled: true,
			el: '.m-home-latest__swiper-pagination',
			clickable: true,
		},
	});

	// Initialize another carousel with custom options
	initSwiperCarousel('.m-latest-news-carousel__cards-swiper', {
		pagination: {
			enabled: true,
			el: '.m-latest-news-carousel__swiper-pagination',
			clickable: true,
		},
		autoplay: {
			delay: 7000,
		},
	});
}

function initSwiperCarousel(selector, customOptions = {}) {
	const carousels = document.querySelectorAll(selector);

	if (!carousels.length) return;

	const defaults = {
		modules: [Navigation, Pagination, Autoplay, A11y, EffectFade],
		direction: 'horizontal',
		autoHeight: true,
		effect: 'fade',
		fadeEffect: {
			crossFade: true,
		},
		loop: true,
		slidesPerView: 1,
		spaceBetween: 0,
		centeredSlides: true,
		observer: true,
		breakpoints: {
			640: {
				slidesPerView: 1,
				spaceBetween: 0
			}
		},
		pagination: {
			enabled: true,
			el: `${selector}-pagination`,
			clickable: true,
		},
		navigation: {
			enabled: false,
			nextEl: `${selector}-next`,
			prevEl: `${selector}-prev`,
		},
		autoplay: {
			delay: 5000,
			pauseOnMouseEnter: true,
		},
	};

	const options = { ...defaults, ...customOptions };

	initCarouselSwipers(options, selector);

	function initCarouselSwipers(options, selector) {
		const swipers = document.querySelectorAll(selector);
		swipers.forEach((swiper) => {
			new Swiper(swiper, options);
		});
	}
}