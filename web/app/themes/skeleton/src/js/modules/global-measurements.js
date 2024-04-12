
/**
 * Global Measurements
 *
 *
 * Measure the layout viewport (and optionally the visual viewport)
 * and make measurements available as CSS custom properties. Observe
 * document and window to update on resize.
 *
 * Note: module doesn't self-initialise. Call GlobalMeasurements.start()
 * to start measurements at the time of your choosing.
 *
 */

const GlobalMeasurements = (() => {

	const gm = {
		callbacks: new Set(), // use set for free dedupe-ing
		hasCallbacks: false,
		currentResults: [],
		observer: undefined,
		sizeRef: undefined,
	};

	/**
	 * Stops observation
	 */
	gm.start = function() {
		// if already exists, restart observation and bail early
		if (gm.observer) {
			gm.observer.observe(gm.sizeRef);
			return;
		}

		let supportsResizeObserver = false;
		try {
			supportsResizeObserver = !! ResizeObserver;
		}
		catch (err) {
			//
		}

		if (supportsResizeObserver) {
			const sizeRef = document.body;
			const observer = new ResizeObserver(entries => {
				// NOTE:
				// "ResizeObserver loop completed with undelivered notifications" error will
				// be thrown in Safari on rotate, presumably due to the large number of update
				// notifications caused by rotation. It does not seem to affect behaviour though.
				gm._update();
			});

			gm.sizeRef = sizeRef
			gm.observer = observer;

			observer.observe(sizeRef);
		}
		else {
			window.addEventListener('DOMContentLoaded', gm._update, {passive: true, once: true});
			window.addEventListener('load', gm._update, {passive: true, once: true});
			window.addEventListener('resize', gm._update, {passive: true});
		}

		window.addEventListener("triggerMeasurements", () => {
			gm._update();
		});
	}

	/**
	 * Stops observation
	 */
	gm.stop = function() {
		if (gm.observer) {
			gm.observer.unobserve(gm.sizeRef);
		}
	}

	/**
	 * Gather results from measurement callbacks and trigger them being applied to DOM
	 */
	gm._measure = function() {
		if (!gm.hasCallbacks) {
			return;
		}

		gm.currentResults = Array.from(gm.callbacks).map(cb => cb());
	}

	/**
	 * Sets measurement results on <html> as CSS custom props
	 */
	gm._applyMeasurements = function() {
		const results = gm.currentResults;
		if (!results || !results.length) {
			return;
		}

		results
			.flat()
			.forEach(results => {

				let { prop, value } = results;

				if (
					prop &&
					(value || value === 0) // allow 0 as a value
				) {
					document.documentElement.style.setProperty(`--${prop}`, value);
				}
			});
	}

	/**
	 * force a measurement to take place immediately
	 */
	gm.forceImmediateUpdate = function() {
		gm._measure();
	}

	/**
	 * schedule a measurement to be taken. Uses requestAnimationFrame
	 */
	gm._update = function() {
		// RAF as this may cause layout shift
		requestAnimationFrame(() => {
			gm._measure();
			gm._applyMeasurements();
		});
	},

	/**
	 * Add a callback to run at measurement time.
	 *
	 * Should return an object (or array of objects) with the signature:
	 *   {prop: 'masthead', value: '128px'}
	 * which will result in the following being added to <html>:
	 *   --masthead: 128px;
	 *
	 * @param function callback
	 *
	 */
	gm.add = function(callback) {
		if (callback) {
			gm.callbacks.add(callback);
			gm.hasCallbacks = !!gm.callbacks.size;
		}
	}

	/**
	 * Remove a callback. Must be === to the callback originally added
	 *
	 *
	 * @param function callback
	 * @return boolean
	 */
	gm.remove = function(callback) {
		if (callback) {
			gm.callbacks.delete(callback);
			gm.hasCallbacks = !!gm.callbacks.size;
		}
	}

	return gm;
})();


// Standard measurements
const measurements = {

	viewport() {
		return [
			{
				prop: 'vp-height',
				value: document.documentElement.clientHeight + 'px',
			},
			{
				prop: 'vp-width',
				value: document.documentElement.clientWidth + 'px',
			},
		]
	},

	visualViewport() {
		return [
			{
				prop: 'vvp-height',
				value: window.visualViewport.height + 'px',
			},
			{
				prop: 'vvp-width',
				value: window.visualViewport.width + 'px',
			},
			{
				prop: 'vvp-scale',
				value: window.visualViewport.scale + 'px',
			},
		];
	},

	masthead() {
		const masthead = document.querySelector('.masthead');
		return {
			prop: 'masthead-height',
			value: masthead ? masthead.clientHeight + 'px' : false,
		}
	},

	pageFooter() {
		const pageFooter = document.querySelector('.page-footer');
		return {
			prop: 'page-footer-height',
			value: pageFooter ? pageFooter.clientHeight + 'px' : false,
		}
	},

	footerSubscribe() {
		const pageFooter = document.querySelector('.footer__subscribe');
		return {
			prop: 'footer-subscribe-height',
			value: pageFooter ? pageFooter.clientHeight + 'px' : false,
		}
	},

	scrollbars() {

		const body = document.body;
		let bodyBar = body.offsetWidth - body.clientWidth;
		if (bodyBar !== 0) {
			bodyBar = bodyBar + 'px';
		}

		let scrollbarRef = document.querySelector('.scrollbarRef');

		if (!scrollbarRef) {
			// Create reference element
			const ref = document.createElement('div');
			ref.style.width = '100px';
			ref.style.position = 'absolute';
			ref.style.left = '-1000vw';
			ref.style.height = '1px';
			ref.style.overflow = 'scroll';
			ref.classList.add('scrollbarRef');
			document.body.appendChild(ref);
			scrollbarRef = ref;
		}


		let browserBar = scrollbarRef.offsetWidth - scrollbarRef.clientWidth;
		if (browserBar < 0) {
			browserBar = 0;
		}

		if (browserBar !== 0) {
			browserBar = browserBar + 'px';
		}


		return [
			{
				prop: 'scrollbar',
				value: browserBar,
			},
			{
				prop: 'body-scrollbar',
				value: bodyBar,
			},

		]
	},
}


GlobalMeasurements.add(measurements.viewport);
GlobalMeasurements.add(measurements.masthead);
GlobalMeasurements.add(measurements.scrollbars);
GlobalMeasurements.add(measurements.pageFooter);
GlobalMeasurements.add(measurements.footerSubscribe);
// GlobalMeasurements.add(measurements.visualViewport);



export default GlobalMeasurements;
