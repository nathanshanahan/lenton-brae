<?php

namespace App\View\Composers\Modules;

use Roots\Acorn\View\Composer;

class ExampleModule extends Composer
{
    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'content' => $this->content(),
        ];
    }

	/**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function content()
    {
        return $this->data->get('content') ?? '';
    }
}
