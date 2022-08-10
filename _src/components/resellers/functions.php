<?php

namespace Granola\Components\Resellers;

function filterArgs(array $args): array
{
    // ---------------------------------------
    // Default arguments.
    // ---------------------------------------
    $args = array_merge([
        'classes' => [],
        'heading' => 'Hello World',
    ], $args);

    // ---------------------------------------
    // Required classes.
    // ---------------------------------------
    $args['classes'] = array_merge([
        'resellers',
        'wp-block',
    ], $args['classes']);

    $colors = [
        'white',
        'teal',
        'mulberry',
        'khaki',
        'seafoam',
        'yellow',
        'peach',
        'sand',
        'grey',
    ];

    if (!empty($args['resellers'])) {
        foreach ($args['resellers'] as $key => $reseller) {
            if (empty($reseller['background_color'])) {
                $args['resellers'][$key]['background_color'] = $colors[rand(0, 8)];
            }
        }
    }

    // -------------------------------------------------------------------------
    // Return the filtered args.
    // -------------------------------------------------------------------------
    return $args;
}
