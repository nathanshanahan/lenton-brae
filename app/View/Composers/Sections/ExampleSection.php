<?php

namespace App\View\Composers\Sections;

use Roots\Acorn\View\Composer;
use App\Bone\PageBuilder;

class ExampleSection extends Composer
{
    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function override()
    {
		$section_options = PageBuilder::computeSectionOptions($this->data, [
			'anchor_id' => uniqid('example-section-'),
		]);

		$overrides = $section_options;

		// add other modifications here

		return $overrides;
    }
}
