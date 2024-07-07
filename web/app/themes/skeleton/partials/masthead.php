<?php

$block = 'masthead';
$menu = 'masthead-menu';

?>

<div class="<?= $block ?>">
	<div class="<?= $block ?>__layout content-lockup">
		<div class="<?= $block ?>__logo-lockup" data-reveal="up">
			<a class="<?= $block ?>__logo-link" href="<?= esc_url(home_url('/')); ?>" rel="home">
				<?php partial('partials/svg-logo');	?>
			</a>
		</div>

		<div class="<?= $block ?>__nav-lockup">
			<nav class=" <?= $menu ?> <?= $block ?>__menu">
				<?php if (has_nav_menu('primary_navigation')) : ?>
					<?php wp_nav_menu([
						'theme_location' => 'primary_navigation',
						'menu_class' => $menu,
						'menu_id' => $menu,
						'container_class' => "{$menu}-lockup",
						'item_spacing' => 'discard',
						'depth' => 1,
					]); ?>
				<?php endif ?>
			</nav>

			<span>
				<hr>
			</span>

			<div class="<?= $block ?>__cart">
				<a class="<?= $block ?>__cart-link" href="#">
					<svg class="<?= $block . '__button-icon-svg'; ?>" width="16" height="14" viewBox="0 0 16 14" fill="none">
						<use xlink:href="#cart-icon"></use>
					</svg>
					<span id="cart-item-count" class="<?= $block ?>__item-count type-style-small">1 Item</span> -
					<span id="cart-total" class="<?= $block ?>__cart-total type-style-small">$29.95</span>
				</a>
			</div>

		</div>
	</div>
</div>