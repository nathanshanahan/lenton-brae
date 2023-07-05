<?php

namespace App;


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
function create_html_element(string $type, array $atts = [], string $inner_content = '') {
    if (!$type) {
        // throw??
        return '';
    }

    $atts_string = atts_list_to_string($atts);
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
function atts_list_to_string($atts) {
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
 * Tries to extract an id from a post object, post array, numeric string or plain post id
 *
 * @return integer
 */
function post_id($post, $use_fallback = true) {
    if (empty($post)) {
        return $use_fallback ? get_the_ID() : 0;
    }

    $type = gettype($post);
    switch($type)
    {
        case 'integer':
            return $post;
            break;
        case 'object':
            return $post->ID ?? false;
            break;
        case 'array':
            // return $post['ID'] ?? false;
        return $post['id'] ?? false;
            break;
        case 'string':
            return is_numeric($post) ? intval($post) : false;
            break;
        default:
            return 0;
    }
}

/**
 * Appends an html class to an existing string, adding a separator if necessary
 *
 * @return string
 */
function append_html_class(string $cls, ?string $existing = '') {
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
