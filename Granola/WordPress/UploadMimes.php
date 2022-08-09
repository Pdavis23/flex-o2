<?php

namespace Granola\WordPress;

class UploadMimes
{
    public static function init(): void
    {
        add_filter('upload_mimes', [__CLASS__, 'extend']);
        add_filter('wp_check_filetype_and_ext', [__CLASS__, 'filetypeAndExt'], 10, 4);
    }

    public static function extend(array $mime_types = []): array
    {
        if (defined('GRANOLA_MIME_TYPES')) {
            foreach (GRANOLA_MIME_TYPES as $key => $value) {
                $mime_types[$key] = $value;
            }
        }

        return $mime_types;
    }

    public static function fileTypeAndExt($types, $file, $filename, $mimes)
    {
        if (defined('GRANOLA_MIME_TYPES')) {
            $filetype = wp_check_filetype($filename, $mimes);

            if (array_key_exists($filetype['ext'], GRANOLA_MIME_TYPES)) {
                $types['ext'] = $filetype['ext'];
                $types['type'] = $filetype['type'];
            }
        }

        return $types;
    }
}
