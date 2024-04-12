<?php

$block = 'masthead';
$menu = 'masthead-menu';

?>

<div class="<?= $block ?> <?= $block ?>--sticky">
	<div class="<?= $block ?>__layout">
		<div class="<?= $block ?>__logo-lockup">
			<a class="<?= $block ?>__logo-link" href="<?= esc_url(home_url('/')); ?>" rel="home">
				<svg class="<?= $block ?>__logo" height="60" viewBox="0 0 40 40">
					<use xlink:href="#placeholder-logo-change-me"></use>
				</svg>
			</a>
		</div>


		<div class="<?= $menu ?> <?= $block ?>__menu">
			<?php if (has_nav_menu('primary_navigation')) : ?>
				<?php wp_nav_menu([
					'theme_location' => 'primary_navigation',
					'menu_class' => $menu,
					'menu_id' => $menu,
					'container_class' => "{$menu}-lockup",
					'item_spacing' => 'discard',
				]); ?>
			<?php endif ?>
		</div>
	</div>
</div>
