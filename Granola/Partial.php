<?php

namespace Granola;

class Partial
{
    // Add some default classes to components
    public static function init(): void
    {
        add_filter('granola/partial/before_custom_filters', function ($args) {
            return $args;
        });

        add_filter('granola/partial/after_custom_filters', function ($args) {
            $args = self::buildPartialClasses($args);
            $args = self::buildPartialAttributes($args);

            return $args;
        });
    }

    /**
     * Retrieve a partial from the theme and pass arguments to it.
     *
     * Like https://developer.wordpress.org/reference/functions/get_template_part
     * but allows for arguments to be passed in the form of an array. Each partial
     * defines its own array arguments so view each partial to see what you
     * can/should pass.
     *
     * @param  string $path    The partial's path (relative to the root directory).
     * @param  array  $args    The arguments to pass to the partial.
     * @return string $content The partial template ouput.
     */
    public static function get(string $path, $args = []): string
    {
        // Push the current partial on to the stack
        \Granola\Partial::pushPartial($path, $args);

        // Allow us to filter the data going through the partial get function
        $args = apply_filters('granola/partial/before_custom_filters', $args);
        $args = apply_filters('granola/partial/' . $path, $args);
        $args = apply_filters('granola/partial/after_custom_filters', $args);

        $path = \Granola\Paths::resolve($path);

        ob_start();

        // Render the partial
        require $path;

        // Pop the current partial off the stack
        self::popPartial();

        return ob_get_clean();
    }


    public static function partialStack(): array
    {
        self::initPartialStack();

        global $granolaPartialStack;

        return $granolaPartialStack;
    }

    public static function currentPartial(): array
    {
        self::initPartialStack();

        global $granolaPartialStack;

        if (!empty($granolaPartialStack)) {
            return array_values(array_slice($granolaPartialStack, -1))[0];
        }

        return [];
    }

    public static function pushPartial(string $partial, $args = ''): void
    {
        self::initPartialStack();

        global $granolaPartialStack;

        $granolaPartialStack[] = [
            'partial' => $partial,
            'args' => $args
        ];
    }

    public static function popPartial(): void
    {
        self::initPartialStack();

        global $granolaPartialStack;

        array_pop($granolaPartialStack);
    }

    public static function initPartialStack(): void
    {
        global $granolaPartialStack;

        if (!is_array($granolaPartialStack)) {
            $granolaPartialStack = [];
        }
    }

    /**
     * Generate args for a component from block attributes and, optionally, associated ACF fields.
     * Typically run during the block render_callback.
     * @param $block array The array of block info passed to render_callback.
     * @param $acf_fields array|bool Usually whatever is returned from get_fields().
     */
    public static function generateArgsFromBlock(array $block, $acf_fields): array
    {
        $args = is_array($acf_fields) ? $acf_fields : [];

        if (!empty($block['anchor'])) {
            $args['anchor'] = $block['anchor'];
        }

        if (!empty($block['className'])) {
            $args['classes'] = [
                $block['className'],
            ];
        }

        if (!empty($block['align'])) {
            $args['align'] = $block['align'];
        }

        if (!empty($block['name'])) {
            $args['editor_block_name'] = $block['name'];
        }

        // $args['backgroundColor'] - the built-in WP Background Color settings.
        if (!empty($block['backgroundColor'])) {
            $args['background_color'] = $block['backgroundColor'];
        }

        // $args['theme_background_color'] - our acf field with auto-loaded options.
        if (!empty($block['theme_background_color'])) {
            $args['background_color'] = $block['theme_background_color'];
        }

        return $args;
    }

    /**
     * Sets a number of commonly used classes from partial args that are passed
     * Also adds the classes array to the attributes array for output
     * Run after the partial filtering
     * @param $args passed through from the granola/partial/components/block filter.
     */
    public static function buildPartialClasses(array $args): array
    {
        $args['classes'] = $args['classes'] ?? [];

        if (!empty($args['align'])) {
            $args['classes'][] = 'align' . $args['align'];
        }

        if (!empty($args['background_color']) && $args['background_color'] !== 'none') {
            $args['classes'][] = 'has-background';
            $args['classes'][] = 'has-' . $args['background_color'] . '-background-color';
        }

        return $args;
    }

    /**
     * Adds the classes array to the attributes array.
     * Run after the partial filtering
     * @param $args passed through from the granola/partial/components/block filter.
     */
    public static function buildPartialAttributes(array $args): array
    {
        $args['attributes'] = $args['attributes'] ?? [];

        if (!is_array($args['attributes'])) {
            return $args;
        }

        if (!empty($args['classes'])) {
            $args['attributes']['class'] = $args['classes'];
        }

        return $args;
    }
}
