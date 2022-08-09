<?php

namespace Granola\WordPress;

class Images
{
    public static function init(): void
    {
        add_action('after_setup_theme', [__CLASS__, 'registerImageSizes']);
        add_filter('image_size_names_choose', [__CLASS__,'setThemeImageSizeNames']);
        add_filter('intermediate_image_sizes_advanced', [__CLASS__, 'removeUnwantedImageSizes']);
        add_filter('jpeg_quality', [__CLASS__, 'setDefaultJPEGQuality']);
        add_filter('big_image_size_threshold', [__CLASS__, 'setMaxImageSize']);
    }

    public static function registerImageSizes(): void
    {
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(150, 150, true);

        if (defined('GRANOLA_IMAGE_SIZES')) {
            foreach (GRANOLA_IMAGE_SIZES as $size_id => $size_info) {
                if ($size_info === false) {
                    continue;
                }

                if (empty($size_info[2])) {
                    $size_info[2] = false;
                }

                add_image_size(
                    $size_id,
                    $size_info[0],
                    $size_info[1],
                    $size_info[2]
                );
            }
        }
    }

    /**
     * Set the nice names for each image.
     * Hooked by https://developer.wordpress.org/reference/hooks/image_size_names_choose/
     *
     */
    public static function setThemeImageSizeNames(array $sizes): array
    {
        if (defined('GRANOLA_IMAGE_SIZES')) {
            foreach (GRANOLA_IMAGE_SIZES as $size_id => $size) {
                if ($size === false) {
                    continue;
                }

                $sizes[$size_id] = ucwords(str_replace('_', ' ', str_replace('granola_', '', $size_id)));
            }
        }

        return $sizes;
    }

    /**
     * Prevent images for unneeded default sizes being generated on upload.
     * Hooked by https://developer.wordpress.org/reference/hooks/intermediate_image_sizes_advanced/
     *
     */
    public static function removeUnwantedImageSizes(array $sizes): array
    {
        if (defined('GRANOLA_IMAGE_SIZES')) {
            foreach ($sizes as $size_id => $size) {
                if (array_key_exists($size_id, GRANOLA_IMAGE_SIZES) && GRANOLA_IMAGE_SIZES[$size_id] === false) {
                    unset($sizes[$size_id]);
                }
            }
        }

        return $sizes;
    }

    /**
     * Set the default JPEG compression quality on upload.
     * Hooked by https://developer.wordpress.org/reference/hooks/jpeg_quality/
     *
     */
    public static function setDefaultJPEGQuality(int $quality): int
    {
        return defined('GRANOLA_JPEG_UPLOAD_QUALITY') ? GRANOLA_JPEG_UPLOAD_QUALITY : $quality;
    }

    /**
     * Set the threshold (in pixels) at which images will be downscaled.
     * Hooked by https://developer.wordpress.org/reference/hooks/big_image_size_threshold/
     *
     */
    public static function setMaxImageSize(int $threshold): int
    {
        $size = defined('GRANOLA_MAX_IMAGE_SIZE') ? GRANOLA_MAX_IMAGE_SIZE : $threshold;

        return $size;
    }
}
