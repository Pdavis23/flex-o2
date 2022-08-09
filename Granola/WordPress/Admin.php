<?php

namespace Granola\WordPress;

class Admin
{
    public static function init(): void
    {
        add_action('init', [__CLASS__,'disallowFileEdit']);
        add_action('admin_head', [__CLASS__, 'addWPAdminSubmenuGlobalFilter'], 15);
        add_action('wp_dashboard_setup', [__CLASS__,'removeDraftWidget'], 1);
        add_filter('get_user_option_admin_color', [__CLASS__, 'adminColor']);
    }

    /**
     * Prevent users editing plugin and theme files.
     *
     * Easier than looping through all defined user roles and reassigning relevant capabilities.
     *
     * @return void
     */
    public static function disallowFileEdit()
    {
        define('DISALLOW_FILE_EDIT', true);
    }

    /**
     * Filter the 'admin_color' user option when .env.js file is present (e.g. a local or development environment).
     *
     * @link https://developer.wordpress.org/reference/hooks/get_user_option_option/
     *
     * @return string The filtered admin_color option.
     */
    public static function adminColor($value)
    {
        $env = \Granola\Paths::resolve('.env.js');

        if (file_exists($env)) {
            return 'midnight';
        }

        return $value;
    }

    /**
     * Remove 'quick edit' widget.
     */
    public static function removeDraftWidget(): void
    {
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    }

    /**
     * Filters the global $submenu to allow adding custom links to the WP admin bar.
     *
     * NOTE: Adding a filter to a WP global isn't ideal. However, as there's
     * no easy way to add custom links to the (sub)menu then this approach
     * will do for now. Some enhancements to the menu API have been suggested
     * on trac (see links below), so could be good options in the future.
     *
     * @link https://core.trac.wordpress.org/ticket/12718
     * @link https://core.trac.wordpress.org/ticket/39050
     *
     * @return void
     */
    public static function addWPAdminSubmenuGlobalFilter(): void
    {
        global $submenu;

        $submenu = apply_filters('granola/wordpress/admin/submenu', $submenu);
    }
}
