<?php

namespace Theme\Plugins;

/*
 * For Publications, auto-populate the region from the sub-region.
*/

class Polylang
{
    public static function init(): void
    {
        add_action('init', [__CLASS__, 'translateStrings']);
    }

    public static function translateStrings(): void
    {
        if (function_exists('pll_register_string')) {
            pll_register_string('Select language', 'Select language', 'FlexO2');
        }
    }
}
