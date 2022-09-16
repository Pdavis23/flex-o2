<?php

$siteManifest = json_decode(\Granola\Asset::content('static/site.webmanifest'), true);

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="manifest" href="<?= \Granola\Asset::URL('static/site.webmanifest'); ?>" crossorigin="use-credentials">
    <meta name="theme-color" content="<?= esc_attr($siteManifest['theme_color']); ?>" />

    <?php wp_head(); ?>
</head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-JJVFEBNQEG"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-JJVFEBNQEG');
</script>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <?php if (get_field('show_cookie_notice', 'options')) { ?>
        <?= \Granola\Component::get('cookie-notice'); ?>
    <?php } ?>
    <?= \Granola\Component::get('skip-link'); ?>
    <?= \Granola\Component::get('site-header'); ?>

    <main class="main" id="main">
