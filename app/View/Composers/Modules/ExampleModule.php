<?php

namespace App\View\Composers\Modules;

use Roots\Acorn\View\Composer;
use App\Bone\PageBuilder;

class ExampleModule extends Composer
{
    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function override()
    {
		$module_options = PageBuilder::computeModuleOptions($this->data, [
			'anchor_id' => uniqid('example-module-'),
		]);

		$overrides = $module_options;

		// add other modifications here

		return $overrides;
    }
}
