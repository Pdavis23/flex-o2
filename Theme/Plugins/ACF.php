<?php

namespace Theme\Plugins;

class ACF
{
    public static function init(): void
    {
        \add_action('acf/init', [__CLASS__, 'optionPages']);
        \add_action('acf/init', [__CLASS__, 'setACFGoogleAPIKey']);

        // Handles disabling Gutenberg on flexible content template
        \add_filter('gutenberg_can_edit_post_type', [__CLASS__, 'disableGutenberg'], 10, 2);
        \add_filter('use_block_editor_for_post_type', [__CLASS__, 'disableGutenberg'], 10, 2);

        // Define custom wysiwyg toolbar.
        \add_filter('acf/fields/wysiwyg/toolbars', [__CLASS__, 'filterEditorToolbarTypes']);

        // Filter the choices for any ACF field named `theme_background_color` to automatically add
        // color options from the color palette/theme.json file.
        // Note: These values will only be updated in the JSON file when the field group is saved.
        \add_filter('acf/load_field/name=theme_background_color', [__CLASS__, 'loadColorFieldChoices']);
        \add_filter('acf/load_field/name=item_theme_background_color', [__CLASS__, 'loadColorFieldChoices']);

        // Fix a long-standing issue with ACF, where fields sometimes aren't shown in previews (ie. from Preview > Open in new tab).
        // More info at https://support.advancedcustomfields.com/forums/topic/custom-fields-on-post-preview/#post-150273
        if (current_user_can('edit_posts') && class_exists('acf_revisions')) {
            $acf_revs_cls = acf()->revisions;
            \remove_filter('acf/validate_post_id', [$acf_revs_cls, 'acf_validate_post_id', 10]);
        }
    }

    public static function optionPages(): void
    {
        // ----------------------------------------------------
        // If ACF Exists, lets create all the options pages
        // that have been configured
        // ----------------------------------------------------
        if (function_exists('acf_add_options_page') && defined('GRANOLA_ACF_OPTIONS_PAGES')) {
            // ----------------------------------------------------
            // Add a default options page to nest everything under
            // ----------------------------------------------------
            \acf_add_options_page();

            // ----------------------------------------------------
            // Loop through the pages configured and create them
            // ----------------------------------------------------
            foreach (GRANOLA_ACF_OPTIONS_PAGES as $page) {
                \acf_add_options_sub_page($page);
            }
        }
    }

    /**
     * Set ACF's Google API key from a site option, if it exists.
     */
    public static function setACFGoogleAPIKey(): void
    {
        $option = \get_field('google_api_key', 'option');

        if (empty($option)) {
            return;
        }

        \acf_update_setting('google_api_key', $option);
    }

    public static function loadColorFieldChoices(array $field): array
    {
        $field['choices']['none'] = __('None', 'granola');

        if (defined('GRANOLA_COLOR_PALETTE')) {
            foreach (GRANOLA_COLOR_PALETTE as $color) {
                $field['choices'][$color['slug']] = $color['name'];
            }
        }

        return $field;
    }

    public static function disableGutenberg(bool $can_edit, string $post_type): bool
    {
        if (!(\is_admin() && !empty($_GET['post']))) {
            return $can_edit;
        }

        if (self::disableEditor($_GET['post'])) {
            $can_edit = false;
        }

        return $can_edit;
    }

    public static function disableEditor($id = false): bool
    {
        $excluded_templates = [
            // 'page-templates/example-template.php',
        ];

        if (empty($id)) {
            return false;
        }

        $id = intval($id);
        $template = \get_page_template_slug($id);

        return in_array($template, $excluded_templates);
    }

    /**
     * Filters ACF wysiwyg toolbar types array.
     *
     * @see /advanced-custom-fields-pro/includes/fields/class-acf-field-wysiwyg.php
     * @link https://www.advancedcustomfields.com/resources/customize-the-wysiwyg-toolbars/
     *
     * @param array[] $toolbars An array of ACF TinyMCE wysiwyg toolbar types.
     * @return array[] $toolbars The filtered array of ACF TinyMCE wysiwyg toolbar types.
     */
    public static function filterEditorToolbarTypes($toolbars): array
    {
        // Remove ACF defaults.
        unset($toolbars['Basic']);
        unset($toolbars['Full']);

        // Define 'Basic' toolbar with custom selection of TinyMCE buttons.
        $toolbars['Basic Formatting'] = [
            1 => \apply_filters('granola/acf/fields/wysiwyg/toolbars/basic', [
                'bold',
                'italic',
                'link',
                'unlink',
                'removeformat',
                'undo',
                'redo'
            ]),
        ];

        // Define 'Extended' toolbar with wider selection of TinyMCE buttons,
        // Includes bulleted lists and heading/paragraph formatting.
        $toolbars['Extended Formatting'] = [
            1 => \apply_filters('granola/acf/fields/wysiwyg/toolbars/extended', [
                'formatselect',
                'bold',
                'italic',
                'bullist',
                'numlist',
                'link',
                'unlink',
                'removeformat',
                'undo',
                'redo'
            ]),
        ];


        // Define 'Bold' toolbar.
        $toolbars['Heading'] = [
            1 => \apply_filters('granola/acf/fields/wysiwyg/toolbars/basic', [
                'bold',
            ]),
        ];

        return $toolbars;
    }
}
