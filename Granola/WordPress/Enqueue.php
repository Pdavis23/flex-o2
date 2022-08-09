<?php

namespace Granola\WordPress;

class Enqueue
{
    public static function init(): void
    {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueueMainAssets']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueueCommentAssets']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueueAdminAssets']);
        add_action('enqueue_block_editor_assets', [__CLASS__, 'enqueueEditorAssets']);

        add_action('wp_enqueue_scripts', [__CLASS__, 'dequeueWPGlobalStyles']);

        add_action('wp_default_scripts', [__CLASS__, 'movejQueryToFooter']);
        add_filter('wp_default_scripts', [__CLASS__, 'removejQueryMigrate']);

        add_filter('style_loader_src', [__CLASS__, 'removeAssetVersion'], 10, 2);
        add_filter('script_loader_src', [__CLASS__, 'removeAssetVersion'], 10, 2);

        add_filter('granola/scripts/dependencies', [__CLASS__, 'addjQueryDependency']);
        add_filter('granola/scripts/localization', [__CLASS__, 'addAjaxLocalization']);
    }

    /**
     * Enqueue Granola block editor assets.
     */
    public static function enqueueEditorAssets(): void
    {
        // ------------------------------------------
        // Editor Scripts
        // ------------------------------------------
        wp_enqueue_script(
            'granola-editor',
            \Granola\Asset::URL('editor-scripts.js', true),
            ['wp-blocks', 'wp-dom'],
            '',
            true
        );
    }

    /**
     * Enqueue Granola admin assets.
     */
    public static function enqueueAdminAssets(): void
    {
        // ------------------------------------------
        // Admin Scripts
        // ------------------------------------------
        wp_enqueue_script(
            'granola-admin',
            \Granola\Asset::URL('admin-scripts.js', true),
            [],
            '',
            true
        );
    }

    /**
     * Remove file version query argument from all enqueued styles and scripts.
     *
     * @param string $src The source URL of the enqueued asset.
     * @return string The filtered URL of the enqueued asset.
     */
    public static function removeAssetVersion(string $src): string
    {
        if (strpos($src, '?ver=')) {
            $src = remove_query_arg('ver', $src);
        }

        return $src;
    }

    /**
     * Enqueue all main Granola assets - styles & scripts
     */
    public static function enqueueMainAssets(): void
    {
        // ------------------------------------------
        // Enqueue Granola CSS
        // ------------------------------------------
        wp_enqueue_style(
            'granola-styles',
            \Granola\Asset::URL('main.css', true),
            apply_filters('granola/styles/dependencies', []),
            false
        );


        // ------------------------------------------
        // Enqueue Granola Print CSS
        // ------------------------------------------
        // wp_enqueue_style(
        //     'granola-print',
        //     \Granola\Asset::URL('print.css', true),
        //     apply_filters('granola/print/dependencies', []),
        //     false,
        //     'print'
        // );

        // ------------------------------------------
        // Register Granola JS
        // ------------------------------------------
        wp_register_script(
            'granola-scripts',
            \Granola\Asset::URL('main.js', true),
            apply_filters('granola/scripts/dependencies', []),
            '',
            true
        );

        // ------------------------------------------
        // Define Granola JS localization.
        // Allows passing PHP variables to JS.
        // ------------------------------------------
        wp_localize_script(
            'granola-scripts',
            'params',
            apply_filters('granola/scripts/localization', [])
        );

        // ------------------------------------------
        // Enqueue Granola JS
        // ------------------------------------------
        wp_enqueue_script('granola-scripts');
    }

    /**
     * Conditionally enqueue WP comment-reply JS.
     */
    public static function enqueueCommentAssets(): void
    {
        if (\Granola\WordPress\Comments::enqueueReplyScript()) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Conditionally dequeue WP's core block styling.
     *
     * Dequeues: /wp-includes/css/dist/block-library/style.min.css
     */
    public static function dequeueWPGlobalStyles(): void
    {
        if (defined('GRANOLA_REMOVE_WP_GLOBAL_STYLES') && GRANOLA_REMOVE_WP_GLOBAL_STYLES === true) {
            wp_dequeue_style('global-styles');
        }
    }

    /**
     * Removes the jQuery Migrate script bundled in WordPress core.
     */
    public static function removejQueryMigrate(&$scripts): void
    {
        if (is_admin()) {
            return;
        }

        if (defined('GRANOLA_REMOVE_JQUERY_MIGRATE') && GRANOLA_REMOVE_JQUERY_MIGRATE === true) {
            $scripts->remove('jquery');
            $scripts->add('jquery', false, array('jquery-core'), '1.12.4');
        }
    }

    /**
     * Moves jQuery to the footer unless it's required in the header.
     *
     * Places jQuery <script> in the footer by default. However, if a plugin requires it in
     * the header, it will automatically be moved there.
     *
     * @link https://wordpress.stackexchange.com/questions/173601/enqueue-core-jquery-in-the-footer/240612#240612
     */
    public static function movejQueryToFooter($wp_scripts): void
    {
        if (is_admin()) {
            return;
        }

        if (defined('GRANOLA_JQUERY_IN_FOOTER') && GRANOLA_JQUERY_IN_FOOTER === true) {
            $wp_scripts->add_data('jquery', 'group', 1);
            $wp_scripts->add_data('jquery-core', 'group', 1);
        }
    }

    /**
     * Adds AJAX object properties to granola-scripts via localization if required via config.
     *
     * @link https://developer.wordpress.org/reference/functions/wp_localize_script/
     *
     * @param array $localizations An array of 'localizations' for granola-scripts.
     * @return array The filtered array of localizations for granola-scripts, with AJAX values conditionally added.
     */
    public static function addAjaxLocalization($localizations): array
    {
        if (defined('GRANOLA_AJAX_REQUIRED') && GRANOLA_AJAX_REQUIRED === true) {
            $localizations['ajax_url'] = admin_url('admin-ajax.php');
            $localizations['home_url'] = home_url();
        }

        return $localizations;
    }

    /**
     * Adds jQuery as a dependancy to granola-scripts if required via config.
     *
     * @param array $dependencies An array of dependencies for granola-scripts.
     * @return array The filtered array of dependencies for granola-scripts, with jQuery conditionally added.
     */
    public static function addjQueryDependency($dependencies): array
    {
        if (defined('GRANOLA_JQUERY_REQUIRED') && GRANOLA_JQUERY_REQUIRED === true) {
            $dependencies[] = 'jquery';
        }

        return $dependencies;
    }
}
