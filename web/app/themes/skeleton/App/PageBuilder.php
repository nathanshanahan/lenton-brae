<?php

namespace App;

class PageBuilder
{

	/**
	 * Computes Module Options into html attributes
	 */
	public static function computeModuleOptions(
		array $module,
		array $defaults = [],
		array $overrides = []
	): array {

		$atts = [];

		// apply defaults
		if (!empty($defaults) && is_array($defaults)) {
			$atts = $defaults;
		}

		if (!empty($module['anchor_id'])) {
			$atts['id'] = sanitize_title($module['anchor_id']);
		}

		if (!empty($module['custom_classes'])) {
			$atts['class'] = $module['custom_classes'];
		} else if (!isset($atts['class'])) {
			// Nicer for calling code if this always exists. Don't overwrite if already set
			$atts['class'] = '';
		}

		// Add colour theme setting as a data attribute if provided
		if (!empty($module['colour_theme'])) {
			$atts['data-color-theme'] = $module['colour_theme'];
		}

		// Add padding settings as CSS variables if provided
		$padding_variables = [];
		if (!empty($module['padding_bottom'])) {
			$padding_variables[] = '--padding-bottom: ' . $module['padding_bottom'];
		}
		if (!empty($module['padding_top'])) {
			$padding_variables[] = '--padding-top: ' . $module['padding_top'];
		}
		if (!empty($module['small_screen_padding_bottom'])) {
			$padding_variables[] = '--small-screen-padding-bottom: ' . $module['small_screen_padding_bottom'];
		}
		if (!empty($module['small_screen_padding_top'])) {
			$padding_variables[] = '--small-screen-padding-top: ' . $module['small_screen_padding_top'];
		}

		if (!empty($padding_variables)) {
			$atts['style'] = implode('; ', $padding_variables) . ';';
		}

		// apply overrides
		if (!empty($overrides) && is_array($overrides)) {
			$atts = wp_parse_args($overrides, $atts);
		}

		return $atts;
	}


	/**
	 * Computes Section Options into html attributes
	 */
	public static function computeSectionOptions(
		array $section,
		array $defaults = [],
		array $overrides = []
	): array {

		$atts = [];

		// apply defaults
		if (!empty($defaults) && is_array($defaults)) {
			$atts = $defaults;
		}

		if (!empty($section['anchor_id'])) {
			$atts['id'] = sanitize_title($section['anchor_id']);
		}

		if (!empty($section['custom_classes'])) {
			$atts['class'] = $section['custom_classes'];
		} else if (!isset($atts['class'])) {
			// Nicer for calling code if this always exists. Don't overwrite if already set
			$atts['class'] = '';
		}

		if (!empty($section['bg_color'])) {
			$atts['data-bg'] = $section['bg_color'];
		}

		// apply overrides
		if (!empty($overrides) && is_array($overrides)) {
			$atts = wp_parse_args($overrides, $atts);
		}

		return $atts;
	}
}
