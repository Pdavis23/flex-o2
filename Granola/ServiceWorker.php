<?php

namespace Granola;

class ServiceWorker
{
    public static function init(): void
    {
        \add_action('admin_init', [__CLASS__, 'copySwToRoot']);
        \add_action('after_switch_theme', [__CLASS__, 'createOfflinePage']);
        \add_action('wp_enqueue_scripts', [__CLASS__, 'swRegistrationScripts']);
        \add_action('admin_enqueue_scripts', [__CLASS__, 'swRegistrationScripts']);
    }

    public static function copySwToRoot(): void
    {
        $source = \Granola\Asset::path('components/serviceworker/sw.js', true);
        $dest = get_home_path() . 'sw.js';

        copy($source, $dest);
    }

    public static function swRegistrationScripts(): void
    {
        if (!is_user_logged_in()) {
            // Register our sw. This can happen after other items have loaded.
            wp_enqueue_script(
                "sw-register",
                \Granola\Asset::URL('components/serviceworker/register.js', true),
                false,
                false,
                true,
            );
        } else {
            wp_enqueue_script(
                "sw-unregister",
                \Granola\Asset::URL('components/serviceworker/unregister.js', true),
                false,
                false,
                false,
            );
        }
    }

    /**
     * Creates an offline page for the serviceworker to cache, if one doesn't exist.
     */
    public static function createOfflinePage(): void
    {
        if (!empty(\get_page_by_path('offline'))) {
            return;
        }

        \wp_insert_post([
            'post_title'   => __('You\'re Offline', 'granola'),
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_name'    => 'offline',
            'meta_input'   => [
                'subheading' => __(
                    'It looks like you’re currently offline so some pages might not be available',
                    'granola'
                ),
            ],
        ]);
    }
}
