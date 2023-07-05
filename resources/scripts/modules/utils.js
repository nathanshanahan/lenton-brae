export function debounce(func, wait, immediate) {
	var timeout;
	return function() {
		var context = this, args = arguments;
		var later = function() {
			timeout = null;
			if (!immediate) func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) func.apply(context, args);
	};
}


export function throttle(callback, interval) {
  let enableCall = true;

  return function(...args) {
	if (!enableCall) return;

	enableCall = false;
	callback.apply(this, args);
	setTimeout(() => enableCall = true, interval);
  }
}

/**
 *
 * @param {*} candidate
 * @returns {boolean}
 */
export function isFunction(candidate) {
	return !!candidate && typeof candidate === 'function';
}


/**
 * the most terrible camelizer on the internet, guaranteed!
 * @param {string} str String that isn't camel-case, e.g., CAMeL_CaSEiS-harD
 * @return {string} String converted to camel-case, e.g., camelCaseIsHard
 */
export default str => `${str.charAt(0).toLowerCase()}${str.replace(/[\W_]/g, '|').split('|')
  .map(part => `${part.charAt(0).toUpperCase()}${part.slice(1)}`)
  .join('')
  .slice(1)}`;
