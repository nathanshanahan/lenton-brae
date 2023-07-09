import.meta.webpackHot?.accept(console.error);

import 'lazysizes';

// Swup setup
import Swup from 'swup';
import SwupBodyClassPlugin from '@swup/body-class-plugin';
import SwupScrollPlugin from '@swup/scroll-plugin';


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
	containers: [
		'.swup-page-loader',
	],
	animationSelector: '.swup-page-loader',
};
const swup = new Swup(options);

// Generic events - these are loaded once only and would not re-run on Swup page changes
window.addEventListener('DOMContentLoaded', () => {
	// Place one off code here - such as click events for the menu
});

window.addEventListener('load', () => {
	//
});

// Load Events when using Swup
swup.on('contentReplaced', () => {
	//
});
