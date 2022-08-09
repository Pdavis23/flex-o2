<?php

namespace Granola\WordPress;

class Gutenberg
{
    public static function init(): void
    {
        add_action('after_setup_theme', [__CLASS__, 'gutenbergSupport']);
        add_filter('block_categories_all', [__CLASS__, 'gutenbergBlockCategory']);
    }

    public static function gutenbergSupport(): void
    {

        // Add editor styles. The path to the asset must be relative to the theme.
        add_theme_support('editor-styles');
        add_editor_style(\Granola\Paths::assetsDir() . \Granola\Asset::extract('editor-styles.css', true));

        // Add support for embeds to responsively keep their aspect ratio.
        add_theme_support('responsive-embeds');

        // Deactivate the block directory.
        remove_action('enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets');
        remove_action('enqueue_block_editor_assets', 'gutenberg_enqueue_block_editor_assets_block_directory');

        // Deactivate block patterns.
        remove_theme_support('core-block-patterns');
    }

    /**
     * Filters the Gutenberg block categories array to add a custom category.
     *
     * @link https://developer.wordpress.org/reference/hooks/block_categories/
     *
     * @param array[] $categories A list of registered block categories.
     * @return array[] The filtered list of registered block categories.
     */
    public static function gutenbergBlockCategory($categories): array
    {
        // Plugin’s block category title and slug.
        $blockCategory = [
            'title' => esc_html__('Granola Blocks', 'granola'),
            'slug' => 'granola-blocks'
        ];

        $categorySlugs = wp_list_pluck($categories, 'slug');

        // Bail early - this category slug is already registered.
        if (in_array($blockCategory['slug'], $categorySlugs, true)) {
            return $categories;
        }

        array_unshift($categories, $blockCategory);

        return $categories;
    }
}
