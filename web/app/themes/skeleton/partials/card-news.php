<?php
$block = 'p-card-news';
$post = $args['post'];
$card = get_field('card', $post->ID);
$post_type = get_post_type($post->ID);
$post_type === 'post' ? $post_type_label = 'News Feed' : $post_type_label = 'Events';
$is_set_media = \App\validateMediaComponent($card['media']);

if ($is_set_media) {
	$card_media = $card['media'];
} else {
	$default_media = get_field('default_news_post_image', 'option');
	$card_media = $default_media['media'];
}

?>
<article class="<?= $block ?>">

	<div class="<?= $block ?>__media-lockup">
		<?php partial('partials/media', '', [
			'media' => $card_media, // Assuming 'media' is a custom field
			'class' => "{$block}__media",
			'display' => 'card-news',
		]) ?>
	</div>
	<div class="<?= $block ?>__content-lockup">
		<h3 class="<?= $block ?>__card-title type-size-h4-post"><?= $post->post_title ?></h3>
		<?php if (!empty($card['excerpt'])) : ?>
			<p class="<?= $block ?>__excerpt"><?= $card['excerpt'] ?></p>
		<?php endif; ?>
		<p class="<?= $block ?>__buttons-container buttons-container">
			<?php if ($post_type == 'event') : ?>
				<a class="btn" href="/events" class="type-style-h4-post" data-style="featured">View Events Calendar</a>
			<?php else : ?>
				<a class="btn" href="<?= $permalink ?>" class="type-style-h4-post" data-style="featured">Read More</a>
			<?php endif; ?>
		</p>
	</div>
</article>