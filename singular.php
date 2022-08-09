<?php

$queried_object = \Granola\WordPress\PageObject::get();

get_header();

while (have_posts()) {
    the_post();

    $content[] = apply_filters('the_content', get_the_content());

    echo \Granola\Component::get('site-main', [
        'header' => \Granola\Component::get('page-header', [
            'object' => $queried_object,
        ]),
        'object' => $queried_object,
        'content' => implode($content),
    ]);
}

get_footer();
