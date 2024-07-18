<?php
$block = 'm-news-archive';

$atts = \App\PageBuilder::computeModuleOptions($args);
$atts['class'] = prepend_class("module $block", $atts['class'] ?? '');

// Get featured posts or fallback to latest news posts.
$featured_posts = $args['featured_posts'] ?? '';

// Check if featured posts exist and get their IDs
$featured_post_ids = [];
if ($featured_posts) {
	foreach ($featured_posts as $featured_post) {
		$featured_post_ids[] = $featured_post->ID;
	}
}

$args = array(
	'post_type' => 'post',
	'posts_per_page' => 9,
	'orderby' => 'menu_order',
	'order' => 'DESC',
	'post__not_in' => $featured_post_ids // Exclude featured posts
);
$query = new WP_Query($args);
$posts = $query->get_posts();

$core_pages = get_field('core_pages', 'option');

?>

<section <?= atts_to_str($atts) ?>>
	<header class="<?= $block ?>__feed-selection content-lockup">
		<ul>
			<li>
				<a href="<?= $core_pages['news_archive'] ?>" class="active type-style-h5-bold">News</a>
			</li>
			<li>
				<a href="<?= $core_pages['events_archive'] ?>" class="type-style-h5-bold">Events</a>
			</li>
		</ul>
	</header>

	<?php if (!empty($featured_posts) && is_array($featured_posts)) : ?>
		<div class="<?= $block ?>__featured-posts-lockup content-lockup">

			<?php foreach ($featured_posts as $index => $featured_post) : ?>
				<?php
				partial('partials/card-featured', '', [
					'post' => $featured_post,
					'index' => $index,
					'attributes' => [
						'data-post-id' => $featured_post->ID
					]
				]) ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if (!empty($posts) && is_array($posts)) : ?>
		<div class="<?= $block ?>__posts-lockup content-lockup">

			<?php foreach ($posts as $index => $post) : ?>
				<?php
				partial('partials/card-news', '', [
					'post' => $post
				]) ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="content-lockup">
		<div class="<?= $block . '__pagination-lockup' ?> ">
			<button id="load-more-posts" class="btn" data-action="load-more" data-style="pill">
				More News
			</button>
		</div>
	</div>

</section>