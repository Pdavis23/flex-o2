<?php

$queried_object = \Granola\WordPress\PageObject::get();

get_header();

$content = \Granola\WordPress\TemplatePage::getContent($queried_object);

if (empty($content)) {
    $content = \Granola\Component::get('no-content', [
        'object' => $wp_query,
    ]);
}

echo \Granola\Component::get('site-main', [
    'header' => \Granola\Component::get('page-header', [
        'object' => $queried_object,
    ]),
    'content' => $content,
]);

get_footer();
