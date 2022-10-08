<?php

namespace Granola\Components\Benefits;

function filterArgs(array $args): array
{
    // ---------------------------------------
    // Default arguments.
    // ---------------------------------------
    $args = array_merge([
        'attributes' => [],
        'classes' => [],
        'items' => [],
    ], $args);

    // ---------------------------------------
    // Required classes.
    // ---------------------------------------
    $args['classes'] = array_merge([
        'benefits',
        'wp-block',
    ], $args['classes']);

    if (!empty($args['items'])) {
        foreach ($args['items'] as $key => $item) {
            if (!empty($args['items'][$key]['link'])) {
                $args['items'][$key]['link']['classes'][] = 'g-button--arrow';
            }
        }
    }

    $args['background_color'] = 'sand';

    // -------------------------------------------------------------------------
    // Return the filtered args.
    // -------------------------------------------------------------------------
    return $args;
}
