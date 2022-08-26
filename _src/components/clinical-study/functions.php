<?php

namespace Granola\Components\clinicalStudy;

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
        'clinical-study',
        'wp-block',
    ], $args['classes']);

    if (!empty($args['file'])) {
        $args['button'] = [
            'title' => pll__('Download'),
            'url' => $args['file']['url'],
            'classes' => ['g-button'],
            'target' => '_blank',
        ];

        $args['file_meta'] = round($args['file']['filesize'] / 1000 / 1000, 2) . ' MB';
    }

    // -------------------------------------------------------------------------
    // Return the filtered args.
    // -------------------------------------------------------------------------
    return $args;
}
