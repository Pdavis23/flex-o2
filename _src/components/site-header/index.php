<header class="<?= \Granola\Helpers::buildClasses($args['classes']) ?>">
    <div class="site-header__inner">
        <div class="site-header__top">
            <?= \Granola\Component::get('link', [
                'url' => home_url('/'),
                'classes' => ['site-header__logo', 'img-fit'],
                'content' => \Granola\SVG::get('logo.svg'),
                'content_filter' => false,
                'attributes' => [
                    'aria-label' => get_bloginfo('name'),
                ],
            ]); ?>

            <div class="site-header__strapline">
                <?= wp_kses_post(get_bloginfo('description')); ?>
            </div>



            <?php echo \Granola\Component::get('burger', [
                'classes' => [
                    'site-header__burger',
                    'js-site-header-toggle',
                ],
                'attributes' => [
                    'aria-label' => __('Main menu button', 'granola'),
                    'aria-controls' => 'main-menu',
                    'aria-expanded' => 'false',
                ]
            ]); ?>
        </div>

        <div class="site-header__bottom">
            <?php if (!empty($args['content']['call_to_action_1'])) { ?>
                <div class="site-header__widgets">
                    <?= \Granola\Component::get('link', $args['content']['call_to_action_1']); ?>
                </div>
            <?php } ?>

            <?php if (!empty($args['languages'])) { ?>
                <?php if (count($args['languages']) > 3) { ?>
                    <div class="site-header__language-switcher reveal">
                        <button class="site-header__language-switcher__button js-reveal-item">
                            <span class="screen-reader-text">Select language, current language: <?= $args['current_language']; ?></span>
                            <span class="label"><?= $args['current_language']; ?></span>
                        </button>
                        <div class="site-header__language-switcher__inner  reveal__content">
                            <ul class="site-header__language-switcher__items">
                                <?php foreach ($args['languages'] as $key => $language) { ?>
                                    <li class="site-header__language-switcher__item <?= ($language['current'] ? 'is-active' : null) ?>">
                                        <?= \Granola\Component::get('link', $language); ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="site-header__language-switcher">
                        <button class="site-header__language-switcher__button g-button">
                            <span class="screen-reader-text">Select language, current language: <?= $args['current_language']; ?></span>
                            <?= \Granola\Component::get('link', $args['languages'][0]); ?>
                        </button>
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if (has_nav_menu('header')) { ?>
                <nav class="site-header__navigation">
                    <?php wp_nav_menu([
                        'theme_location' => 'header',
                        'container' => '',
                        'menu_class' => 'site-header__main-menu  site-header__navigation__menu',
                        'menu_id' => 'main-menu', // don't delete it, needed for 'aria-controls' in burger button.
                        'fallback_cb' => false,
                        'walker' => new \Theme\WordPress\MenuWalker(),
                    ]); ?>
                </nav>
            <?php } ?>
        </div>
    </div>
</header>
