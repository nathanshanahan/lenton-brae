export function initLoadMore() {

	let page = 1;
	const loadMoreButton = document.querySelector('#load-more-posts');

	loadMoreButton.addEventListener('click', function () {
		loadMoreButton.disabled = true;
		loadMoreButton.innerText = 'Loading...';

		const featuredPostIds = Array.from(document.querySelectorAll('.m-news-archive__featured-posts-lockup [data-post-id]'))
			.map(el => el.getAttribute('data-post-id'));

		const data = new FormData();
		data.append('action', 'load_more');
		data.append('page', page);
		data.append('featured_post_ids', JSON.stringify(featuredPostIds));

		fetch(load_more_params.ajaxurl, {
			method: 'POST',
			body: data
		})
			.then(response => response.text())
			.then(data => {
				if (data) {
					document.querySelector('.m-news-archive__posts-lockup').insertAdjacentHTML('beforeend', data);
					page++;
					loadMoreButton.disabled = false;
					loadMoreButton.innerText = 'More News';
				} else {
					loadMoreButton.innerText = 'All posts loaded';
					loadMoreButton.disabled = true;
				}
			})
			.catch(error => console.error('Error:', error));
	});

}

