<?php
$block = 'm-text-image';

$atts = \App\PageBuilder::computeModuleOptions($args);
$atts['class'] = prepend_class("module $block", $atts['class'] ?? '');
$content = $args['content'] ?? '';
$media = $args['media'] ?? '';
$is_set_media = \App\validateMediaComponent($media);

$atts['data-layout'] = $args['layout'] ?? 'text-first'; // text-first, media-first	
$atts['data-alignment'] = $args['alignment'] ?? 'center'; // top, center

$has_arch = $args['add_arch'] ?? false;
$arch_position = $args['arch_position'] ?? 'left'; // left, center 
?>

<section <?= atts_to_str($atts) ?>>
	<div class="content-lockup <?= $block ?>__content-lockup">

		<?php if ($has_arch) : ?>
			<div class="<?= $block ?>__graphics-lockup graphics-lockup garaphics-lockup--<?= $arch_position ?>">
			<?php endif; ?>
			<?php if ($is_set_media) : ?>
				<?php partial('partials/media', '', [
					'media' => $media,
					'class' => "{$block}__media-lockup",
					'data-reveal' => "left",
				]) ?>
			<?php endif; ?>
			<?php if ($has_arch) : ?>
				<svg class="<?= $block . '__graphic-arch'; ?> graphic-arch" width="448" height="604" viewBox="0 0 448 604" fill="none">
					<use xlink:href="#graphic-arch"></use>
				</svg>

			</div>
		<?php endif; ?>


		<div class="<?= $block ?>__text-lockup">
			<?php if (!empty($content['heading'])) : ?>
				<header class="<?= $block ?>__header">
					<h2 class="<?= $block ?>__heading type-style-h5" data-featured data-reveal="up">
						<?= $content['heading'] ?>
					</h2>
				</header>
			<?php endif; ?>

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

			<?php if (!empty($content['buttons'])) :
				partial('partials/buttons', '', [
					'buttons' => $content['buttons'],
					'class' => "{$block}__buttons",
					'data-reveal' => 'up',
				]);
			endif; ?>
		</div>
	</div>
</section>