<?php

/**
 * Registers the 'Location' custom taxonomy and handles related functionality.
 */

namespace Theme\Taxonomies;

class TestimonialType
{
    protected const SLUG = 'testimonial-type';

    public static function init(): void
    {
        \add_action('init', [__CLASS__, 'registerTaxonomy']);
        \add_filter('granola/templates/taxonomies', [__CLASS__, 'filterGranolaTemplatesTaxonomies']);
    }

    /**
     * Register Taxonomy.
     *
     * @link https://github.com/johnbillion/extended-cpts/wiki/Registering-taxonomies
     */
    public static function registerTaxonomy(): void
    {
        if (!function_exists('register_extended_taxonomy')) {
            return;
        }

        \register_extended_taxonomy(
            self::SLUG,
            [
                'testimonial',
            ],
            [
                // Core taxonomy configuration.
                'hierarchical'      => false,
                'show_admin_column' => true,
                'show_in_rest'      => true,
                'public'            => false,

                // Extended taxonomy configuration.
                'meta_box'         => 'simple',
                'exclusive'        => true, // Only one can be selected.
                'required'         => true,
                'dashboard_glance' => true,
            ],
            [
                // Override the base names used for labels (optional).
                'singular' => __('Testimonial Type', 'granola'),
                'plural'   => __('Testimonial Types', 'granola'),
                'slug'     => self::SLUG,
            ]
        );
    }

    /**
     * Filter the Granola Templates Taxonomies array to enable Template Pages for this taxonomy.
     *
     * @see /Granola/WordPress/TemplatePage.php
     *
     * @return array The filtered taxonomy array.
     */
    public static function filterGranolaTemplatesTaxonomies($taxonomies): array
    {
        $taxonomies[] = self::SLUG;
        return $taxonomies;
    }
}
