<?php

namespace Granola;

class Component extends Partial
{
    public static function init(): void
    {
        add_action('init', [__CLASS__, 'loadComponentFunctions']);
        add_action('init', [__CLASS__, 'loadComponentHooks']);
        add_action('acf/init', [__CLASS__, 'addBlocks']);
        add_filter('acf/settings/load_json', [__CLASS__, 'loadBlockFieldGroupJSON']);
        add_action('acf/update_field_group', [__CLASS__, 'saveBlockFieldGroupJSON'], 1);
    }

    public static function saveBlockFieldGroupJSON($group): void
    {
        // Bail early - this field group is not assigned to a block.
        if (empty($group['location'][0][0]['param']) || $group['location'][0][0]['param'] !== 'block') {
            return;
        }

        $blockName = str_replace('acf/', '', $group['location'][0][0]['value']);

        // This should only ever return a one-item array.
        $blockFieldGroupJSONDirPaths = glob(\Granola\Paths::themePath('_src/components*/' . $blockName));

        // Bail if the glob has failed.
        if (!is_array($blockFieldGroupJSONDirPaths)) {
            return;
        }

        foreach ($blockFieldGroupJSONDirPaths as $path) {
            if (!is_dir($path)) {
                continue;
            }

            // Hook into the save_json filter and change the save path to the
            // current block's directory so that the block's acf json file is
            // saved in its directory, rather than the `acf-json/` directory.
            add_action('acf/settings/save_json', function () use ($path) {
                return $path;
            }, 9999);
        }
    }

    /**
     * Load Field Groups from the locations we've set elsewhere.
     * See https://www.advancedcustomfields.com/resources/local-json/
     *
     */
    public static function loadBlockFieldGroupJSON(array $paths): array
    {
        return array_merge(
            $paths,
            glob(\Granola\Paths::themePath('assets/components/*'))
        );
    }

    public static function addBlocks(): void
    {
        self::require(
            glob(\Granola\Paths::themePath('assets/components/*/acf.php'))
        );
    }

    public static function loadComponentFunctions(): void
    {
        self::require(
            glob(\Granola\Paths::themePath('assets/components/*/functions.php'))
        );
    }

    public static function loadComponentHooks(): void
    {
        self::require(
            glob(\Granola\Paths::themePath('assets/components/*/hooks.php'))
        );
    }

    private static function require(array $files): void
    {
        foreach ($files as $key => $file) {
            require_once $file;
        }
    }

    /**
     * Retrieve a partial from the theme components and pass arguments to it.
     *
     * Like https://developer.wordpress.org/reference/functions/get_template_part
     * but allows for arguments to be passed in the form of an array. Each partial
     * defines its own array arguments so view each partial to see what you
     * can/should pass.
     *
     * @param  string $path    The partial's path (relative to the components directory).
     * @param  array  $args    The arguments to pass to the partial.
     * @return string $content The partial template ouput.
     */
    public static function get(string $path, $args = []): string
    {
        self::enqueueScripts($path);
        self::enqueueStyles($path);

        return parent::get("assets/components/$path", $args);
    }

    public static function enqueueScripts(string $partial): void
    {
        $jsPath = "components/$partial/scripts.js";

        if (!empty(\Granola\Asset::extract($jsPath, true))) {
            wp_enqueue_script(
                "$partial-scripts",
                \Granola\Asset::URL($jsPath, true),
                apply_filters("granola/partial/$partial/enqueue_script_dependencies", []),
                apply_filters("granola/partial/$partial/enqueue_script_in_footer", false),
            );
        }
    }

    public static function enqueueStyles(string $partial): void
    {
        $cssPath = "components/$partial/styles.css";

        if (!empty(\Granola\Asset::extract($cssPath, true))) {
            wp_enqueue_style(
                "$partial-styles",
                \Granola\Asset::URL($cssPath, true),
                apply_filters("granola/partial/$partial/enqueue_style_dependencies", []),
            );
        }
    }
}
