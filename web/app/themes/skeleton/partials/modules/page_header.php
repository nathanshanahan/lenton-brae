<?php
$block = 'm-page-header';

$atts = \App\PageBuilder::computeModuleOptions($args);
$atts['class'] = prepend_class("module $block", $atts['class'] ?? '');
$header = $args['header'] ?? '';
$page_title = $header['title'] ?? get_the_title();
$page_layout = $header['layout'] ?? 'default'; // default, overlay	
$is_set_overlay = \App\validateMediaComponent($header['media']['media']);
$atts['data-layout'] = $header['layout'] ?? 'default';;
?>

<header <?= atts_to_str($atts) ?>>

	<?php

	if ($page_layout === 'overlay' && $is_set_overlay) : ?>

		<div class="<?= $block ?>__hero-media-lockup overlay-lockup">
			<?php partial('partials/media', '', [
				'media' => $header['media']['media'],
				'class' => "{$block}__media",
				'data-reveal' => "up",
			]) ?>

			<div class="<?= $block ?>__overlay overlay">
				<div class="<?= $block ?>__header-lockup content-lockup">
					<h1 class="type-style-h1"><?= $page_title ?></h1>

					<?php if (!empty($header['content'])) : ?>
						<?php partial('partials/user-html', '', [
							'content' => $header['content'],
							'class' => $block . '__content'
						]) ?>
					<?php endif ?>
				</div>
			</div>

		</div>

	<?php else : ?>

		<div class="<?= $block ?>__header-lockup content-lockup">
			<h1 class="type-style-h1"><?= $page_title ?></h1>

			<?php if (!empty($header['content'])) : ?>
				<?php partial('partials/user-html', '', [
					'content' => $header['content'],
					'class' => $block . '__content'
				]) ?>
			<?php endif ?>
		</div>

	<?php endif; ?>

</header>