<?php

namespace Granola\WordPress;

/**
 * Removes the default 'post' post type if the GRANOLA_DISABLE_POSTS_PT constant is true.
 */
class PostsPT
{
    public static function init(): void
    {
        if (!defined('GRANOLA_DISABLE_POSTS_PT') || GRANOLA_DISABLE_POSTS_PT !== true) {
            return;
        }

        \add_action('admin_bar_menu', [__CLASS__, 'removeDefaultPostTypeAddNew'], 80);
        \add_action('admin_menu', [__CLASS__, 'removeDefaultPostTypeMenuItem']);
        \add_action('current_screen', [__CLASS__, 'redirectPostsAdminPage']);
    }

    /**
     * Remove '+New Post' from admin bar.
     *
     * @param WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance, passed by reference.
     */
    public static function removeDefaultPostTypeAddNew($wp_admin_bar): void
    {
        $wp_admin_bar->remove_node('new-post');
    }

    /**
     * Remove 'Posts' menu item from the admin Side Menu.
     */
    public static function removeDefaultPostTypeMenuItem(): void
    {
        \remove_menu_page('edit.php');
    }

    /**
     * Redirect any user trying to access post related pages.
     *
     * @param WP_Screen $screen Current WP_Screen object.
     */
    public static function redirectPostsAdminPage($screen): void
    {
        if (
            isset($screen->base) && $screen->base === 'edit'
            && isset($screen->post_type) && $screen->post_type === 'post'
        ) {
            \wp_redirect(\admin_url());
            exit;
        }
    }
}
