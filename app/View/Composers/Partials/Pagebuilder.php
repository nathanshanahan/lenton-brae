<?php

namespace App\View\Composers\Partials;

use Roots\Acorn\View\Composer;

class Pagebuilder extends Composer
{
    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $types = ['sections', 'modules'];

        $passed_type = $this->data->get('type');
        if ($passed_type && in_array($passed_type, $types)) {
            $layout = get_field($passed_type);

            return [
                'layout' => $layout,
                'type' => $passed_type,
            ];
        }


        // Default to sections, fall back to modules
        $type = 'sections';
        $layout = get_field('sections');
        if (empty($layout)) {
            $modules = get_field('modules');
            if (!empty($modules)) {
                $layout = $modules;
                $type = 'modules';
            }
        }

        return [
            'layout' => $layout,
            'type' => $type,
        ];
    }
}
