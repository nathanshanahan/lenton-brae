<?php
$block = 'social-media';
$social_media = get_field('social_platforms', 'option');
$instagram = $social_media['instagram'];
$facebook = $social_media['facebook'];

$show_handle = $args['show_handle'] ?  $args['show_handle'] : false;

?>

<div class="social-media">
	<?php if ($show_handle) : ?>
		<h3 class="type-style-p">
			<span class=" screen-reader-text">Follow us on Social Media</span> @lentonbrae
		</h3>
	<?php endif; ?>

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