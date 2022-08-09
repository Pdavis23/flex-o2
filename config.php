<?php

// phpcs:disable PSR1.Files.SideEffects

// ----------------------------------------------------
// ----------------------------------------------------
// Granola Configuration.
// ----------------------------------------------------

// ----------------------------------------------------
// General WordPress and Theme Setup
// ----------------------------------------------------
define('GRANOLA_DISABLE_POSTS_PT', false);
define('GRANOLA_DISABLE_COMMENTS', true);
define('GRANOLA_AJAX_REQUIRED', false);
define('GRANOLA_REMOVE_WP_GLOBAL_STYLES', false);

define('GRANOLA_JQUERY_REQUIRED', false);
define('GRANOLA_JQUERY_IN_FOOTER', true);
define('GRANOLA_REMOVE_JQUERY_MIGRATE', true);


// ----------------------------------------------------
// Image Sizes
// ----------------------------------------------------
// Define the generated image thumbnail sizes

// [id] => [width, height, hard crop]
// OR [id] => false to disable a default size.

// ----------------------------------------------------
define('GRANOLA_IMAGE_SIZES', [
    'granola_super'       => [2500, 2500, false],
    // 'granola_square_900'  => [900, 900, true],

    // Default thumbnails to disable
    // N.B. If Shortpixel AI is being used, multiple uncropped thumbnails aren't necessary.
    // 'thumbnail'    => false,
    // 'medium'       => false,
    // 'medium_large' => false,
    // '1536x1536'    => false,
    // '2048x2048'    => false,
]);

define('GRANOLA_JPEG_UPLOAD_QUALITY', 90); // The compression ratio for all JPEG uploads
define('GRANOLA_MAX_IMAGE_SIZE', 3000); // Images larger than this size will be downscaled by WordPress

// ----------------------------------------------------
// Menus.
// ----------------------------------------------------
define('GRANOLA_MENUS', [
    'header' => 'Header',
    'footer-1' => 'Footer 1',
    'footer-2' => 'Footer 2',
]);


// ----------------------------------------------------
// Sidebars.
// ----------------------------------------------------
// define('GRANOLA_SIDEBARS', [
//     [
//         'name'          => esc_html__( 'Sidebar', 'granola' ),
//         'id'            => 'sidebar-1',
//         'description'   => esc_html__( 'Add widgets here.', 'granola' ),
//         'before_widget' => '<section id="%1$s" class="widget %2$s">',
//         'after_widget'  => '</section>',
//         'before_title'  => '<h4 class="widget-title">',
//         'after_title'   => '</h4>',
//     ]
// ]);

// ----------------------------------------------------
// Options pages.
// ----------------------------------------------------
define('GRANOLA_ACF_OPTIONS_PAGES', [
    'General',
    'Header',
    'Footer',
]);


// ----------------------------------------------------
// Preloads.
// ----------------------------------------------------
define('GRANOLA_PRELOAD_ASSETS', [
    // [
    //     'href' => \Granola\Asset::URL('static/WebFont-Regular.woff2'),
    //     'importance' => 'low',
    //     'type' => 'font/woff2',
    //     'as' => 'font',
    //     'crossorigin' => 'anonymous',
    // ],
]);


// ----------------------------------------------------
// MIME Types.
// ----------------------------------------------------
define('GRANOLA_MIME_TYPES', [
    // 'svg' => 'image/svg+xml',
    // 'msg  => 'application/vnd.ms-outlook',
    // 'flv  => 'video/x-flv',
    // 'xls  => 'application/application/excel',
    // 'xlsx => 'application/application/vnd.ms-excel',
    // 'tiff => 'image/tiff',
    // 'tif  => 'image/tiff',
    // 'psd  => 'image/photoshop',
    // 'xlsx => 'application/application/vnd.ms-excel',
    // 'swf  => 'application/x-shockwave-flash',
]);


// ----------------------------------------------------
// Theme Color Palette (from theme.json).
// ----------------------------------------------------
$theme_json = json_decode(file_get_contents(get_theme_file_path('theme.json')));

if (!empty($theme_json->settings->color->palette)) {
    $color_palette_arrs = [];

    foreach ($theme_json->settings->color->palette as $color_palette_obj) {
        $color_palette_arrs[] = (array) $color_palette_obj;
    }

    define('GRANOLA_COLOR_PALETTE', $color_palette_arrs);
}
