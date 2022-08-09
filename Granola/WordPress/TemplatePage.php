<?php

/**
 * Functionality that enables a 'Template' CPT which is used for editing archive
 * (and other special templates) page content.
 */

namespace Granola\WordPress;

class TemplatePage
{
    protected const SLUG = 'granola-template'; // Prefixed to avoid conflicts.

    /**
     * Create Template Page post type and link to configured post types and taxonomies.
     */
    public static function init()
    {
        \add_action('init', [__CLASS__, 'registerPostType']);
        \add_action('admin_bar_menu', [__CLASS__, 'addPostTypeEditToolbarButton'], 80);
        \add_action('admin_bar_menu', [__CLASS__, 'addAdminBarEditToolbarButton'], 80);
        \add_action('admin_bar_menu', [__CLASS__, 'add404AddTemplateAdminBarLink'], 80);
        \add_action('admin_bar_menu', [__CLASS__, 'addTaxonomyAddTemplateAdminBarLink'], 80);
        \add_action('admin_bar_menu', [__CLASS__, 'addViewToolbarButton'], 80);
        \add_action('admin_bar_menu', [__CLASS__, 'removeBlogHomeEditPageButton'], 100);
        \add_filter('granola/wordpress/admin/submenu', [__CLASS__, 'addPostTypeTemplateEditSubmenuLink']);
        \add_action('admin_post_create_template_page', [__CLASS__, 'createTemplatePage']);
    }

    public static function registerPostType(): void
    {
        if (!function_exists('register_extended_post_type')) {
            return;
        }

        \register_extended_post_type(self::SLUG, [
            'public' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'menu_position' => 50, // Below comments, after all other post types.
            'menu_icon' => 'dashicons-welcome-widgets-menus',
            'supports' => [
                'title',
                'editor',
                'revisions',
                'thumbnail',
                'custom-fields',
            ],
            'admin_cols' => [
                'updated' => [
                    'title'      => \__('Updated', 'granola'),
                    'post_field' => 'post_modified',
                    'date_format' => 'Y/m/d \a\t H:i a',
                ],
                'template_for' => [
                    'title' => \__('Template For', 'granola'),
                    'function' => function () {
                        // Nothing passed to this function but can use loop functions/global variables.
                        $object = get_post_meta(get_the_ID(), 'granola-templated-object', true);

                        if (empty($object)) {
                            return;
                        }

                        if ($object instanceof \WP_Term) {
                            echo sprintf(
                                // translators: 1: Taxonomy name. 2: Term archive link.
                                \_x('%s: %s', 'Template post list taxonomy archive link', 'granola'),
                                get_taxonomy($object->taxonomy)->labels->singular_name,
                                \Granola\Component::get('link', [
                                    'url' => \get_term_link($object),
                                    'content' => $object->name,
                                ])
                            );
                        } elseif ($object instanceof \WP_Post_Type) {
                            echo sprintf(
                                // translators: 1: Post type archive link.
                                \_x('Post Type: %s', 'Template post list post type archive link', 'granola'),
                                \Granola\Component::get('link', [
                                    'url' => \get_post_type_archive_link($object->name),
                                    'content' => $object->label,
                                ])
                            );
                        } elseif ($object->name === '404') {
                            echo \_x('Special: 404', 'Template post list 404', 'granola');
                        }
                    }
                ]
            ],
        ], [
            // Override the base names used for labels (optional).
            'singular' => \__('Template Page', 'granola'),
            'plural'   => \__('Template Pages', 'granola'),
            'slug'     => self::SLUG,
        ]);
    }

    /**
     * Filters the global $submenu to add post type edit link(s) to the WP admin bar.
     *
     * @param array $submenu An array of WP admin menu items.
     */
    public static function addPostTypeTemplateEditSubmenuLink($submenu): array
    {
        // Bail early - user doesn't have the right capabilities.
        if (!\current_user_can('edit_pages')) {
            return $submenu;
        }

        $postTypes = self::getTemplatePostTypes();

        foreach ($postTypes as $pt) {
            $template = self::getPage(
                \get_post_type_object($pt)
            );

            // Handle 'post' PT menu key edge case.
            $key = ($pt === 'post') ? 'edit.php' : "edit.php?post_type={$pt}";

            // Bail early - no page found.
            if (
                !empty($template)
                && $template instanceof \WP_Post
                && $template->post_status === 'publish'
            ) {
                $linkArray = [
                    \__('Edit Template', 'granola'),
                    'edit_pages',
                    \get_edit_post_link($template->ID),
                ];
            } else {
                $linkArray = [
                    \__('Add Template', 'granola'),
                    'edit_pages',
                    \add_query_arg([
                        'action' => 'create_template_page',
                        'object_type' => 'wp_post_type',
                        'object_id' => $pt,
                        'nonce' => \wp_create_nonce('create_template_page'),
                    ], \admin_url('admin-post.php')),
                ];
            }

            $submenu[$key][] = $linkArray;
        }

        return $submenu;
    }

    /**
     * Filters the global $submenu to add post type edit link(s) to the WP admin bar.
     *
     * @param WP_Admin_Bar $adminBar An array of WP admin menu items.
     */
    public static function addTaxonomyAddTemplateAdminBarLink($adminBar): void
    {
        if (!\current_user_can('edit_posts') || !\is_admin()) {
            return;
        }

        $screen = \get_current_screen();
        $taxonomies = self::getTemplateTaxonomies();

        // Bail early - not currently editing a valid taxonomy term.
        if (
            !empty($screen->taxonomy)
            && ($screen->base !== 'term' || !in_array($screen->taxonomy, $taxonomies, true))
        ) {
            return;
        }

        $term = self::getTermBeingEdited();

        // Bail early - invalid term page.
        if (empty($term)) {
            return;
        }

        $templatePageId = \get_term_meta($term->term_id, 'template_page', true);
        $templatePage = \get_post($templatePageId);

        // Bail early - template page set already.
        if (
            !empty($templatePage)
            && $templatePage instanceof \WP_Post
            && $templatePage->post_status !== 'trash'
        ) {
            return;
        }

        $adminBar->add_menu([
            'id'    => 'granola-add-template',
            'title' => \__('Add Template', 'granola'),
            'href'  => \add_query_arg([
                'action' => 'create_template_page',
                'object_type' => 'wp_taxonomy',
                'object_id' => $term->term_taxonomy_id,
                'nonce' => \wp_create_nonce('create_template_page'),
            ], \admin_url('admin-post.php')),
            'meta'  => [
                'title' => \__('Add Template', 'granola'),
            ],
        ]);
    }

    /**
     * Filters the global $submenu to add post type edit link(s) to the WP admin bar.
     *
     * @param WP_Admin_Bar $adminBar An array of WP admin menu items.
     */
    public static function add404AddTemplateAdminBarLink($adminBar): void
    {
        if (!\current_user_can('edit_posts') || \is_admin() || !\is_404()) {
            return;
        }

        $templatePageId = \get_option("404_template_page", 0);
        $templatePage = \get_post($templatePageId);

        // Bail early - template page set already.
        if (
            !empty($templatePage)
            && $templatePage instanceof \WP_Post
            && $templatePage->post_status !== 'trash'
        ) {
            return;
        }

        $adminBar->add_menu([
            'id'    => 'granola-add-template',
            'title' => \__('Add Template', 'granola'),
            'href'  => \add_query_arg([
                'action' => 'create_template_page',
                'object_type' => '404',
                'object_id' => '0',
                'nonce' => \wp_create_nonce('create_template_page'),
            ], \admin_url('admin-post.php')),
            'meta'  => [
                'title' => \__('Add Template', 'granola'),
            ],
        ]);
    }

    /**
     * Creates a new template page for a post type, if one does not exist, using the `admin_post_{action}` hook.
     *
     * @link https://developer.wordpress.org/reference/hooks/admin_post_action/
     */
    public static function createTemplatePage(): void
    {
        // Bail early - capability check.
        if (!\current_user_can('edit_pages')) {
            return;
        }

        // Bail early - nonce check.
        $nonce = $_REQUEST['nonce'] ?? '';
        if (\wp_verify_nonce($nonce, 'create_template_page') === false) {
            \wp_die(
                \__('Invalid request.', 'granola')
            );
        }

        if (empty($_REQUEST['object_type']) || !isset($_REQUEST['object_id'])) {
            // Bail early - required args not set.
            \wp_safe_redirect(
                \admin_url('edit.php?post_type=' . self::SLUG)
            );
            exit;
        }

        $objectId = (string) $_REQUEST['object_id'];
        $objectType = (string) $_REQUEST['object_type'];
        if ($objectType === 'wp_post_type') {
            $object = \get_post_type_object($objectId);
            $objectName = $object->labels->name;
        } elseif ($objectType === 'wp_taxonomy') {
            $object = \get_term_by('term_taxonomy_id', $objectId);
            $objectName = $object->name;
        } elseif ($objectType === '404') {
            $object = (object) [
                'name' => '404',
                'type' => 'special',
            ];
            $objectName = '404 Not Found';
        }

        // Bail early - invalid object.
        if (!is_object($object)) {
            \wp_safe_redirect(
                \admin_url('edit.php?post_type=' . self::SLUG)
            );
            exit;
        }

        // Create new template page for this post type.
        $templatePageId = \wp_insert_post([
            'post_title'   => $objectName,
            'post_status'  => 'publish',
            'post_type'    => self::SLUG,
            'meta_input'   => [
                'granola-templated-object' => $object,
            ],
        ]);

        // Bail early - post creation failed somehow.
        if (empty($templatePageId) || \is_wp_error($templatePageId)) {
            \wp_safe_redirect(
                \admin_url('edit.php?post_type=' . self::SLUG)
            );
            exit;
        }

        if ($object instanceof \WP_Post_Type) {
            // Add connection from post type to template page.
            \update_option("{$object->name}_template_page", $templatePageId, false);
        } elseif ($object instanceof \WP_Term) {
            // Add connection from term to template page.
            \update_term_meta($object->term_id, 'template_page', $templatePageId);
        } elseif ($object->name === '404') {
            \update_option("404_template_page", $templatePageId, false);
        }

        \wp_safe_redirect(
            \get_edit_post_link($templatePageId, 'redirect')
        );
        exit;
    }

    /**
     * Add a 'View Template' button to the WP admin bar when editing a granola-template post.
     *
     * @link https://developer.wordpress.org/reference/hooks/admin_bar_menu/
     *
     * @param WP_Admin_Bar $adminBar The WP_Admin_Bar instance, passed by reference.
     */
    public static function addViewToolbarButton($adminBar): void
    {
        if (!\current_user_can('edit_posts') || !\is_admin()) {
            return;
        }

        $screen = \get_current_screen();

        // Bail early - not currently on a screen related to granola-template.
        if (empty($screen->post_type) || $screen->post_type !== self::SLUG) {
            return;
        }

        // Bail early - not currently on a post edit screen.
        if (empty($screen->base) || $screen->base !== 'post') {
            return;
        }

        // Retrieves the object (WP_Post_Type|WP_Term) linked to this granola-template post.
        $object = \get_post_meta(\get_the_ID(), 'granola-templated-object', true);

        if (empty($object)) {
            return;
        }

        if ($object instanceof \WP_Term) {
            $link = \get_term_link($object);
        } elseif ($object instanceof \WP_Post_Type) {
            $link = \get_post_type_archive_link($object->name);
        } else {
            return; // Bail early - invalid post meta.
        }

        $adminBar->add_menu([
            'id'    => 'granola-view-template',
            'title' => \__('View Template', 'granola'),
            'href'  => $link,
            'meta'  => [
                'title' => \__('View Template', 'granola'),
                'class' => 'granola-ab-item granola-view-template'
            ],
        ]);
    }

    /**
     * Add an 'Edit Template' button to the WP admin bar when editing a valid taxonomy
     * term or viewing a valid post type list on the back-end.
     *
     * @link https://developer.wordpress.org/reference/hooks/admin_bar_menu/
     *
     * @param WP_Admin_Bar $adminBar The WP_Admin_Bar instance, passed by reference.
     */
    public static function addAdminBarEditToolbarButton($adminBar): void
    {
        if (!\current_user_can('edit_posts') || !\is_admin()) {
            return;
        }

        $screen = \get_current_screen();

        // Bail early - wrong screen.
        if ($screen->base !== 'term' && $screen->base !== 'edit') {
            return;
        }

        $postTypes = self::getTemplatePostTypes();
        $taxonomies = self::getTemplateTaxonomies();

        if (
            $screen->base === 'term'
            && !empty($screen->taxonomy)
            && !in_array($screen->taxonomy, $taxonomies, true)
        ) {
            return; // Bail early - currently editing an invalid taxonomy term.
        } elseif (
            $screen->base === 'edit'
            && !empty($screen->post_type)
            && !in_array($screen->post_type, $postTypes, true)
        ) {
            return; // Bail early - currently viewing an invalid post type list.
        }

        if (!empty($screen->taxonomy) && $screen->base === 'term') {
            $term = self::getTermBeingEdited();

            // Bail early - invalid term page.
            if (empty($term)) {
                return;
            }

            $templatePageId = \get_term_meta($term->term_id, 'template_page', true);
        } elseif (!empty($screen->post_type) && $screen->base === 'edit') {
            $templatePageId = \get_option("{$screen->post_type}_template_page", 0);
        }

        if (empty($templatePageId)) {
            return;
        }

        $templatePage = \get_post($templatePageId);

        if (
            empty($templatePage)
            || !($templatePage instanceof \WP_Post)
            || $templatePage->post_status !== 'publish'
        ) {
            return;
        }

        $adminBar->add_menu([
            'id'    => 'granola-edit-template',
            'title' => sprintf(
                // translators: 1: opening html tags. 2: closing html tags.
                \_x('%sEdit Template Content%s', 'Admin bar edit link', 'granola'),
                '<span class="ab-icon" aria-hidden="true"></span><span class="ab-label">',
                '</span>'
            ),
            'href'  => \get_edit_post_link($templatePage),
            'meta'  => [
                'title' => \_x('Edit Template Content', 'Admin bar edit link title', 'granola'),
                'class' => 'granola-ab-item granola-edit-template'
            ],
        ]);
    }

    /**
     * Add an 'Edit Template' button to the WP admin bar when viewing a post type
     * template on the front-end, which is linked to a granola-template post.
     *
     * @link https://developer.wordpress.org/reference/hooks/admin_bar_menu/
     *
     * @param WP_Admin_Bar $adminBar The WP_Admin_Bar instance, passed by reference.
     */
    public static function addPostTypeEditToolbarButton($adminBar): void
    {
        if (!\current_user_can('edit_posts') || \is_admin()) {
            return;
        }

        // Bail early - not on an template page.
        if (!\is_archive() && !\is_home() && !\is_404()) {
            return;
        }

        $templatePage = self::getPage(
            \Granola\WordPress\PageObject::get()
        );

        if (
            empty($templatePage)
            || !($templatePage instanceof \WP_Post)
            || $templatePage->post_status !== 'publish'
        ) {
            return;
        }

        $adminBar->add_menu([
            'id'    => 'granola-edit-template',
            'title' => sprintf(
                // translators: 1: opening html tags. 2: closing html tags.
                \_x('%sEdit Template Content%s', 'Admin bar edit link', 'granola'),
                '<span class="ab-icon" aria-hidden="true"></span><span class="ab-label">',
                '</span>'
            ),
            'href'  => \get_edit_post_link($templatePage),
            'meta'  => [
                'title' => \_x('Edit Template Content', 'Admin bar edit link title', 'granola'),
                'class' => 'granola-ab-item granola-edit-template'
            ],
        ]);
    }

    /**
     * Remove the default 'Edit Page' button from the WP admin bar when viewing the 'Post' post type
     * template on the front-end, which is linked to a 'Page' post via core settings.
     *
     * Hooked in after the button has been added (priority: >80).
     *
     * @link https://developer.wordpress.org/reference/hooks/admin_bar_menu/
     * @see /wp-includes/admin-bar.php:847
     *
     * @param WP_Admin_Bar $adminBar The WP_Admin_Bar instance, passed by reference.
     */
    public static function removeBlogHomeEditPageButton($adminBar): void
    {
        if (!\current_user_can('edit_posts') || \is_admin()) {
            return;
        }

        // Bail early - not on an template page.
        if (!\is_archive() && !\is_home()) {
            return;
        }

        $adminBar->remove_menu('edit');
    }

    /**
     * Retrieves the filtered page content for a Post Type, Taxonomy, or Term, if an template page is set.
     *
     * @param object $object A WP_Post_Type, WP_Taxonomy, or WP_Term which might have an template page set.
     * @return string|bool The content of the template page, if found, false otherwise.
     */
    public static function getContent($object)
    {
        if (!\Granola\Helpers::isValidClass($object, ['WP_Post_Type', 'WP_Taxonomy', 'WP_Term', 'WP_Query'])) {
            return false;
        }

        $templatePage = self::getPage($object);

        if (empty($templatePage)) {
            return false;
        }

        return \apply_filters('the_content', $templatePage->post_content);
    }


    /**
     * Retrieves the template content page setting, if it exists.
     *
     * @param object $object A WP_Post_Type, WP_Taxonomy, or WP_Term object which might have an template page set.
     * @return WP_Post|bool The post object, or false if the $object argument isn't valid or no template is set.
     */
    public static function getPage($object)
    {

        if (!\Granola\Helpers::isValidClass($object, ['WP_Post_Type', 'WP_Taxonomy', 'WP_Term', 'WP_Query'])) {
            return false;
        }

        if ($object instanceof \WP_Term) {
            $templateId = \get_field('template_page', $object);
        } elseif ($object instanceof \WP_Query && $object->is_404()) {
            $templateId = \get_option("404_template_page", 0);
        } else {
            $templateId = \get_option("{$object->name}_template_page", 0);
        }

        if (!empty($templateId)) {
            return \get_post($templateId);
        }

        return false;
    }

    /**
     * Get the term currently being edited on the edit.php screen in the WordPress admin.
     *
     * @return WP_Term|null The term object or null on failure.
     */
    public static function getTermBeingEdited()
    {
        global $taxnow;

        if (empty($taxnow) || empty($_GET['tag_ID'])) {
            return null;
        }

        $term_id = \absint($_GET['tag_ID']);
        $term    = \get_term($term_id, $taxnow);

        return $term instanceof \WP_Term ? $term : null;
    }

    /**
     * Retrieves the list of taxonomy names that can have a template assigned.
     *
     * @return array An array of taxonomy names that can have a template assigned.
     */
    public static function getTemplateTaxonomies(): array
    {
        return \apply_filters('granola/templates/taxonomies', []);
    }

    /**
     * Retrieves the list of post type names that can have a template assigned.
     *
     * @return array An array of post type names that can have a template assigned.
     */
    public static function getTemplatePostTypes(): array
    {
        return \apply_filters('granola/templates/post-types', []);
    }
}
