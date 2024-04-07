<?php
/**
 * Pass in sections or modules to have them displayed
 *
 *
 *
 * @param sections
 * @param modules
 *
 */

$types = ['sections', 'modules'];
$default_type = 'sections';

$type = '';
$layout = [];

if (isset($args['sections'])) {
	$type = 'sections';
	$layout = $args['sections'];
}
elseif (isset($args['modules'])) {
	$type = 'modules';
	$layout = $args['modules'];
}
else {
	$type = $default_type;
	$layout = get_field($type);
}

?>

<?php if (!empty($layout) && is_array($layout)) : ?>
	<?php
	foreach ($layout as $item) :
		partial('partials/' . $type . '/' . $item['acf_fc_layout'], '', $item);
	endforeach;
	?>
<?php endif ?>
