<?php

$siteManifest = json_decode(\Granola\Asset::content('static/site.webmanifest'), true);

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="manifest" href="<?= \Granola\Asset::URL('static/site.webmanifest'); ?>" crossorigin="use-credentials">
    <meta name="theme-color" content="<?= esc_attr($siteManifest['theme_color']); ?>"/>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <?= \Granola\Component::get('cookie-notice'); ?>
    <?= \Granola\Component::get('skip-link'); ?>
    <?= \Granola\Component::get('site-header'); ?>

    <main class="main" id="main">
