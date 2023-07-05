<!doctype html>
<html <?php language_attributes(); ?>>
    @include('partials.head')

    <body <?php body_class(); ?>>
        @include('partials.tracking--body')

        <?php wp_body_open(); ?>

        @include('partials.svg-symbols')

        <?php do_action('get_header'); ?>

        @include('partials.skip-links')

        @include('partials.header')

        <div id="body-wrapper" class="body-wrapper swup-page-loader">
            <main id="main" class="">
                @yield('content')
            </main>
        </div>

        @include('partials.footer')

        <?php do_action('get_footer'); ?>
        <?php wp_footer(); ?>

        @include('utils.scripts')
    </body>
</html>
