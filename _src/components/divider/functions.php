<?php

namespace Granola\Components\divider;

function filterArgs(array $args): array
{
    // ---------------------------------------
    // Default arguments.
    // ---------------------------------------
    $args = array_merge([
        'classes' => [],
        'attributes' => [],
        'icon_translucent' => false,
    ], $args);

    // ---------------------------------------
    // Required classes.
    // ---------------------------------------
    $args['classes'] = array_merge([
        'divider',
    ], $args['classes']);

    if (!empty($args['icon'])) {
        $args['classes'][] = 'icon--type--' . $args['icon'];

        if ($args['icon'] === 'ribbon-short' || $args['icon'] === 'ribbon-long') {
            $args['classes'][] = 'wp-block';
        }
    }

    if (!empty($args['icon_position'])) {
        $args['classes'][] = 'icon--position--' . $args['icon_position'];
    }

    if ($args['icon_translucent']) {
        $args['classes'][] = 'icon--translucent';
    }


    if (!empty($args['icon_color'])) {
        $args['attributes']['style']['--divider--icon-color'] = 'var(--color--' . $args['icon_color'] . ')';
    }

    // -------------------------------------------------------------------------
    // Return the filtered args.
    // -------------------------------------------------------------------------
    return $args;
}
