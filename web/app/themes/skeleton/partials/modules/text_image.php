<?php
$block = 'm-text-image-collage';

$atts = \App\PageBuilder::computeModuleOptions($args);
$atts['class'] = prepend_class("module $block", $atts['class'] ?? '');
$content = $args['content'] ?? '';
$primary_media = $args['primary_media'] ?? '';
$is_set_primary_media = \App\validateMediaComponent($primary_media['media']);
$secondary_media = $args['secondary_media'] ?? '';
$is_set_secondary_media = \App\validateMediaComponent($secondary_media['media']);
$atts['data-layout'] = $args['layout'] ?? 'left';
?>

<section <?= atts_to_str($atts) ?>>
	<div class="content-lockup <?= $block ?>__content-lockup">
		<?php if (!empty($content['heading'])) : ?>
			<header class="<?= $block ?>__header">
				<h2 class="<?= $block ?>__heading type-style-h5" data-reveal="up">
					<?= $content['heading'] ?>
				</h2>
			</header>
		<?php endif; ?>

		<div class="<?= $block ?>__text-lockup">

			<?php if (!empty($content['sub_heading'])) : ?>
				<p class="<?= $block ?>__sub-heading type-style-h2" data-reveal="up">
					<?= $content['sub_heading'] ?>
				</p>
			<?php endif; ?>
			<?php if (!empty($content['content'])) :
				partial('partials/user-html', '', [
					'content' => $content['content'],
					'class' => $block . '__content',
					'data-reveal' => 'up',
				]);
			endif; ?>

		</div>
		<div class="<?= $block ?>__media-lockup">
			<div class="<?= $block ?>__primary-media <?= $block ?>__graphics-lockup graphics-lockup">
				<?php if ($is_set_primary_media) : ?>
					<?php partial('partials/media', '', [
						'media' => $primary_media['media'],
						'class' => "{$block}__primary",
						'data-reveal' => "left",
					]) ?>
				<?php endif; ?>
				<svg class="<?= $block . '__graphic-arch'; ?> graphic-arch" width="448" height="604" viewBox="0 0 448 604" fill="none">
					<use xlink:href="#graphic-arch"></use>
				</svg>
			</div>
			<?php if ($is_set_secondary_media) : ?>
				<?php partial('partials/media', '', [
					'media' => $secondary_media['media'],
					'class' => "{$block}__secondary-media",
					'data-reveal' => "up",
				]) ?>
			<?php endif; ?>
		</div>
	</div>
</section>