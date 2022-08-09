<?php

namespace Theme\WordPress;

class Excerpt
{
    public static function init()
    {
        \add_filter('excerpt_more', [__CLASS__, 'filterExcerptMore']);
        \add_filter('excerpt_length', [__CLASS__, 'filterExcerptLength']);
    }

    /**
     * Add "..." to the excerpt.
     *
     * @return string The text appended to the excerpt.
     */
    public static function filterExcerptMore()
    {
        return '&hellip;';
    }

    /**
     * Set the excerpt length.
     *
     * @return int The new excerpt length.
     */
    public static function filterExcerptLength()
    {
        return 20;
    }
}
