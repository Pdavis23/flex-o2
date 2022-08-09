<?php

/**
 * Handles core 'Post' post type related functionality.
 */

namespace Theme\PostTypes;

class Post
{
    protected const SLUG = 'post';

    public static function init(): void
    {
        \add_filter('granola/templates/post-types', [__CLASS__, 'filterGranolaTemplatesPostTypes']);
    }

    /**
     * Filter the Granola Templates Post Types array to enable Template Pages for this post type.
     *
     * @see /Granola/WordPress/TemplatePage.php
     *
     * @return array The filtered post type array.
     */
    public static function filterGranolaTemplatesPostTypes($postTypes)
    {
        $postTypes[] = self::SLUG;
        return $postTypes;
    }
}
