<?php
$block = 'social-media';
$social_media = get_field('social_platforms', 'option');

$instagram = $social_media['instagram'];
$facebook = $social_media['facebook'];

?>

<div class="social-media">
	<h3 class="type-style-p">
		<span class=" screen-reader-text">Follow us on Social Media</span> @lentonbrae
	</h3>

	<?php if (!empty($instagram['account_url'])) : ?>
		<a href="<?php echo $instagram['account_url']; ?>" target="_blank">
			<svg class="social-media-icon <?= $block . '__instagram-icon-svg'; ?>" width="24" height="24" viewBox="0 0 24 24" fill="none">
				<use xlink:href="#instagram-icon-svg"></use>
			</svg>
		</a>
	<?php endif; ?>

	<?php if (!empty($facebook['account_url'])) : ?>
		<a href="<?php echo $facebook['account_url']; ?>" target="_blank">
			<svg class="social-media-icon <?= $block . '__facebook-icon-svg'; ?>" width="24" height="24" viewBox="0 0 24 24" fill="none">
				<use xlink:href="#facebook-icon-svg"></use>
			</svg>
		</a>
	<?php endif; ?>


</div>