<footer class="<?= \Granola\Helpers::buildClasses($args['classes']) ?>">
    <div class="site-footer__inner alignwide">
        <div class="site-footer__top">
            <div class="site-footer__logo">
                <?= \Granola\Component::get('link', [
                    'url' => home_url('/'),
                    'classes' => ['site-header__logo', 'img-fit'],
                    'content' => \Granola\SVG::get('logo.svg'),
                    'content_filter' => false,
                    'attributes' => [
                        'aria-label' => get_bloginfo('name'),
                    ],
                ]); ?>
            </div>

            <?php if ($bottom_text = get_field('footer_text_bottom', 'option')) { ?>
                <div class="site-footer__bottom-text">
                    <?= wp_kses_post($bottom_text); ?>
                </div>
            <?php } ?>

        </div>

        <div class="site-footer__bottom">
            <div class="site-footer__menu site-footer__menu-1">
                <?php if (has_nav_menu('footer-1')) : ?>
                    <?= \Granola\Component::get('heading', [
                        'heading' => __('Key information', 'granola'),
                        'classes' => ['site-footer__bottom__heading'],
                        'el' => 'h5',
                    ]); ?>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'footer-1',
                        'depth' => 1,
                        'menu_class' => 'site-footer__menu__inner',
                    )); ?>
                <?php endif; ?>
            </div>
            <div class="site-footer__bottom__inner">
                <?= \Granola\Component::get('heading', [
                    'heading' => __('Contact', 'granola'),
                    'classes' => ['site-footer__bottom__heading'],
                    'el' => 'h5',
                ]); ?>
                <?php if ($bottom_text = get_field('site_email_address', 'option')) { ?>
                    <div class="site-footer__bottom-text">
                        <?= wp_kses_post($bottom_text); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</footer>
