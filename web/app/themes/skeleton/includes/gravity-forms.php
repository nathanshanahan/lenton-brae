<?php
/************************************************************
 * TODO: move all of this to a class in the Bone folder, but
 * leave the file here for project-specific customisations.
 *************************************************************/






/**
 * Adds a funciton missing for Captcha with Swup
 */
add_action('wp_head', function() {
	?>
		<script>
			function onloadCallback()
			{
				renderRecaptcha();
			}
		</script>
	<?php
});

/**
 * Disables GravityForms Styling
 */
add_filter( 'gform_disable_css', '__return_true' ); // disables _all_ frontend css, added GF 2.8,
add_filter( 'gform_init_scripts_footer', '__return_true' );

/**
 * Adds all Gravity Form scripts to all pages - needed for swup
 */
add_action('wp_head', function () {
	if( !class_exists('GFAPI') )
	{
		return;
	}

	// Add gravity scripts on load to accomodate swup
	$forms = \GFAPI::get_forms();

	foreach ($forms as $form)
	{
		gravity_form_enqueue_scripts( $form['id'], true );
	}
});

/**
 * Used with the above to force JS output on pages
 */
add_filter( 'gform_force_hooks_js_output', '__return_true' );



/**
 * Removes the <style> tag on each form when a theme is selected. Usually filled with
 * css variables that we aren't using.
 */
function gform_remove_inline_style($form_string, $form) {
	$open_tag = '<style>';
	$close_tag = '</style>';
	$start = strpos($form_string, $open_tag);
	if (!$start) {
		return $form_string;
	}

	// find end, starting from the end of the open_tag
	$end = strpos($form_string, $close_tag, $start + strlen($open_tag));
	if (!$end) {
		return $form_string;
	}
	// account for the length of the closing tag
	$end = $end + strlen($close_tag);

	$form_string = substr_replace($form_string, '', $start, $end - $start);

	return $form_string;
}
add_filter( 'gform_get_form_filter', __NAMESPACE__.'\\gform_remove_inline_style', 10, 2 );



/**
 * IMPORTANT!: this is currently breaking conditional fields and possibly other things, as
 * there are also inline scripts that get generated with ID's hardcoded in them.
 * --
 *
 * Gives each Gravity Form a unique ID to allow multiple instances of the same form on a page.
 *
 * Implementation taken from the multiple-gf-form-on-single-page plugin which is no longer
 * actively developed.
 *
 * https://wordpress.com/plugins/multiple-gf-form-on-single-page
 *
 */
function gform_unique_instance_form_ids( $form_string, $form ) {
	// if form has been submitted, use the submitted ID, otherwise generate a new unique ID
	if ( isset( $_POST['gform_random_id'] ) ) {
		$random_id = absint( $_POST['gform_random_id'] ); // Input var okay.
	} else {
		$random_id = mt_rand();
	}

	// this is where we keep our unique ID
	$hidden_field = "<input type='hidden' name='gform_field_values'";

	// define all occurences of the original form ID that wont hurt the form input
	$strings = array(
		' gform_wrapper ' => ' gform_wrapper gform_wrapper_original_id_' . $form['id'] . ' ',
		"for='choice_" => "for='choice_" . $random_id . '_',
		"id='choice_" => "id='choice_" . $random_id . '_',
		"id='gform_target_page_number_" => "id='gform_target_page_number_" . $random_id . '_',
		"id='gform_source_page_number_" => "id='gform_source_page_number_" . $random_id . '_',
		"#gform_target_page_number_"  => "#gform_target_page_number_" . $random_id . '_',
		"#gform_source_page_number_"  => "#gform_source_page_number_" . $random_id . '_',
		"id='label_" => "id='label_" . $random_id . '_',
		"'gform_wrapper_" . $form['id'] . "'" => "'gform_wrapper_" . $random_id . "'",
		"'gf_" . $form['id'] . "'" => "'gf_" . $random_id . "'",
		"'gform_" . $form['id'] . "'" => "'gform_" . $random_id . "'",
		"'gform_ajax_frame_" . $form['id'] . "'" => "'gform_ajax_frame_" . $random_id . "'",
		'#gf_' . $form['id'] . "'" => '#gf_' . $random_id . "'",
		"'gform_fields_" . $form['id'] . "'" => "'gform_fields_" . $random_id . "'",
		"id='field_" . $form['id'] . '_' => "id='field_" . $random_id . '_',
		"for='input_" . $form['id'] . '_' => "for='input_" . $random_id . '_',
		"id='input_" . $form['id'] . '_' => "id='input_" . $random_id . '_',
		"'gform_submit_button_" . $form['id'] . "'" => "'gform_submit_button_" . $random_id . "'",
		'"gf_submitting_' . $form['id'] . '"' => '"gf_submitting_' . $random_id . '"',
		"'gf_submitting_" . $form['id'] . "'" => "'gf_submitting_" . $random_id . "'",
		'#gform_ajax_frame_' . $form['id'] => '#gform_ajax_frame_' . $random_id,
		'#gform_wrapper_' . $form['id'] => '#gform_wrapper_' . $random_id,
		'#gform_' . $form['id'] => '#gform_' . $random_id,
		"trigger('gform_post_render', [" . $form['id'] => "trigger('gform_post_render', [" . $random_id,
		'gformInitSpinner( ' . $form['id'] . ', ' => 'gformInitSpinner( ' . $random_id . ', ',
		"trigger('gform_page_loaded', [" . $form['id'] => "trigger('gform_page_loaded', [" . $random_id,
		"'gform_confirmation_loaded', [" . $form['id'] . ']' => "'gform_confirmation_loaded', [" . $random_id . ']',
		'gf_apply_rules(' . $form['id'] . ', ' => 'gf_apply_rules(' . $random_id . ', ',
		'gform_confirmation_wrapper_' . $form['id'] => 'gform_confirmation_wrapper_' . $random_id,
		'gforms_confirmation_message_' . $form['id'] => 'gforms_confirmation_message_' . $random_id,
		'gform_confirmation_message_' . $form['id'] => 'gform_confirmation_message_' . $random_id,
		'if(formId == ' . $form['id'] . ')' => 'if(formId == ' . $random_id . ')',
		"window['gf_form_conditional_logic'][" . $form['id'] . ']' => "window['gf_form_conditional_logic'][" . $random_id . ']',
		"trigger('gform_post_conditional_logic', [" . $form['id'] . ', ' => "trigger('gform_post_conditional_logic', [" . $random_id . ', ',
		'gformShowPasswordStrength("input_' . $form['id'] . '_' => 'gformShowPasswordStrength("input_' . $random_id . '_',
		"gformInitChosenFields('#input_" . $form['id'] . '_' => "gformInitChosenFields('#input_" . $random_id . '_',
		"jQuery('#input_" . $form['id'] . '_' => "jQuery('#input_" . $random_id . '_',
		'gforms_calendar_icon_input_' . $form['id'] . '_' => 'gforms_calendar_icon_input_' . $random_id . '_',
		"id='ginput_base_price_" . $form['id'] . '_' => "id='ginput_base_price_" . $random_id . '_',
		"id='ginput_quantity_" . $form['id'] . '_' => "id='ginput_quantity_" . $random_id . '_',
		'gfield_price_' . $form['id'] . '_' => 'gfield_price_' . $random_id . '_',
		'gfield_quantity_' . $form['id'] . '_' => 'gfield_quantity_' . $random_id . '_',
		'gfield_product_' . $form['id'] . '_' => 'gfield_product_' . $random_id . '_',
		'ginput_total_' . $form['id'] => 'ginput_total_' . $random_id,
		'GFCalc(' . $form['id'] . ', ' => 'GFCalc(' . $random_id . ', ',
		'gf_global["number_formats"][' . $form['id'] . ']' => 'gf_global["number_formats"][' . $random_id . ']',
		'gform_next_button_' . $form['id'] . '_' => 'gform_next_button_' . $random_id . '_',
		'gform_previous_button_' . $form['id'] . '_' => 'gform_previous_button_' . $random_id . '_',
		$hidden_field => "<input type='hidden' name='gform_random_id' value='" . $random_id . "' />" . $hidden_field,
	);

	// allow addons & plugins to add additional find & replace strings
	$strings = apply_filters( 'gform_multiple_instances_strings', $strings );

	// replace all occurences with the new unique ID
	foreach ( $strings as $find => $replace ) {
		$form_string = str_replace( $find, $replace, $form_string );
	}

	return $form_string;
}
// add_filter( 'gform_get_form_filter', __NAMESPACE__.'\\gform_unique_instance_form_ids', 9999, 2 );
