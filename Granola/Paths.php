<?php

namespace Granola;

class Paths
{
    /**
     * Take a path and turn it in to a legitimate file path. Allows for a child
     * theme to implement a new version of the partial
     * @param string $path
     * @return string
     */
    public static function resolve(string $path): string
    {
        // Resolve to get a full path
        $path = \Granola\Paths::themePath($path);

        // If its a directory, we will load up the index.php file. This imitates
        // node.js with import statements.
        if (is_dir($path)) {
            $path = $path . '/index.php';
        }

        // Pull apart the full path
        $pathinfo = pathinfo($path);

        // Check if there is an extension. If not add one
        if (isset($pathinfo['extension']) !== true) {
            $path = $path . '.php';
        }

        return $path;
    }

    public static function themeURL(string $path = ''): string
    {
        // https://developer.wordpress.org/reference/functions/get_stylesheet_directory_uri/
        // Supports child theme overriding
        return get_stylesheet_directory_uri() . '/' . $path;
    }

    public static function themePath(string $path = ''): string
    {
        // https://developer.wordpress.org/reference/functions/get_theme_file_path/
        // Supports child theme overriding
        if ($path !== '') {
            return get_theme_file_path() . '/' . $path;
        }

        return get_theme_file_path();
    }

    public static function assetsDir(): string
    {
        return 'assets/';
    }
}
