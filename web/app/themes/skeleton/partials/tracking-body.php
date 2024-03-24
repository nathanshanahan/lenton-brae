<?php
[
	// 'ga' => $ga,
	'gtm' => $gtm,
	'fb_pixel' => $fb_pixel,
] = \App\SiteOptions::trackingPlatforms();

if (false && 'development' === WP_ENV) {
	$gtm['is_enabled'] = false;
	$fb_pixel['is_enabled'] = false;
}

?>


<?php if (!empty($gtm['is_enabled']) && !empty($gtm['id'])) : ?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= $gtm['id'] ?>"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
<?php endif; ?>


<?php if (!empty($fb_pixel['is_enabled']) && !empty($fb_pixel['id'])) : ?>
	<!-- Facebook Pixel Code -->
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=<?= $fb_pixel['id'] ?>&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Facebook Pixel Code -->
<?php endif; ?>
