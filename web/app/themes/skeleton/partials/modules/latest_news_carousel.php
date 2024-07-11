<?php
$block = 'm-latest-news-carousel';

$atts = \App\PageBuilder::computeModuleOptions($args);
$atts['class'] = prepend_class("module $block", $atts['class'] ?? '');

// Get featured posts or fallback to latest news posts. 
$featured_posts = $args['featured_posts'] ?? '';

if (!is_array($featured_posts) || empty($featured_posts)) {
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => 4,
		'orderby' => 'menu_order',
		'order' => 'DESC'
	);
	$query = new WP_Query($args);
	$posts = $query->get_posts();
} else {
	$posts = $args['featured_posts'] ?? '';
}

?>

<section <?= atts_to_str($atts) ?>>
	<div class="<?= $block ?>__latest-lockup">
		<?php if (!empty($posts) && is_array($posts)) : ?>
			<div class="<?= $block . '__cards-swiper' ?> swiper">
				<div class="swiper-wrapper">
					<?php foreach ($posts as $post) :

						$post_type = get_post_type($post->ID);
						$post_type === 'post' ? $post_type_label = 'News' : $post_type_label = 'Events';

						$card = get_field('card', $post->ID);

						$event =  get_field('event', $post->ID) ?? '';
						$permalink = get_permalink($post->ID);

					?>
						<div class="swiper-slide" data-post-type="<?= $post_type ?>">
							<div class="overlay-lockup <?= $block ?>__overlay-lockup">
								<div class="<?= $block ?>__media-lockup">
									<?php partial('partials/media', '', [
										'media' => $card['media'], // Assuming 'media' is a custom field
										'class' => "{$block}__media",
										'display' => 'banner',
									]) ?>
								</div>
								<div class="<?= $block ?>__overlay overlay">
									<div class="content-lockup">
										<div class="<?= $block ?>__card-lockup" data-color-theme="brown">
											<h2 class="type-style-h1"><?= $post_type_label ?></h2>
											<?php if (!empty($card['title'])) : ?>
												<h3 class="type-style-h4-post"><?= $card['title'] ?></h3>
											<?php endif; ?>

											<?php if (!empty($event['event_date'])) :
												// Get the date from the ACF field
												$eventDate = $event['event_date'];

												// Convert the date from d/m/Y format to a DateTime object
												$dateTime = DateTime::createFromFormat('d/m/Y', $eventDate);

												// Format the date as 26 FEBRUARY 2023
												$formattedDate = strtoupper($dateTime->format('d F Y'));
											?>
												<p class="type-style-h4-post"><?= $formattedDate; ?></p>
											<?php endif; ?>

											<?php if (!empty($card['excerpt'])) : ?>
												<p class="<?= $block ?>__excerpt"><?= $card['excerpt'] ?></p>
											<?php endif; ?>

											<p class="<?= $block ?>__buttons-container buttons-container">
												<?php if ($post_type == 'event') : ?>
													<a class="btn" href="/events" class="type-style-h4-post" data-style="text">View Events Calendar</a>
												<?php else : ?>
													<a class="btn" href="<?= $permalink ?>" class="type-style-h4-post" data-style="text">Read More</a>
												<?php endif; ?>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="<?= $block . '__pagination-lockup' ?> content-lockup">
					<div class="<?= $block ?>__swiper-pagination"></div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>