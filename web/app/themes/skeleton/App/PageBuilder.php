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
		}
		else if (!isset($atts['class'])) {
			// Nicer for calling code if this always exists. Don't overwrite if already set
			$atts['class'] = '';
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
		}
		else if (!isset($atts['class'])) {
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
