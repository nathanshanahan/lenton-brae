<?php
// $tracking = \App\SiteOptions::trackingPlatforms();
[
	'ga' => $ga,
	'gtm' => $gtm,
	'fb_pixel' => $fb_pixel,
] = \App\SiteOptions::trackingPlatforms();

if (false && 'development' === WP_ENV) {
	$ga['is_enabled'] = false;
	$gtm['is_enabled'] = false;
	$fb_pixel['is_enabled'] = false;
}

?>

<?php if (!empty($gtm['is_enabled']) && !empty($gtm['id'])) : ?>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','<?= $gtm['id'] ?>');</script>
	<!-- End Google Tag Manager -->
<?php endif; ?>

<?php if (!empty($ga['is_enabled']) && !empty($ga['id'])) : ?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $ga['id'] ?>"></script>
	<script>
		window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());
		gtag('config', '<?= $ga['id'] ?>');
	</script>
<?php endif; ?>

<?php if (!empty($fb_pixel['is_enabled']) && !empty($fb_pixel['id'])) : ?>
	<!-- Facebook Pixel Code -->
	<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '<?= $fb_pixel['id'] ?>');fbq('track', 'PageView');</script>
	<!-- End Facebook Pixel Code -->
<?php endif; ?>
