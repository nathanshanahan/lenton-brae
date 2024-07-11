<?php
$block = 'm-home-latest';

$atts = \App\PageBuilder::computeModuleOptions($args);
$atts['class'] = prepend_class("module $block", $atts['class'] ?? '');

$welcome = $args['welcome'] ?? '';
$visit = $args['visit'] ?? '';
$showcase = $args['showcase'] ?? '';

?>

<section <?= atts_to_str($atts) ?>>

	<div class="<?= $block ?>__latest-lockup content-lockup">

		<div class="<?= $block ?>__social-media-lockup">
			<?php partial('partials/social_media', '', [
				'show_handle' => true,
			]);	?>
		</div>

		<?php if (!empty($welcome)) : ?>

			<div class="<?= $block ?>__welcome-media-lockup overlay-lockup">

				<?php partial('partials/media', '', [
					'media' => $welcome['media'],
					'class' => $block . '__media',
					// 'data-reveal' => "up",
				]) ?>

				<div class="<?= $block ?>__overlay overlay">
					<svg class="<?= $block . '__lenton-brae-lb-badge'; ?>" width="210" height="210" viewBox="0 0 210 210" fill="none">
						<use xlink:href="#lenton-brae-lb-badge"></use>
					</svg>
				</div>

			</div>

			<div class="<?= $block ?>__welcome-content-lockup" data-color-theme="<?= $welcome['colour_theme'] ?>">

				<?php if (!empty($welcome['title'])) : ?>
					<h2 class="type-style-h2" data-color="feature" data-reveal="up"><?= $welcome['title'] ?></h2>
				<?php endif; ?>

				<?php if (!empty($welcome['content'])) :
					partial('partials/user-html', '', [
						'content' => $welcome['content'],
						'class' => $block . '__content',
						'data-reveal' => 'up',
					]);
				endif; ?>

				<?php if (!empty($welcome['buttons'])) :
					partial('partials/buttons', '', [
						'buttons' => $welcome['buttons'],
						'class' => "{$block}__buttons",
						'data-reveal' => 'up',
					]);
				endif; ?>

			</div>

		<?php endif; ?>

		<?php if (!empty($showcase) && is_array($showcase)) : ?>
			<div class="<?= $block . '__showcase-lockup' ?>">
				<div class="<?= $block . '__showcase-swiper' ?> swiper">
					<div class="swiper-wrapper">

						<?php foreach ($showcase as $index => $row) : ?>
							<div class="swiper-slide <?= $block . '__slide' ?>" data-color-theme="<?= $row['colour_theme'] ?>" data-transition="scale-x">

								<?php if (isset($row['link']) && !empty($row['link']['url'])) : ?>

									<a class="<?= $block . '__showcase-link' ?>" href="<?= $row['link']['url'] ?>" target="<?= $row['link']['target'] ?>">

									<?php endif; ?>

									<div class="<?= $block . '__showcase-media-lockup' ?> overlay-lockup">

										<?php partial('partials/media', '', [
											'media' => $row['media'],
											'class' => "{$block}__media",
											'display' => 'natural',
										]) ?>

										<div class="<?= $block ?>__showcase-overlay overlay">
											<?php if (!empty($row['caption_highlighted']) || !empty($row['caption_regular'])) : ?>
												<p class="type-style-caption">
													<span data-color="feature"><?= $row['caption_highlighted'] ?></span> <?= $row['caption_regular'] ?>
												</p>
											<?php endif; ?>
										</div>

									</div>

									<?php if (isset($row['link']) && !empty($row['link']['url'])) : ?>

									</a>

								<?php endif; ?>

							</div>

						<?php endforeach; ?>

					</div>
				</div>

				<div class="<?= $block ?>__swiper-pagination"></div>
			</div>

		<?php endif; ?>

		<?php if (!empty($visit)) : ?>


			<div class="<?= $block ?>__visit-lockup">

				<div class="<?= $block ?>__visit-media-lockup overlay-lockup">

					<?php partial('partials/media', '', [
						'media' => $visit['media'],
						'class' => $block . '__media',
						// 'data-reveal' => "up",
					]) ?>

				</div>

				<div class="<?= $block ?>__visit-content-lockup" data-color-theme="<?= $visit['colour_theme'] ?>" data-reveal="left">

					<?php if (!empty($visit['title'])) : ?>
						<h2 class="type-style-h2" data-color="feature" data-reveal="up"><?= $visit['title'] ?></h2>
					<?php endif; ?>

					<?php if (!empty($visit['content'])) :
						partial('partials/user-html', '', [
							'content' => $visit['content'],
							'class' => $block . '__content',
							'data-reveal' => 'up',
						]);
					endif; ?>

					<?php if (!empty($visit['buttons'])) :
						partial('partials/buttons', '', [
							'buttons' => $visit['buttons'],
							'class' => "{$block}__buttons",
							'data-reveal' => 'up',
						]);
					endif; ?>

				</div>
			</div>


		<?php endif; ?>
	</div>

</section>