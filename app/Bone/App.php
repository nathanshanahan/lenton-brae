<?php

namespace App\Bone;


class App
{
	/**
	 * Returns the image title tag from an ID or image field in ACF
	 *
	 * @param $image
	 * @return mixed|string
	 */
	public static function getImageTitle($image)
	{
		$title = '';
		if( null == $image )
		{
			//Empty
			return '';
		}

		if( is_array($image) )
		{
			if( array_key_exists('title', $image) )
			{
				$title = $image['title'];
			}
		}
		else if( is_integer($image) )
		{
			$title = get_the_title($image);
		}

		return $title;
	}

	/**
	 * Returns the image alt tag from an image ID or image field in ACF
	 *
	 * @param $image
	 * @return mixed|string
	 */
	public static function getImageAlt($image)
	{
		$alt = '';
		if( null == $image )
		{
			//Empty
			return '';
		}

		if( is_array($image) )
		{
			if( array_key_exists('alt', $image) )
			{
				$alt = $image['alt'];
			}
		}
		else if( is_integer($image) )
		{
			$alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
		}

		return $alt;
	}

	/**
	 * Generates an img tag with all attributes required
	 * @param $image
	 * @param string $size
	 * @param string $classes
	 * @return string
	 */
	public static function generateImgTag($image, $size = 'full', $classes = '')
	{
		$img_html = '';
		if( !empty($image) )
		{
			$image = $image['ID'];
			$title = App::getImageTitle($image);
			$alt = App::getImageAlt($image);
			$image_url = App::getImageUrlFromField($image, $size);
			$img_html = '<img class="' . $classes . '" title="'. $title . '" alt="' . $alt . '" src="' . $image_url . '" />';
		}
		return $img_html;
	}

	/**
	 * Returns a url from a field or attachment ID
	 *
	 * @param $image ID or image field
	 * @param string $size size of the image
	 * @return mixed|string|void image url or null if can't be found
	 */
	public static function getImageUrlFromField($image, $size = 'full')
	{
		$image_url = '';
		if( null == $image || empty($image) )
		{
			//Doesn't exist
			return;
		}

		if( is_array($image) && !empty($image) )
		{
			if( 'full' == $size )
			{
				$image_url = $image['url'];
			}
			else
			{
				$image_url = $image['sizes'][$size];
			}
		}
		else if( is_integer($image) )
		{
			$obj = wp_get_attachment_image_src($image, $size);
			if( !is_wp_error($obj) )
			{
				$image_url = $obj[0];
			}
		}

		return $image_url;
	}

	/**
	 * Returns the title based on teh type of page or archive
	 *
	 * @return string|void
	 */
    public static function title()
    {
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }
            return __('Latest Posts', 'sage');
        }
        if (is_archive()) {
            return get_the_archive_title();
        }
        if (is_search()) {
            return sprintf(__('Search Results for %s', 'sage'), get_search_query());
        }
        if (is_404()) {
            return __('Not Found', 'sage');
        }
        return get_the_title();
    }

	/**
	 * Outputs the HTML for GA
	 *
	 * @return string|string[]
	 */
    public static function googleAnalytics()
    {
        //Get the UA Code
        $ua_code = get_field('ga_code', 'options');
        $html = '';
        if( !empty($ua_code) )
        {
            $html = "<!-- Global site tag (gtag.js) - Google Analytics -->
                    <script async src=\"https://www.googletagmanager.com/gtag/js?id={{CODE}}\"></script>
                    <script>
                      window.dataLayer = window.dataLayer || [];
                      function gtag(){dataLayer.push(arguments);}
                      gtag('js', new Date());

                      gtag('config', '{{CODE}}');
                    </script>
                    ";
            $html = str_replace('{{CODE}}', $ua_code, $html);
        }
        return $html;
    }

	/**
	 * Outputs a HTML5 video wrapper
	 *
	 * @param null $mp4
	 * @param null $webm
	 * @param null $flv
	 * @param null $image
	 * @param bool $background_video
	 * @return false|string
	 */
	public static function GenerateVideoEmbed($mp4 = null, $webm = null, $flv = null, $image = null, $background_video = true)
	{
		$image_url = null;
		if( null != $image )
		{
			$image_url = App::getImageUrlFromField($image, 'full');
		}
		$mp4_src = $mp4;
		$webm_src = $webm;
		$flv_src = $flv;

		if( is_array($mp4) )
		{
			$mp4_src = $mp4['url'];
		}
		if( is_array($webm) )
		{
			$webm_src = $webm['url'];
		}
		if( is_array($flv) )
		{
			$flv_src = $flv['url'];
		}
		if( empty($flv_src) && empty($webm_src) && empty($mp4_src) )
		{
			return '';
		}

		ob_start();
		?>
		<video
			autoplay
			<?php if($background_video) : ?>
				muted
				loop
				playsinline
			<?php else: ?>
				controls
			<?php endif; ?>

			<?php if($image_url) : ?>
				poster="<?php echo $image_url; ?>"
			<?php endif; ?>
		>
			<?php if($mp4_src) : ?>
				<source src="<?php echo $mp4_src; ?>" type="video/mp4">
			<?php endif; ?>

			<?php if($webm_src) : ?>
				<source src="<?php echo $webm_src; ?>" type="video/webm">
			<?php endif; ?>

			<?php if($flv_src) : ?>
				<source src="<?php echo $flv_src; ?>" type="video/flv">
			<?php endif; ?>
		</video>
		<?php
		$html = ob_get_clean();
		return $html;
	}

	/**
	 * Adds the FB pixel to the body
	 * @return string
	 */
	public static function facebookPixelBody()
	{
		//Get the FB Pixel Code
		$fb_pixel = get_field('fb_pixel', 'options');
		$html = '';
		if( !empty($fb_pixel) )
		{
			$html = '<noscript><img height="1" width="1" style="display:none"
			src="https://www.facebook.com/tr?id=' . $fb_pixel . '&ev=PageView&noscript=1"
			/></noscript>
			<!-- End Facebook Pixel Code -->';
		}

		return $html;
	}

	/**
	 * Adds the head tag to the FB pixel
	 * @return string
	 */
	public static function facebookPixelHead()
	{
		//Get the FB Pixel Code
		$fb_pixel = get_field('fb_pixel', 'options');
		$html = '';
		if( !empty($fb_pixel) )
		{
			$html = "<!-- Facebook Pixel Code -->
				<script>
				!function(f,b,e,v,n,t,s)
				{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};
				if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
				n.queue=[];t=b.createElement(e);t.async=!0;
				t.src=v;s=b.getElementsByTagName(e)[0];
				s.parentNode.insertBefore(t,s)}(window, document,'script',
				'https://connect.facebook.net/en_US/fbevents.js');
				fbq('init', '" . $fb_pixel . "');
				fbq('track', 'PageView');
				</script>";
		}

		return $html;
	}

	/**
	 * Adds the GTM body tag
	 * @return string
	 */
	public static function googleTagManagerBody()
	{
		//Get the GTM Code
		$gtm_code = get_field('gtm_code', 'options');
		$html = '';
		if( !empty($gtm_code) )
		{
			$html = '<!-- Google Tag Manager (noscript) -->
			<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $gtm_code . '"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<!-- End Google Tag Manager (noscript) -->';
		}

		return $html;
	}

	/**
	 * Adds the GTM head script code
	 * @return string
	 */
	public static function googleTagManagerHead()
	{
		//Get the GTM Code
		$gtm_code = get_field('gtm_code', 'options');
		$html = '';
		if( !empty($gtm_code) )
		{
			$html = "<!-- Google Tag Manager -->
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','" . $gtm_code . "');</script>
			<!-- End Google Tag Manager -->";
		}

		return $html;
	}

	/**
	 * Outputs a HTML5 video element, with sources for various formats
	 *
	 * @param array<array> $formats [currently supports mp4 and webm]
	 * @param array $poster
	 * @param array $atts
	 * @param bool $is_background
	 * @return false|string
	 */
	public static function makeVideo($formats, $poster = false, $atts = [], $is_background = true, $is_lazy = false)
	{
		// TODO: lazy loading implementation

		if (empty($formats)) {
			return false;
		}

		$supported_formats = [
			'mp4',
			'webm',
			'hevc',
		];

		$default_atts = [
			'muted' => $is_background,
			'loop' => $is_background,
			'playsinline' => true,
			'autoplay' => $is_background,
			'controls' => !$is_background,
		];

		$atts = \wp_parse_args($atts, $default_atts);

		if (empty($atts['poster']) && !empty($poster['url'])) {
			$atts['poster'] = $poster['url'];
		}

		$sources_html = '';
		if (!empty($formats['webm']['url'])) {
			$source = \App\create_html_element('source', [
				'src' => $formats['webm']['url'],
				'type' => 'video/webm; codecs="vp8,vp9"',
			]);

			$sources_html .= $source;
		}

		if (!empty($formats['mp4']['url'])) {
			$source = \App\create_html_element('source', [
				'src' => $formats['mp4']['url'],
				'type' => 'video/mp4',
			]);

			$sources_html .= $source;
		}


		$html = \App\create_html_element('video', $atts, $sources_html);

		return $html;
	}

	/*
	 * @param $id === integer|mixed. Attachment ID, object, or array
	 * @param $size === string. Should be a valid image size
	 * @param $atts === array. Attributes to add to img tag (including src)
	 * @param $is_lazy === array. Flag if should be lazily loaded (defaults to true)
	 *
	 * @return string. Completed <img>
	 */
	public static function makeImg( $img, $size = 'full', $atts = [], $is_lazy = true ) {
		$id = \App\post_id($img);

	    $defaults = [
			'class' => '',
	    ];

	    $atts = wp_parse_args($atts, $defaults);

	    $details = wp_get_attachment_image_src( $id, $size );
	    if ( ! $details ) {
	        return '';
	    }

	    $src = $details[0];
	    $width = $details[1];
	    $height = $details[2];
	    $srcset = wp_get_attachment_image_srcset( $id, $size );

	    if ($is_lazy) {
	        $atts['data-src'] = $atts['data-src'] ?? $src;
	        $atts['data-srcset'] = $atts['data-srcset'] ?? $srcset;
	        $atts['class'] = \App\append_html_class('lazyload', $atts['class']);

			// just in case this is set
			if (isset($atts['src'])) {
				unset($atts['src']);
			}
	    }
	    else {
	        $atts['src'] = $atts['src'] ?? $src;
	        $atts['srcset'] = $atts['srcset'] ?? $srcset;
	    }

	    $atts['width'] = $width;
	    $atts['height'] = $height;
		if ($width >= $height) {
			$atts['class'] = \App\append_html_class('is-landscape', $atts['class']);
		}
		else {
			$atts['class'] = \App\append_html_class('is-portrait', $atts['class']);
		}

		// TODO: check if $img is an object/array and alt is provided, use that instead of
		// getting from post meta
	    if (!isset($atts['alt'])) {
			$empty_alt = '';
	        $alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
			$atts['alt'] = !empty($alt) ? $alt : $empty_alt;
	    }


	    $element = \App\create_html_element('img',$atts);

		return $element;
	}
}
