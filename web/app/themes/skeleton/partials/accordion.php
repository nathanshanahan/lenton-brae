<?php
$part = 'p-accordion';
$accordion_rows = $args['accordion'] ?? '';
$allow_multiple = $args['allow_multiple'] ? 'true' : 'false';

?>
<?php if (!empty($accordion_rows) && is_array($accordion_rows)) : ?>
	<div class="<?= $part . '__accordion-wrapper' ?>" role="tablist" data-multiple="<?= $allow_multiple ?>">
		<?php foreach ($accordion_rows as $index => $row) : ?>
			<?php $id = uniqid(); ?>
			<span data-reveal="up">
				<div class="<?= $part . '__accordion-item' ?>">
					<button id="accordion-button-<?= $id . $index ?>" class="hover-button  <?= $part . '__accordion-header' ?>" aria-expanded="false" aria-controls="accordion-content-<?= $id . $index ?>" role="tab">
						<span class="type-style-body"><?= $row['heading'] ?></span>
						<svg class="icon-accordion" height="9" viewBox="0 0 16 9">
							<use xlink:href="#icon-accordion"></use>
						</svg>
					</button>
					<div id="accordion-content-<?= $id . $index ?>" class="<?= $part . '__accordion-content' ?>" role="tabpanel" aria-labelledby="accordion-button-<?= $id . $index ?>" aria-hidden="true">
						<div class="<?= $part . '__row-inner' ?>">
							<?php if ($row['type'] == 'text' && !empty($row['content'])) : ?>
								<div class="<?= $part . '__text-content' ?>">
									<?php partial('partials/user-html', '', [
										'content' => $row['content'],
										'class' => "{$part}__content"
									]) ?>

									<?php
									partial('partials/buttons', '', [
										'buttons' => $row['buttons']['buttons'],
										'class' => "{$part}__buttons"
									]) ?>
								</div>

							<?php elseif ($row['type'] == 'media' && !empty($row['media'])) : ?>
								<div class="<?= $part . '__media-content' ?>">
									<?php partial('partials/media', '', [
										'media' => $row['media']['media'],
										'class' => "{$part}__media"
									]) ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</span>
		<?php endforeach; ?>
	</div>
<?php endif; ?>