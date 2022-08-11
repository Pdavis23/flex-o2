<?php

namespace Granola\Components\language;

function filterArgs(array $args): array
{
    // ---------------------------------------
    // Default arguments.
    // ---------------------------------------
    $args = array_merge([
        'classes' => [],
        'icon' => 'address',
        'label' => '',
        'post_id' => '',
        'languages' => [],
        'show' => false,
    ], $args);

    // ---------------------------------------
    // Required classes.
    // ---------------------------------------
    $args['classes'] = array_merge([
        'language',
        'wp-block',
    ], $args['classes']);

    // $args['label'] = \pll_register_string('Read', 'Read in');
    $args['label'] = __('Read In:', 'granola');

    $translations = pll_get_post_translations($args['post_id']);

    if ($translations) {
        foreach ($translations as $translated_post) {
            if ($args['post_id'] != $translated_post) {
                $args['show'] = true;
                $args['languages'][] = [
                    'title' => get_the_title($translated_post),
                    'url' => get_the_permalink($translated_post),
                    'language' => pll_get_post_language($translated_post, 'name'),
                ];
            }
        }
    }

    // -------------------------------------------------------------------------
    // Return the filtered args.
    // -------------------------------------------------------------------------
    return $args;
}
