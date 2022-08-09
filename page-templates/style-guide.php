<?php

/*
 * Template Name: Granola Style Guide
 * description: A testing template for HTML elements, edtitor blocks and Granola components
 */

get_header();

if (have_posts()) {
    while (have_posts()) {
        the_post();

        $content = [];
        $content[] = \Granola\Component::get('style-guide');
        $content[] =  apply_filters('the_content', get_the_content());

        echo \Granola\Component::get('site-main', [
            'header' => \Granola\Component::get('page-header', [
                'object' => $post,
            ]),
            'object' => \Granola\WordPress\PageObject::get(),
            'content' => implode($content),
        ]);
    }
} else {
    echo \Granola\Component::get('no-content');
}

get_footer();
