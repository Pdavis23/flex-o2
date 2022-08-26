<?php

// ----------------------------------------------------
// Register the autoloader from Composer.
// ----------------------------------------------------

if (file_exists($autoloader = __DIR__ . '/vendor/autoload.php')) {
    require $autoloader;
}

// ----------------------------------------------------
// Load the config file
// ----------------------------------------------------
require_once 'config.php';

// ----------------------------------------------------
// Load core Granola functionality.
// ----------------------------------------------------
\Granola\Partial::init();
\Granola\Component::init();
\Granola\ServiceWorker::init();

\Granola\WordPress\Admin::init();
\Granola\WordPress\Cleanup::init();
\Granola\WordPress\Comments::init();
\Granola\WordPress\EditHomepage::init();
\Granola\WordPress\Enqueue::init();
\Granola\WordPress\Gutenberg::init();
\Granola\WordPress\Head::init();
\Granola\WordPress\Images::init();
\Granola\WordPress\Menus::init();
\Granola\WordPress\PostsPT::init();
\Granola\WordPress\Security::init();
\Granola\WordPress\Sidebars::init();
\Granola\WordPress\TemplatePage::init();
\Granola\WordPress\ThemeSetup::init();
\Granola\WordPress\Updates::init();
\Granola\WordPress\UploadMimes::init();

// ----------------------------------------------------
// Load custom Theme functionality.
// ----------------------------------------------------
// All custom functions for a theme should go in the
// 'Theme' folder and (ideally) follow a namespaced,
// class-based approach.
// ----------------------------------------------------
// WordPress -  & Other Functionality.
// ----------------------------------------------------
\Theme\WordPress\Emails::init();
\Theme\WordPress\Escaping::init();
\Theme\WordPress\Excerpt::init();

// ----------------------------------------------------
// Custom Shortcodes.
// ----------------------------------------------------
\Theme\Shortcodes\Year::init();

// ----------------------------------------------------
// Custom Post Types.
// ----------------------------------------------------
\Theme\PostTypes\CaseStudy::init();
\Theme\PostTypes\Post::init();

// ----------------------------------------------------
// Custom Taxonomies.
// ----------------------------------------------------
// \Theme\Taxonomies\Location::init();
\Theme\Taxonomies\Category::init();

// ----------------------------------------------------
// Custom Plugin functionality.
// ----------------------------------------------------
\Theme\Plugins\ACF::init();
\Theme\Plugins\YoastSEO::init();
\Theme\Plugins\Polylang::init();
