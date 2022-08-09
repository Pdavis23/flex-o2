<?php

namespace Granola;

class Asset
{
    /**
     * Takes an asset path and returns the compiled asset path from the manifest.json
     * @param string $asset A path to an asset
     * @return string|bool
     */
    public static function extract(string $asset)
    {
        if ($path = json_decode(\Granola\Asset::content('manifest.json'), true)) {
            $path_parts = explode('/', $asset);

            foreach ($path_parts as $part) {
                $path = $path[$part] ?? null;

                if (is_null($path)) {
                    break;
                }
            }

            if (!empty($path)) {
                return $path;
            } else {
                return false;
            }
        } else {
            \Granola\Helpers::errorLog('manifest.json could not be found');
            return false;
        }
    }

    /**
     * Takes an asset path and returns the content of the asset.
     * @param string $asset A path to an asset
     * @return string|bool
     */
    public static function content(string $asset_path)
    {
        if ($path = \Granola\Asset::path($asset_path)) {
            if (!file_exists($path)) {
                return '';
            }

            return trim(file_get_contents($path));
        }

        return '';
    }

    /**
     * Takes an asset path and returns the full compiled theme path.
     * @param string $asset A path to an asset
     * @param bool $manifest Whether to use the manifest.json to return a versioned asset name.
     * @return string|bool
     */
    public static function path(string $asset = '', bool $manifest = false)
    {
        if ($manifest === true) {
            $asset = \Granola\Asset::extract($asset);
        }

        if (!empty($asset)) {
            return \Granola\Paths::themePath(
                \Granola\Paths::assetsDir() . $asset
            );
        }

        return false;
    }

    /**
     * Takes and asset path and returns the full compiled theme URL.
     * @param string $asset A path to an asset
     * @param bool $manifest Whether to use the manifest.json to return a versioned asset name.
     * @return string
     */
    public static function URL(string $asset, bool $manifest = false)
    {
        if ($manifest === true) {
            $asset = \Granola\Asset::extract($asset);
        }

        if (!empty($asset)) {
            return \Granola\Paths::themeURL(
                \Granola\Paths::assetsDir() . $asset
            );
        }

        return false;
    }
}
