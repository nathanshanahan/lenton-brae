<?php

/**
 * Accepts the main arg $buttons, and arbitrary args to be added as HTML attributes
 * on the wrapper <div>
 *
 * @param array $args Contains 'buttons' and other arbitrary attributes.
 */

$buttons = $args['buttons'] ?? '';
if (empty($buttons)) {
	return;
}

$extra_args = array_diff_key($args, ['buttons' => '']);

?>

<div <?= atts_to_str($extra_args) ?>>

	<?php if (!empty($buttons) && is_array($buttons)) : ?>
		<p class="buttons-container">
			<?php foreach ($buttons as $button) : ?>
				<?php if ($button['button_action'] == 'link' && !empty($button['link_url']['url']) && !empty($button['button_label'])) : ?>
					<a class="btn" href="<?= $button['link_url']['url']; ?>" target="<?= $button['link_url']['target']; ?>" data-style="<?= $button['button_style']; ?>" data-text="<?= $button['button_label']; ?>">
						<?php if ($button['button_style'] === 'naked-arrow-left') : ?>
							<svg class="button-icon" height="9" viewBox="0 0 6 9">
								<use xlink:href="#chevron-left"></use>
							</svg>
						<?php endif; ?>
						<span>
							<?= $button['button_label']; ?>
						</span>
						<?php if ($button['button_style'] === 'naked-arrow-right') : ?>
							<svg class="button-icon" height="9" viewBox="0 0 6 9">
								<use xlink:href="#chevron-right"></use>
							</svg>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			<?php endforeach; ?>
		</p>
	<?php endif; ?>
</div>