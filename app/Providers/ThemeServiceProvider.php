<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Roots\Acorn\Sage\SageServiceProvider;
use App\Bone\Theme\CookieNotice;
use App\Bone\Theme\Maintenance;
use App\Bone\Theme\Security;
use App\Bone\Theme\Theme;
use App\Bone\Theme\Emails;
use App\Bone\Theme\Headless;
use App\Bone\Theme\JWTHelper;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register theme services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        /**
         * Register theme support and navigation menus from the theme config.
         *
         * @return void
         */
        add_action('after_setup_theme', function (): void {
            Collection::make(config('theme.support'))
                ->map(fn ($params, $feature) => is_array($params) ? [$feature, $params] : [$params])
                ->each(fn ($params) => add_theme_support(...$params));

            Collection::make(config('theme.remove'))
                ->map(fn ($entry) => is_string($entry) ? [$entry] : $entry)
                ->each(fn ($params) => remove_theme_support(...$params));

            register_nav_menus(config('theme.menus'));

            Collection::make(config('theme.image_sizes'))
                ->each(fn ($params, $name) => add_image_size($name, ...$params));
        }, 20);

        /**
         * Register sidebars from the theme config.
         *
         * @return void
         */
        add_action('widgets_init', function (): void {
            Collection::make(config('theme.sidebar.register'))
                ->map(fn ($instance) => register_sidebar(
                    array_merge(config('theme.sidebar.config'), $instance)
                ));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->initBoneHelperClasses();
    }

    /**
     *
     */
    public function initBoneHelperClasses() {
        new Theme();
        new Security();
        new Emails();
        new Maintenance();
        new CookieNotice();
        new Headless();
        new JWTHelper();
    }
}
