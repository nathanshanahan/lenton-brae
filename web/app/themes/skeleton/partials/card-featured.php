<?php
$block = 'p-card-featued';
$post = $args['post'];
$index = $args['index'];
$card = get_field('card', $post->ID);
$post_type = get_post_type($post->ID);
$post_type === 'post' ? $post_type_label = 'News Feed' : $post_type_label = 'Events';
?>
<article class="<?= $block ?>" data-index="<?= $index ?>">

	<div class="<?= $block ?>__media-lockup">
		<?php partial('partials/media', '', [
			'media' => $card['media'], // Assuming 'media' is a custom field
			'class' => "{$block}__media",
			'display' => 'card-featured',
		]) ?>
	</div>

	<div class="<?= $block ?>__content-lockup">
		<?php if ($index === 0) : ?>
			<h2 class="<?= $block ?>__post-type-heading type-style-h5"><?= $post_type_label ?></h2>
		<?php endif; ?>
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