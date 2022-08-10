<?php

namespace Granola\Components\Links;

function filterArgs(array $args): array
{
    // ---------------------------------------
    // Default arguments.
    // ---------------------------------------
    $args = array_merge([
        'type' => 'default',
        'items' => [],
        'link' => null,
        'align' => 'full',
        'classes' => [],
        'columns' => '',
        'card_type' => '',
        'slider_on_mobile' => false,
    ], $args);

    // ---------------------------------------
    // Required classes.
    // ---------------------------------------
    $args['classes'] = array_merge([
        'links',
        'wp-block',
        'animate',
        'type--' . $args['display'],
    ], $args['classes']);

    if (!empty($args['items'])) {
        if ($args['display'] === 'links') {
            foreach ($args['items'] as $key => $item) {
                $args['items'][$key]['link']['classes'] = ['g-button--arrow', 'g-button'];
            }
        }
    }

    if (!empty($args['heading']) || !empty($args['subheading'])) {
        $args['classes'][] = 'has-heading';
    }


    // -------------------------------------------------------------------------
    // Return the filtered args.
    // -------------------------------------------------------------------------
    return $args;
}
