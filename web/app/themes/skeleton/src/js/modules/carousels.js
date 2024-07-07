import Swiper from 'swiper';
import { Navigation, Pagination, A11y, Autoplay, EffectFade } from 'swiper/modules';

export function initCarousel() {
	const selector = '.m-home-latest__showcase-swiper';
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
	};

	initCarouselSwipers(defaults, selector);

	function initCarouselSwipers(defaults = {}, selector) {
		const swipers = document.querySelectorAll(selector);
		swipers.forEach((swiper) => {
			const options = {
				...defaults,
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
					el: '.m-home-latest__swiper-pagination',
					clickable: true,
				},
				navigation: false ? {
					enabled: true,
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev',
				} : false,
				autoplay: 7000 ? {
					delay: 7000,
					pauseOnMouseEnter: true,
				} : false,
			};

			new Swiper(swiper, options);
		});
	}
}