<?php

namespace Granola\Components\TaxonomyFilters;

function filterArgs(array $args): array
{
    // ---------------------------------------
    // Default arguments.
    // ---------------------------------------
    $args = array_merge([
        'classes' => [],
        'current_item' => 0,
        'label' => __('Filter by', 'granola'),
    ], $args);

    // ---------------------------------------
    // Required classes.
    // ---------------------------------------
    $args['classes'] = array_merge([
        'taxonomy-filters',
        'wp-block',
        'anchor--bottom--half',
    ], $args['classes']);

    if (!empty($args['object'])) {
        $object = $args['object'];

        if ($object instanceof \WP_Term) {
            $args['taxonomy'] = $object->taxonomy;
            $args['current_item'] = $object->term_id;
        } elseif ($object instanceof \WP_Post_Type) {
            $args['taxonomy'] = 'category';
        }
    }

    if (!empty($args['taxonomy'])) {
        $tax = get_taxonomy($args['taxonomy']);

        $args['label'] = sprintf(
            __('Filter by %s', 'granola'),
            strtolower($tax->labels->singular_name)
        );

        $items = get_terms($args['taxonomy']);

        if (!empty($items)) {
            foreach ($items as $key => $item) {
                $args['items'][$key] = [
                    'title' => $item->name,
                    'url' => get_term_link($item->slug, $item->taxonomy),
                    'classes' => [
                        'g-button',
                        'g-button--small',
                        'taxonomy-filters__item',
                    ],
                ];

                if ($args['current_item'] === $item->term_id) {
                    $args['items'][$key]['classes'][] = 'taxonomy-filters__item--current';
                }
            }
        }
    }

    // -------------------------------------------------------------------------
    // Return the filtered args.
    // -------------------------------------------------------------------------
    return $args;
}
