<?php


/**
 * Dump to front-end with preformatted whitespace. Can be targetted for further
 * styling with .var-dump
 *
 */
function html_var_dump($dumpable, $as_string = false) {
    ob_start();
    var_dump($dumpable);
    $contents = ob_get_clean();

    $output = "<div class='var-dump'><pre>{$contents}</pre></div>";

    if ($as_string) {
        return $output;
    }
    else {
        echo $output;
    }
}

/**
 * Generate a new html element. Accounts for empty (i.e. single-tag) elements
 *
 * @param string $type - element tag name
 * @param array $atts - key => value array of attributes to be added to the element
 * @param string $inner_content - child content. This will be ignored for empty elements
 *
 */
function make_elem(string $type, array $atts = [], string $inner_content = '') {
    if (!$type) {
        // throw??
        return '';
    }

    $atts_string = atts_to_str($atts);
    $atts_string = $atts_string ? " $atts_string" : '';

    $html = "";
    if (is_empty_element_type($type)) {
        $html = "<{$type}{$atts_string}>";
    }
    else {
        $html = "<{$type}{$atts_string}>{$inner_content}</{$type}>";
    }

    return $html;
}

/**
 * Converts a key=>value array to a string. Empty and boolean false values are skipped.
 * Boolean true values are added as standalone attributes without a value (e.g. 'hidden' => true)
 *
 */
function atts_to_str($atts) {
    $atts_string = '';
    if (!empty($atts)) {
        foreach ($atts as $prop => $val) {
            if ($val) {
                if (is_bool($val)) {
                    $atts_string .= " {$prop}";
                }
                else {
                    $atts_string .= " {$prop}=\"{$val}\"";
                }
            }
        }
    }
    return $atts_string;
}

/**
 * Checks if an element type is empty (i.e. single-tag)
 *
 */
function is_empty_element_type($type) {
    $empty_elements = [
        "area",
        "base",
        "br",
        "col",
        "command",
        "embed",
        "hr",
        "img",
        "input",
        "keygen",
        "link",
        "meta",
        "param",
        "source",
        "track",
        "wbr",
    ];

    return in_array($type, $empty_elements);
}

/**
 * Tries to extract an id from a post object, post array, numeric string or plain post id. Returns if
 * this is not possible.
 *
 * You can flag for a fallback to be used in case $post is empty
 */
function post_id(int|string|WP_Post|array $post, bool $use_fallback = true): int {
	$failure_val = 0;

    if (empty($post)) {
        return $use_fallback ? get_the_ID() : $failure_val;
    }

    $type = gettype($post);
    switch($type)
    {
        case 'integer':
            return $post;
        case 'object':
            return $post->ID ?? $failure_val;
        case 'array':
        	return $post['id'] ?? $failure_val;
        case 'string':
            return is_numeric($post) ? intval($post) : $failure_val;
        default:
            return $failure_val;
    }
}

function post_object($post, $use_fallback = false) {
	if (is_object($post) && is_a($post, 'WP_POST')) {
		return $post;
	}

	if (is_numeric($post) && is_int((int)$post)) {
		return get_post((int)$post, 'OBJECT');
	}

	if (is_array($post) && !empty($post['ID'])) {
		return get_post($post['ID'], 'OBJECT');
	}

	return null;
}

/**
 * Appends a class string to another string, using html class spacing
 */
function append_class(string $cls, ?string $existing = ''): string {
	if (empty($cls)) {
		return $existing;
	}

	$new_class;
	if ($existing) {
		$new_class = "$existing $cls";
	}
	else {
		$new_class = $cls;
	}

	return $new_class;
}

/**
 * Prepends a class string to another string, using html class spacing
 */
function prepend_class(string $cls, ?string $existing = ''): string {
	if (empty($cls)) {
		return $existing;
	}

	$new_class;
	if ($existing) {
		$new_class = "$cls $existing";
	}
	else {
		$new_class = $cls;
	}

	return $new_class;
}


/**
 * Optionally merges classes into a class string based on truthy values as flags.
 *
 * E.g. [
 * 	'layout' => true,
 * 	'layout--fancy' => false,
 * 	'grid' => true,
 * ]
 *
 * returns "layout grid"
 *
 * NOTE: $classes as a string is implemented purely for convenience of use - this function will not
 * affect a string passed in, it will just return it as-is.
 */
function compute_classes(array|string $classes = []): string {
	$is_supported_type = is_array($classes) || !is_string($classes);
	if (empty($classes) || !$is_supported_type) {
		return '';
	}

	if (is_string($classes)) {
		// nothing to do here
		return $classes;
	}

	$keep = [];
	foreach ($classes as $cls => $flag) {
		if ($flag) {
			$keep[]  = $cls;
		}
	}

	return implode(' ', $keep);
}
