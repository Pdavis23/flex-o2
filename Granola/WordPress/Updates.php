<?php

namespace Granola\WordPress;

class Updates
{
    public static function init(): void
    {
        /**
         * Disable auto updates for themes and plugins to avoid unexpected changes
         * @link https://wordpress.org/support/article/configuring-automatic-background-updates/#plugin-theme-updates-via-filter
         */
        add_filter('auto_update_theme', '__return_false');
        add_filter('auto_update_plugin', '__return_false');

        /**
         * Disable auto-update user interface(s).
         * @link https://make.wordpress.org/core/2020/07/15/controlling-plugin-and-theme-auto-updates-ui-in-wordpress-5-5/
         */
        add_filter('themes_auto_update_enabled', '__return_false');
        add_filter('plugins_auto_update_enabled', '__return_false');

        /**
         * Explicitly disallow major & dev core updates, but allow minor/security updates.
         */
        add_filter('allow_major_auto_core_updates', '__return_false');
        add_filter('allow_minor_auto_core_updates', '__return_true');
        add_filter('allow_dev_auto_core_updates', '__return_false');
    }
}
