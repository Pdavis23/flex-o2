<?php

namespace Granola\Components\PageHeader;

function filterArgs(array $args): array
{
    // ---------------------------------------
    // Default arguments.
    // ---------------------------------------
    $args = array_merge([
        'classes' => [],
        'type' => '',
        'show' => true,
        'image-position' => 'inset',
        'attributes' => [],
        'content' => [],
        'post_id' => '',
    ], $args);

    // ---------------------------------------
    // Required classes.
    // ---------------------------------------
    $args['classes'] = array_merge([
        'page-header',
        'alignfull',
    ], $args['classes']);


    // Set up page header args for each type of view (singular posts, archive pages and terms)
    if (!empty($args['object'])) {
        $object = $args['object'];

        if ($object instanceof \WP_Term) {
            $args['content']['heading'] = $object->name;

            if ($object->taxonomy === 'category') {
                $post_type = \get_post_type_object('post');

                $args['content']['back-link'] = [
                    'url' => \get_post_type_archive_link('post'),
                    'title' => sprintf(pll__('Back to %s'), $post_type->labels->name),
                ];
            }
        } elseif ($object instanceof \WP_Post_Type) {
            $heading = $object->label;

            $args['content'] = [
                'heading' => $heading,
            ];
        } elseif ($object instanceof \WP_Query && $object->is_404()) {
            $args['content'] = [
                'heading' => __('404', 'granola'),
            ];
        } elseif ($object instanceof \WP_Query && $object->is_search()) {
            $args['content'] = [
                'heading' => __('Search', 'granola'),
            ];

            if (!empty($object->query['s'])) {
                $args['content']['subheading'] = sprintf(
                    __("Showing results for '%s'", 'granola'),
                    $object->query['s']
                );
            }
        } elseif ($object instanceof \WP_User) {
            $heading = $object->data->display_name;

            $args['content'] = [
                'heading' => sprintf(
                    __('Posts by %s', 'granola'),
                    $heading
                ),
            ];
        }

        // If the content has a connected archive content page, set the object to that page
        if ($archive_page = \Granola\WordPress\TemplatePage::getPage($object)) {
            $object = $archive_page;
        }

        if ($object instanceof \WP_Post) {
            $post_type = \get_post_type_object($object->post_type);
            $args['post_id'] = $object->ID;

            // -----------------------------------------------------------------
            // Handle filtering content from WordPress posts
            // -----------------------------------------------------------------

            if (!in_array($object->post_type, ['page', 'granola-template'])) {
                // Add a 'back to' link to the post type archive by default
                // $args['content']['back-link'] = [
                //     'url' => \get_post_type_archive_link($object->post_type),
                //     'title' => sprintf(__('Back to %s', 'granola'), $post_type->labels->name),
                // ];
            }

            $args['content']['heading'] = $object->post_title;

            $args['content']['image'] = \get_post_thumbnail_id($object);

            if ($primary_call_to_action = \get_field('primary_call_to_action', $object)) {
                $args['content']['buttons'][] = array_merge($primary_call_to_action, [
                    'classes' => [
                        'g-button',
                        'g-button--arrow',
                    ]
                ]);
            }

            if ($subheading = \get_field('subheading', $object)) {
                $args['content']['subheading'] = $subheading;
            }

            if ($overlay_opacity = \get_field('image_overlay_opacity', $object)) {
                $args['attributes']['style']['--page-header--overlay-opacity'] = "$overlay_opacity%";
            }

            if ($object->post_type === 'post') {
                $args['content']['meta'] = sprintf(
                    pll__('Published on %s '),
                    \get_the_date(\get_option('date_format'), $object->ID)
                );

                $args['background'] = false;
                $args['image-position'] = 'inset';

                $args['type'] = 'article';

                if ($background_color = \get_field('background_color', $object)) {
                    if ($background_color === 'seafoam') {
                        $args['attributes']['style']['--page-header--background-color'] = 'var(--color--' . $background_color . ')';
                        $args['color'] = 'has-background has-' . $background_color . '-background-color';
                    }
                }
            } elseif ($object->post_type === 'case-study') {
                $args['content']['meta'] = sprintf(
                    pll__('Published on %s '),
                    \get_the_date(\get_option('date_format'), $object->ID)
                );

                $args['background'] = false;
                $args['image-position'] = 'inset';

                $args['type'] = 'case-study';

                if ($background_color = \get_field('background_color', $object)) {
                    if ($background_color === 'seafoam') {
                        $args['attributes']['style']['--page-header--background-color'] = 'var(--color--' . $background_color . ')';
                        $args['color'] = 'has-background has-' . $background_color . '-background-color';
                    }
                }
            } elseif ($object->post_type === 'page') {
                if (\is_front_page()) {
                    $args['classes'][] = 'page-header--home';

                    $args['content']['screen-reader-heading'] = $args['content']['heading'];
                    $args['content']['heading'] = null;

                    if ($secondary_image = \get_field('secondary_image', $object)) {
                        $args['content']['secondary-image'] = ['ID' => $secondary_image];
                    }
                }

                if ($parent = $object->post_parent) {
                    $args['content']['back-link'] = [
                        'url' => \get_permalink($parent),
                        'title' => sprintf(
                            pll__('Back to %s'),
                            \get_the_title($parent)
                        ),
                    ];
                }

                if ($background_color = \get_field('background_color', $object)) {
                    if ($background_color === 'seafoam') {
                        $args['attributes']['style']['--page-header--background-color'] = 'var(--color--' . $background_color . ')';
                        $args['color'] = 'has-background has-' . $background_color . '-background-color';
                    }
                }
            }
        }

        unset($args['object']);
    }

    // -------------------------------------------------------------------------
    // Pull the image if one exists
    // -------------------------------------------------------------------------
    if (!empty($args['content']['image']) && is_int($args['content']['image'])) {
        if ($args['image-position'] === 'inset') {
            $args['content']['inset-image'] = \wp_get_attachment_image($args['content']['image'], 'granola_super');
        } else {
            $args['content']['background-image'] = \wp_get_attachment_image($args['content']['image'], 'granola_super');
        }
    }

    if (!empty($args['background']) && $args['background'] !== 'none') {
        $args['classes'][] = 'has-' . $args['background'] . '-background-color';
        $args['classes'][] = 'has-background';
    }

    if (!empty($args['type'])) {
        $args['classes'][] = 'page-header--type--' . $args['type'];
    }

    if (!empty($args['content']['background-image'])) {
        $args['classes'][] = 'has-background';
        $args['classes'][] = 'has-background-image';
    }

    if (!empty($args['content']['inset-image'])) {
        $args['classes'][] = 'page-header--inset-image';
    } else {
        $args['classes'][] = 'page-header--no-image';
    }

    if (!empty($args['content']['back-link'])) {
        $args['content']['back-link']['prefix'] = '←';
    }

    // -------------------------------------------------------------------------
    // Return the filtered args.
    // -------------------------------------------------------------------------
    return $args;
}
