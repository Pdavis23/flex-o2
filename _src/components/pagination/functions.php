<?php

namespace Granola\Components\Pagination;

function filterArgs(array $args): array
{
    // ---------------------------------------
    // Default arguments.
    // ---------------------------------------
    $args = array_merge([
        'classes' => [
            'pagination',
            'alignfull',
            'wp-block',
        ]
    ], $args);

    $args['output'] = get_the_posts_pagination([
        'prev_text' => pll__('Previous page'),
        'next_text' => pll__('Next page'),
        'before_page_number' => '<span class="screen-reader-text">' . pll__('Page') . ' </span>',
        'class' => 'pagination__inner',
    ]);

    // -------------------------------------------------------------------------
    // Return the filtered args.
    // -------------------------------------------------------------------------
    return $args;
}
