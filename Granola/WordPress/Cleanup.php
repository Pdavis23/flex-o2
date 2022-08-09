<?php

namespace Granola\WordPress;

class Cleanup
{
    public static function init(): void
    {
        \add_action('init', [__CLASS__,'headCleanup']);
        \add_action('wp_footer', [__CLASS__, 'noEmbed']);

        // Prevent recent comments widget css being output.
        \add_filter('show_recent_comments_widget_style', '__return_false');

        // ---------------------------------------
        // Emoji Cleanup.
        // ---------------------------------------
        \add_action('init', [__CLASS__,'disableEmoji']);
        \add_filter('emoji_svg_url', '__return_false');
        \add_filter('tiny_mce_plugins', [__CLASS__, 'disableEmojiTinyMCE']);

        // Don't convert :) to an emoji image.
        \remove_filter('the_content', [__CLASS__, 'convertSmilies'], 20);

        // ---------------------------------------
        // Gutenberg duotone SVG Cleanup.
        // ---------------------------------------
        \remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
        \remove_action('in_admin_header', 'wp_global_styles_render_svg_filters');
    }

    /**
     * Deregisters unnecessary wp-embed script.
     */
    public static function noEmbed(): void
    {
        \wp_deregister_script('wp-embed');
    }

    /**
     * Remove unnecessary actions from wp_head hook.
     */
    public static function headCleanup(): void
    {
        // Remove EditURI link.
        \remove_action('wp_head', 'rsd_link');

        // Remove Windows live writer.
        \remove_action('wp_head', 'wlwmanifest_link');

        // Remove index link.
        \remove_action('wp_head', 'index_rel_link');

        // Remove previous link.
        \remove_action('wp_head', 'parent_post_rel_link', 10, 0);

        // Remove start link.
        \remove_action('wp_head', 'start_post_rel_link', 10, 0);

        // Remove links for adjacent posts.
        \remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

        // Remove WP version.
        \remove_action('wp_head', 'wp_generator');
    }

    /**
     * Remove all actions related to emojis.
     */
    public static function disableEmoji(): void
    {
        \remove_action('admin_print_styles', 'print_emoji_styles');
        \remove_action('wp_head', 'print_emoji_detection_script', 7);
        \remove_action('admin_print_scripts', 'print_emoji_detection_script');
        \remove_action('wp_print_styles', 'print_emoji_styles');
        \remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        \remove_filter('the_content_feed', 'wp_staticize_emoji');
        \remove_filter('comment_text_rss', 'wp_staticize_emoji');
    }

    /**
     * Remove wpemoji plugin from TinyMCE editor.
     *
     * @param array $plugins An array of default TinyMCE plugins.
     */
    public static function disableEmojiTinyMCE($plugins): array
    {
        if (!is_array($plugins)) {
            return [];
        }

        return array_diff($plugins, ['wpemoji']);
    }
}
