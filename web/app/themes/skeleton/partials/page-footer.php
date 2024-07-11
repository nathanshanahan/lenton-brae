<?php

$block = 'p-page-footer';
$newsletter = get_field('newsletter', 'options') ?? '';
$footer = get_field('footer', 'options') ?? '';
$menu = 'p-page-footer__menu';

$shortcode_atts = [
	'id' => $newsletter['form_id'],
	'title' => 'false',
	'description' => 'false',
	'ajax' => 'true',
];

$shortcode_atts = atts_to_str($shortcode_atts, '=', ' ');
?>
<div class="page-footer">


	<?php if (!empty($newsletter) && !empty($newsletter['form_id'])) : ?>
		<section class="<?= $block ?>__newsletter" data-color-theme="white">

			<div class="<?= $block ?>__newsletter-lockup content-lockup">
				<?php if (!empty($newsletter['title'])) : ?>
					<h2 class="<?= $block ?>__newsletter-title">
						<?= $newsletter['title'] ?>
					</h2>
				<?php endif; ?>


				<div class="<?= $block ?>__newsletter-content-lockup ">

					<?php if (!empty($newsletter['description'])) : ?>
						<p class="type-style-large">
							<?= $newsletter['description'] ?>
						</p>
					<?php endif; ?>

				</div>

				<div class="form-lockup">
					<?php if (!empty($newsletter['form_id']) && '0' !== $newsletter['form_id']) : ?>
						<div class="<?= $block ?>__lockup">
							<?= do_shortcode("[gravityform $shortcode_atts]"); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</section>
	<?php endif; ?>

	<footer class="<?= $block ?> <?= $block ?>__footer" data-color-theme="black">
		<div class="<?= $block . '__footer-lockup' ?> content-lockup ">

			<div class="<?= $block ?>__footer-row">
				<div class="<?= $block ?>__footer-col <?= $block ?>__footer-col--first">
					<div class="<?= $block ?>__logo-lockup" data-reveal="up">
						<a class="<?= $block ?>__logo-link" href="<?= esc_url(home_url('/')); ?>" rel="home">
							<?php partial('partials/svg-logo');	?>
						</a>
					</div>
					<address class="<?= $block ?>__address-lockup">
						<?php if (!empty($footer['address'])) : ?>
							<p class="type-style-small">
								<?php echo !empty($footer['address_maps_link']['url']) ? '<a href="' . esc_url($footer['address_maps_link']['url']) . '" target="_blank">' . esc_html($footer['address']) . '</a>' : esc_html($footer['address']); ?>
							</p>
						<?php endif; ?>

						<?php if (!empty($footer['email_address'])) : ?>
							<p class="type-style-small">
								e: <a href="mailto: <?= $footer['email_address'] ?>"> <?= $footer['email_address'] ?></a>
							</p>
						<?php endif; ?>

						<?php if (!empty($footer['phone'])) : ?>
							<p class="type-style-small">
								p: <a href="tel: <?= $footer['phone'] ?>"> <?= $footer['phone'] ?></a>
							</p>
						<?php endif; ?>
					</address>
				</div>

				<div class="<?= $block ?>__footer-col <?= $block ?>__footer-col--second">
					<div class="<?= $block ?>__social-media-lockup">
						<?php partial('partials/social_media', '', [
							'show_handle' => false,
						]);	?>

					</div>
					<?php if (!empty($footer['contact_button']['url'])) : ?>
						<p class="<?= $block ?>__buttons-container">
							<a class="btn" href="<?= $footer['contact_button']['url']; ?>" data-style="pill"><?= $footer['contact_button']['title']; ?></a>
						</p>
					<?php endif; ?>
				</div>
			</div>

			<hr>

			<div class="<?= $block ?>__footer-row">
				<?php if (!empty($footer['acknowledgement'])) : ?>
					<div class="<?= $block ?>__acknowledgement-lockup">
						<?php partial('partials/user-html', '', [
							'content' => $footer['acknowledgement'],
							'class' => $block . '__content ',
						]) ?>
					</div>
				<?php endif ?>
			</div>

			<div class="<?= $block ?>__footer-row">
				<div class="<?= $block ?>__footer-col <?= $block ?>__footer-col--first">
					<?php if (!empty($footer['copyright'])) : ?>
						<div class="<?= $block ?>copyright-lockup type-style-small">
							<?= $footer['copyright']; ?>
						</div>
					<?php endif ?>
				</div>
				<div class="<?= $block ?>__footer-col <?= $block ?>__footer-col--second">
					<?php if (has_nav_menu('footer_navigation')) : ?>
						<nav class=" <?= $menu ?> <?= $block ?>__menu">
							<?php wp_nav_menu([
								'theme_location' => 'footer_navigation',
								'menu_class' => $menu,
								'menu_id' => $menu,
								'container_class' => "{$menu}-lockup",
								'item_spacing' => 'discard',
								'depth' => 1,
							]); ?>
						</nav>
					<?php endif ?>
				</div>
			</div>
		</div>
	</footer>
</div>