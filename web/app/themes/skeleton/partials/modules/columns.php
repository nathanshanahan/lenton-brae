<?php
$block = 'm-columns';

// Compute module options and prepend class
$atts = \App\PageBuilder::computeModuleOptions($args);
$atts['class'] = prepend_class("module $block", $atts['class'] ?? '');

// Get columns data
$columns = $args['columns'] ?? '';
?>

<section <?= atts_to_str($atts) ?>>
	<div class="content-lockup grid-container <?= $block . '__grid-container' ?>">
		<?php if (!empty($columns) && is_array($columns)) : ?>
			<?php foreach ($columns as $column) : ?>

				<?php if ($column['type'] == 'text' && (!empty($column['content']) || !empty($column['buttons']))) : ?>
					<div class="<?= $block . '__column ' . $block . '__column--' . $column['type'] ?>" style="--column-count: <?= $column['width']; ?>; --column-offset: <?= $column['offset']; ?>" data-reveal="up">

						<?php
						if (!empty($column['heading'])) : ?>
							<h2 class="<?= $block ?>__heading type-style-h5" data-reveal="up">
								<?= $column['heading'] ?>
							</h2>
						<?php endif; ?>

						<?php if (!empty($column['sub_heading'])) : ?>
							<p class="<?= $block ?>__sub-heading type-style-h2" data-featured data-reveal="up">
								<?= $column['sub_heading'] ?>
							</p>
						<?php endif; ?>

						<?php partial('partials/user-html', '', [
							'content' => $column['content'],
							'class' => "{$block}__content"
						]) ?>
						<?php
						partial('partials/buttons', '', [
							'buttons' => $column['buttons']['buttons'],
							'class' => "{$block}__buttons"
						]) ?>
					</div>
				<?php elseif ($column['type'] == 'media' && !empty($column['media'])) : ?>
					<div class="<?= $block . '__column ' . $block . '__column--' . $column['type'] ?>" style="--column-count: <?= $column['width']; ?>; --column-offset: <?= $column['offset']; ?>" data-reveal="up">
						<?php partial('partials/media', '', [
							'media' => $column['media']['media'],
							'class' => "{$block}__media"
						]) ?>
					</div>
				<?php elseif ($column['type'] == 'accordion' && !empty($column['accordion'])) : ?>
					<div class="<?= $block . '__column ' . $block . '__column--' . $column['type'] ?>" style="--column-count: <?= $column['width']; ?>; --column-offset: <?= $column['offset']; ?>" data-reveal="up">
						<?php partial('partials/accordion', '', [
							'accordion' => $column['accordion'],
							'allow_multiple' => $column['allow_multiple'],
							'class' => "{$block}__accordion"
						]) ?>
					</div>

				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</section>