<?php
namespace App\Bone;

class PageBuilder
{
	/**
	 * @var bool Whether or not using sections
	 */
	private $_usingSections = false;

	/**
	 * @var array stores the layouts for later use
	 */
	private $_layouts = [];

	/**
	 * PageBuilder constructor.
	 * @param $layouts ACF flexible content field
	 * @param bool $using_sections set to true if using sections or false if using modules only
	 */
	public function __construct($layouts, $using_sections = true)
	{
		$this->_usingSections = $using_sections;
		if( $this->_usingSections )
		{
			$this->_layouts = $this->_cleanseSections($layouts);
		}
		else
		{
			$this->_layouts = $this->_cleanseModules($layouts);
		}
	}

	/**
	 * Cleanses the sections field to organise the data for blade
	 *
	 * @param $sections_field
	 * @return array
	 */
	private function _cleanseSections($sections_field)
	{
		$sections = [];

		if( !is_array($sections_field) || empty($sections_field) )
		{
			//Not an array so return emtpty
			return $sections;
		}

		foreach($sections_field as $section)
		{
			if(!array_key_exists('modules', $section))
			{
				$section['modules'] = [];
			}
			$modules = $section['modules'];
			$section['modules'] = [];
			if(is_array($modules) && !empty($modules))
			{
				foreach($modules as $module)
				{
					//Apply a filter so that content can be overwritten later
					$module = apply_filters( 'page_builder_module_' . $module['acf_fc_layout'], $module, null );
					$module['name'] = $module['acf_fc_layout'];
					$section['modules'][] = [
						'name'		=> $module['acf_fc_layout'],
						'data'		=> $module
					];
				}
			}
			$section_data = apply_filters( 'page_builder_section_' . $section['acf_fc_layout'], $section, null );
			$section_data['modules'] = $section['modules'];
			$section_data['name'] = $section['acf_fc_layout'];
			$sections[] = [
				'name' => $section['acf_fc_layout'],
				'data'  => $section_data,
			];
		}
		return $sections;
	}

	/**
	 * Cleanses the modules fields for us in blade
	 *
	 * @param $modules_field
	 * @return array
	 */
	private function _cleanseModules($modules_field)
	{
		$modules = [];

		if( !is_array($modules_field) || empty($modules_field) )
		{
			//Not an array so return emtpty
			return $modules;
		}

		foreach($modules_field as $module)
		{
			//Apply a filter so that content can be overwritten later
			$module = apply_filters( 'page_builder_module_' . $module['acf_fc_layout'], $module, null );
			$module['name'] = $module['acf_fc_layout'];
			$modules[] = [
				'name'		=> $module['acf_fc_layout'],
				'data'		=> $module
			];
		}

		return $modules;
	}

	/**
	 * Returns the organised layouts with the type of page builder being used
	 *
	 * @return array
	 */
	public function layouts()
	{
		//Set the type and arrays
		$type = 'modules';
		$sections = [];
		$modules = [];

		if( $this->_usingSections )
		{
			$type = 'sections';
			$sections = $this->_layouts;
		}
		else
		{
			$modules = $this->_layouts;
		}

		return [
			'type'	=> $type,
			'sections'	=> $sections,
			'modules'	=> $modules,
		];
	}


	/**
	 * computeModuleOptions
	 *
	 * Parses a Fluent data object for the options found in the 'module Options' ACF fields
	 * and computes them for use with module.blade templates. Returns the computed values only.
	 *
	 * @param Illuminate\Support\Fluent $module_data
	 * @return array
	 */
	public static function computeModuleOptions($data, $overrides = []) {
		if (empty($data)) {
			return [];
		}

		if (!is_array($overrides)) {
			$overrides = [];
		}

		// vars for html class and other attributes to be applied to the module's outer wrapper
		// initialise if necessary
		$options['module_computed_classes'] = $overrides['module_computed_classes'] ?? '';
		$options['module_computed_attributes'] = $overrides['module_computed_attributes'] ?? '';

		// 'block' in the sense used by BEM naming scheme
		$layout = $data->get('acf_fc_layout');
		$block = $overrides['block'] ?? "m-{$layout}";
		$options['block'] = $block;

		// add to computed classes
		$options['module_computed_classes'] .= $options['module_computed_classes'] ? " $block" : $block;

		$atts = [];

		$anchor_id = $overrides['anchor_id'] ?? $data->get('anchor_id');
		if (!empty($anchor_id)) {
		    $atts['id'] = sanitize_title($anchor_id);
		}

		$options['module_attributes'] = $atts;
		$options['module_computed_attributes'] = \App\atts_list_to_string($atts);

		return $options;
	}


	/**
	 * computeSectionOptions
	 *
	 * Parses a Fluent data object for the options found in the 'module Options' ACF fields
	 * and computes them for use with module.blade templates. Returns the computed values only.
	 *
	 * @param Illuminate\Support\Fluent $data
	 * @return array
	 */
	public static function computeSectionOptions($data, $overrides = []) {
		if (empty($data)) {
			return [];
		}

		if (!is_array($overrides)) {
			$overrides = [];
		}

		// vars for html class and other attributes to be applied to the module's outer wrapper
		// initialise if necessary
		$options['section_computed_classes'] = $overrides['section_computed_classes'] ?? '';
		$options['section_computed_attributes'] = $overrides['section_computed_attributes'] ?? '';

		// 'block' in the sense used by BEM naming scheme
		$layout = $data->get('acf_fc_layout');
		$block = $overrides['block'] ?? "s-{$layout}";
		$options['block'] = $block;

		// add to computed classes
		$options['section_computed_classes'] .= $options['section_computed_classes'] ? " $block" : $block;

		$atts = [];

		$anchor_id = $overrides['anchor_id'] ?? $data->get('anchor_id');
		if (!empty($anchor_id)) {
		    $atts['id'] = sanitize_title($anchor_id);
		}

		$options['section_attributes'] = $atts;
		$options['section_computed_attributes'] = \App\atts_list_to_string($atts);

		return $options;
	}
}
