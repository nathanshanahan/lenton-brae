
import 'lazysizes';

// Swup setup
import Swup from 'swup';
import SwupBodyClassPlugin from '@swup/body-class-plugin';
import SwupScrollPlugin from '@swup/scroll-plugin';
import GlobalMeasurements from './modules/global-measurements';
import { watchForReveals } from './modules/reveals';
import { rerunGravityFormsScripts } from './modules/gravity-forms';
import { initCarousel } from './modules/carousels';
import { initAccordion } from './modules/accordion';
import { initLoadMore } from './modules/load-more';

const options = {
	linkSelector: `a[href^="${window.location.origin}"]:not([data-no-swup]):not([target="_blank"]), a[href^="/"]:not([data-no-swup]):not([target="_blank"]), a[href^="#"]:not([data-no-swup]):not([target="_blank"])`,
	plugins: [
		new SwupBodyClassPlugin(),
		new SwupScrollPlugin({
			doScrollingRightAway: false,
			animateScroll: false,
		}),
	],
	animateHistoryBrowsing: true,
	animationSelector: '.swup-page-loader',
	containers: [
		'.swup-page-loader',
	],
};
const swup = new Swup(options);
window.swup = swup;

window.addEventListener('DOMContentLoaded', () => {
	GlobalMeasurements.start();
	watchForReveals();
	initCarousel();
	initAccordion();
	initLoadMore();
})

swup.hooks.on('content:replace', (e) => {
	watchForReveals();
	rerunGravityFormsScripts();
	initCarousel();
	initAccordion();
	initLoadMore();
});

