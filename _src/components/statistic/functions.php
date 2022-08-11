<?php

namespace Granola\Components\statistic;

function filterArgs(array $args): array
{
    // ---------------------------------------
    // Default arguments.
    // ---------------------------------------
    $args = array_merge([
        'classes' => [],
    ], $args);

    // ---------------------------------------
    // Required classes.
    // ---------------------------------------
    $args['classes'] = array_merge([
        'statistic',
        'wp-block',
    ], $args['classes']);

    if (!empty($args['icon'])) {
        $args['classes'][] = 'icon--type--' . $args['icon'];
    }

    // -------------------------------------------------------------------------
    // Return the filtered args.
    // -------------------------------------------------------------------------
    return $args;
}
