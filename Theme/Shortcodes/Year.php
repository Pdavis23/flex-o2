<?php

namespace Theme\Shortcodes;

/**
 * A shortcode to output the current year.
 *
 * Useful for copyright notices and as a starting point for new shortcode classes.
 */
class Year
{
    public static function init()
    {
        \add_shortcode('year', [__CLASS__, 'yearShortcode']);
    }

    public static function yearShortcode()
    {
        return date('Y');
    }
}
