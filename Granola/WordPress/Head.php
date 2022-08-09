<?php

namespace Granola\WordPress;

/**
 * Handles any non-cleanup <head> functionality.
 *
 * @see Cleanup.php
 */
class Head
{
    public static function init(): void
    {
        add_action('wp_head', [__CLASS__, 'preloadThemeAssets'], 10);
        add_action('wp_head', [__CLASS__, 'javascriptDetection'], 0);
    }

    // ------------------------------------------
    // Output <link rel="preload"> tags in the head
    // for assets specified in granola/config.php
    // ------------------------------------------
    public static function preloadThemeAssets(): void
    {
        if (defined('GRANOLA_PRELOAD_ASSETS')) {
            $defaults = [
                'rel'        => 'preload',
                'href'        => '',
                'importance'  => 'low',
                'type'        => 'font/woff2',
                'as'          => 'font',
                'crossorigin' => 'anonymous',
            ];

            foreach (GRANOLA_PRELOAD_ASSETS as $key => $value) {
                $atts = array_merge($defaults, $value);

                if (!empty($atts['href'])) {
                    echo "<link " . \Granola\Helpers::buildAttributes($atts) . ">\n";
                }
            }
        }
    }

    // ------------------------------------------
    // Handles JavaScript detection.
    // Adds a `js` class to the root `<html>` element when JavaScript is detected.
    // Needs to be added in the header to avoid FOUC.
    // ------------------------------------------
    public static function javascriptDetection(): void
    {
        echo "<script>(function(html){html.className = " .
        "html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
    }
}
