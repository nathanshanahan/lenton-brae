
import 'lazysizes';

// Swup setup
import Swup from 'swup';
import SwupBodyClassPlugin from '@swup/body-class-plugin';
import SwupScrollPlugin from '@swup/scroll-plugin';
import GlobalMeasurements from './modules/global-measurements';
import { watchForReveals } from './modules/reveals';
import { rerunGravityFormsScripts } from './modules/gravity-forms';
import { initCarousel } from './modules/carousels';

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
})

swup.hooks.on('content:replace', (e) => {
	watchForReveals();
	rerunGravityFormsScripts();
	initCarousel();

	const currentPage = swup.cache.getCurrentPage();

	if (currentPage.originalContent) {
		const doc = new DOMParser().parseFromString(currentPage.originalContent, 'text/html');
		const dataset = doc.body.dataset;

		const sets = Object.keys(dataset);
		sets.forEach(d => {
			document.body.dataset[d] = dataset[d];
		});
	}
});

