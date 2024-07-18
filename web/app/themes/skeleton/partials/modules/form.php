<?php
$block = 'm-form';

$atts = \App\PageBuilder::computeModuleOptions($args);
$atts['class'] = prepend_class("module $block", $atts['class'] ?? '');

$form = $args['form'] ?? '';

$shortcode_atts = [
	'id' => $form['form_id'],
	'title' => 'false',
	'description' => 'false',
	'ajax' => 'true',
];

$shortcode_atts = atts_to_str($shortcode_atts, '=', ' ');

?>

<div <?= atts_to_str($atts) ?>>

	<?php if (!empty($form['form_id'])) : ?>
		<section class="<?= $block ?>__form" data-color-theme="white">

			<div class="<?= $block ?>__form-lockup content-lockup">

				<?php if (!empty($form['title'])) : ?>
					<h2 class="<?= $block ?>__form-title type-style-h3">
						<?= $form['title'] ?>
					</h2>
				<?php endif; ?>

				<div class="<?= $block ?>__form-content-lockup">

					<?php if (!empty($form['content'])) : ?>
						<p class="type-style-large">
							<?= $form['content'] ?>
						</p>
					<?php endif; ?>

				</div>

				<div class="form-lockup">
					<?php if (!empty($form['form_id']) && '0' !== $form['form_id']) : ?>
						<div class="<?= $block ?>__lockup">
							<?= do_shortcode("[gravityform $shortcode_atts]"); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</section>
	<?php endif; ?>

</div>