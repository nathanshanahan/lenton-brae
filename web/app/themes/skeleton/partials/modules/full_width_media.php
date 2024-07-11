<?php
$block = 'm-full-width-media';

$atts = \App\PageBuilder::computeModuleOptions($args);
$atts['class'] = prepend_class("module $block", $atts['class'] ?? '');

$media = $args['media'] ?? '';
$is_set_media = \App\validateMediaComponent($media);

if (!$is_set_media) {
	return;
}

$content = $args['content'] ?? '';
$atts['data-layout'] = $args['content_position'] ?? 'default';

?>

<div <?= atts_to_str($atts) ?>>

	<div class="<?= $block ?>__media-lockup overlay-lockup">
		<?php partial('partials/media', '', [
			'media' => $media,
			'class' => "{$block}__media",
			'display' => "viewport-height",
			'data-reveal' => "up",
		]) ?>

		<?php if (!empty($content)) : ?>

			<div class="content-lockup">
				<div class="<?= $block ?>__overlay overlay">
					<div class="<?= $block ?>__overlay-content" data-color-theme="<?= $args['colour_theme'] ?>">
						<?php if (!empty($content['heading'])) : ?>
							<h2 class="type-style-h5" data-reveal="up"><?= $content['heading'] ?></h2>
						<?php endif; ?>

						<?php if (!empty($content['sub_heading'])) : ?>
							<p class="type-style-h2" data-reveal="up"><?= $content['sub_heading'] ?></p>
						<?php endif; ?>

						<?php if (!empty($content['content'])) :
							partial('partials/user-html', '', [
								'content' => $content['content'],
								'class' => $block . '__content',
								'data-reveal' => 'up',
							]);
						endif; ?>
						<?php if (!empty($content['buttons'])) :
							partial('partials/buttons', '', [
								'buttons' => $content['buttons'],
								'class' => "{$block}__buttons",
								'data-reveal' => 'up',
							]);
						endif; ?>
					</div>

				</div>
			</div>
		<?php endif; ?>
	</div>
</div>