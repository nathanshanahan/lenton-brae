export function watchForReveals() {
	if (!'IntersectionObserver' in window) return;

	const config = {
		root: null,
		rootMargin: '0px 0px -10% 0px',
		// threshold: 0.1,
	};

	let observer = new IntersectionObserver(handleIntersection, config);

	const instances = document.querySelectorAll('[data-reveal]');
	for (const instance of instances) {
		prepareReveal(instance);
		observer.observe(instance);
	}
}

function handleIntersection(entries, observer) {
	for (const entry of entries) {
		if (entry.isIntersecting) {
			reveal(entry.target);
		}
	}
}

function prepareReveal(element) {
	if (!element) return;

	element.classList.add('reveal-waiting');
}

function reveal(element) {
	if (!element) return;

	element.classList.remove('reveal-waiting');
	element.classList.add('revealed');
}

