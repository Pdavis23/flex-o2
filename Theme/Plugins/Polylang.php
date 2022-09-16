<?php

namespace Theme\Plugins;

/*
 * For Publications, auto-populate the region from the sub-region.
*/

class Polylang
{
    public static $filtered = false;

    public static function init(): void
    {
        add_action('init', [__CLASS__, 'translateStrings']);
        // Need to force ACF to show fields set to homepage on the homepage of other languages.
        // Guide from here: https://support.advancedcustomfields.com/forums/topic/polylang-location-front-page/
        add_filter('acf/location/rule_match/page_type', [__CLASS__, 'hookPageOnFront']);
    }

    public static function translateStrings(): void
    {
        if (function_exists('pll_register_string')) {
            \pll_register_string('Select language', 'Select language', 'FlexO2');
            \pll_register_string('Published on %s ', 'Published on %s ', 'FlexO2');
            \pll_register_string('Read more', 'Read more', 'FlexO2');
            \pll_register_string('Download', 'Download', 'FlexO2');
            \pll_register_string('Download Case Study: %s', 'Download Case Study: %s', 'FlexO2 - accessibility text');
            \pll_register_string('Accept', 'Accept', 'Cookies');
            \pll_register_string('Reject', 'Reject', 'Cookies');
            \pll_register_string('site cookies', 'site cookies', 'Cookies');
            \pll_register_string('No content found.', 'No content found.', 'FlexO2');
            \pll_register_string('Back to %s', 'Back to %s', 'FlexO2');
            \pll_register_string('Previous page', 'Previous page', 'Pagination');
            \pll_register_string('Next page', 'Next page', 'Pagination');
            \pll_register_string('Page', 'Page', 'Pagination');
            \pll_register_string('Main menu button', 'Main menu button', 'FlexO2 - accessibility text');
            \pll_register_string('Skip to content', 'Skip to content', 'FlexO2 - accessibility text');
            \pll_register_string('Filter by', 'Filter by', 'FlexO2');
            \pll_register_string('Filter by %s', 'Filter by %s', 'FlexO2');
        }
    }

    public static function hookPageOnFront($match)
    {
        if (!self::$filtered) {
            add_filter('option_page_on_front', [__CLASS__, 'translatePageOnBack']);
            // Prevent second hooking
            self::$filtered = true;
        }

        return $match;
    }

    public static function translatePageOnBack($value)
    {

        if (function_exists('pll_get_post')) {
            $value = \pll_get_post($value);
        }

        return $value;
    }
}
