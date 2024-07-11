<?php
$block = 'm-home-about-us-hero';

$atts = \App\PageBuilder::computeModuleOptions($args);
$atts['class'] = prepend_class("module $block", $atts['class'] ?? '');

$hero_media = $args['hero_media']['media'] ?? '';
$hero_svg = $args['hero_svg'] ?? '';
$our_story = $args['our_story'] ?? '';
?>

<div <?= atts_to_str($atts) ?>>

	<div class="<?= $block ?>__hero-media-lockup overlay-lockup">
		<?php partial('partials/media', '', [
			'media' => $hero_media,
			'class' => "{$block}__media",
			'display' => "viewport-height",
			'data-reveal' => "up",
		]) ?>
		<?php if (!empty($hero_svg)) : ?>
			<div class="<?= $block ?>__overlay overlay">
				<div class="content-lockup">
					<img class="<?= $block ?>__hero-svg" src="<?= $hero_svg['url'] ?>" alt="<?= $hero_svg['alt'] ?>" data-reveal="right">
				</div>
			</div>
		<?php endif; ?>
	</div>


	<?php if (!empty($our_story)) : ?>
		<div class="<?= $block ?>__our-story content-lockup">

			<div class="<?= $block ?>__content-lockup">
				<?php if (!empty($our_story['sub_title'])) : ?>
					<h2 class="type-style-h5" data-reveal="up"><?= $our_story['sub_title'] ?></h2>
				<?php endif; ?>

				<?php if (!empty($our_story['title'])) : ?>
					<p class="type-style-h2" data-color="feature" data-reveal="up"><?= $our_story['title'] ?></p>
				<?php endif; ?>

				<?php if (!empty($our_story['content'])) :
					partial('partials/user-html', '', [
						'content' => $our_story['content'],
						'class' => $block . '__content',
						'data-reveal' => 'up',
					]);
				endif; ?>
				<?php if (!empty($our_story['buttons'])) :
					partial('partials/buttons', '', [
						'buttons' => $our_story['buttons'],
						'class' => "{$block}__buttons",
						'data-reveal' => 'up',
					]);
				endif; ?>
			</div>

			<div class="<?= $block ?>__graphics-lockup graphics-lockup">
				<?php if (!empty($our_story['media'])) :
					partial('partials/media', '', [
						'media' => $our_story['media'],
						'class' => "{$block}__media",
						'data-reveal' => 'left',
					]);
				endif; ?>

				<svg class="<?= $block . '__graphic-arch'; ?> graphic-arch" width="448" height="604" viewBox="0 0 448 604" fill="none">
					<use xlink:href="#graphic-arch"></use>
				</svg>

			</div>


		</div>
	<?php endif; ?>

</div>