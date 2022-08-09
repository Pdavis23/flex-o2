<?php

$queried_object = \Granola\WordPress\PageObject::get();

get_header();

$items = [];
$content = [];

while (have_posts()) {
    the_post();
    $items[]['object'] = $post;
}

$content[] = \Granola\WordPress\TemplatePage::getContent($queried_object);

if (!have_posts()) {
    $content[] = \Granola\Component::get('no-content', [
        'object' => $queried_object,
    ]);
} else {
    $content[] = \Granola\Component::get('taxonomy-filters', [
        'object' => $queried_object,
    ]);

    $content[] = \Granola\Component::get('cards', [
        'items' => $items,
        'columns' => 4,
        'classes' => [
            'anchor--top--half',
        ]
    ]);

    $content[] = \Granola\Component::get('pagination');
}

echo \Granola\Component::get('site-main', [
    'header' => \Granola\Component::get('page-header', [
        'object' => $queried_object,
    ]),
    'object' => $queried_object,
    'content' => implode($content),
]);

get_footer();
