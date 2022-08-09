<?php

namespace Granola\WordPress;

class Sidebars
{
    public static function init(): void
    {
        add_action('widgets_init', [__CLASS__, 'register']);
    }

    public static function register(): void
    {
        if (defined('GRANOLA_SIDEBARS')) {
            foreach (GRANOLA_SIDEBARS as $key => $sidebar) {
                register_sidebar($sidebar);
            }
        }
    }
}
