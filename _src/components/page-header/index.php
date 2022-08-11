<?php if ($args['show']) { ?>
    <div <?= \Granola\Helpers::buildAttributes($args['attributes']); ?>>
        <?php if (!empty($args['content']['back-link'])) { ?>
            <div class="page-header__back-link">
                <div class="page-header__back-link__inner">
                    <?= \Granola\Component::get('link', $args['content']['back-link']); ?>
                </div>
            </div>
        <?php } ?>

        <div class="page-header__inner <?= (isset($args['color']) ? $args['color'] : null); ?>">
            <?php if (!empty($args['content']['inset-image'])) { ?>
                <div class="page-header__inset-image">
                    <div class="page-header__inset-image-inner img-fit">
                        <?php echo $args['content']['inset-image']; ?>
                    </div>
                </div>
            <?php } ?>

            <?php if (!empty($args['content']['secondary-image'])) { ?>
                <div class="page-header__inset-image image-secondary">
                    <div class="page-header__inset-image-inner img-fit">
                        <?= \Granola\Component::get('image', $args['content']['secondary-image']); ?>
                    </div>
                </div>

            <?php } ?>

            <div class="page-header__content">
                <?php if (!empty($args['content']['heading'])) { ?>
                    <?= \Granola\Component::get('heading', [
                        'heading' => $args['content']['heading'],
                        'classes' => ['page-header__heading'],
                        'el'      => 'h1',
                    ]); ?>
                <?php } ?>

                <?php if (!empty($args['content']['screen-reader-heading'])) { ?>
                    <?= \Granola\Component::get('heading', [
                        'heading' => $args['content']['screen-reader-heading'],
                        'classes' => ['screen-reader-text'],
                        'el'      => 'h1',
                    ]); ?>
                <?php } ?>

                <?php if (!empty($args['content']['subheading'])) { ?>
                    <div class="page-header__subheading">
                        <?= wp_kses_post($args['content']['subheading']); ?>
                    </div>
                <?php } ?>

                <?php if (!empty($args['content']['meta'])) { ?>
                    <div class="page-header__meta">
                        <?= wp_kses_post($args['content']['meta']); ?>
                    </div>
                <?php } ?>

                <?php if (!empty($args['content']['labels'])) { ?>
                    <div class="page-header__labels">
                        <div class="page-header__labels__items flex-list">
                            <?php foreach ($args['content']['labels'] as $label) {
                                echo \Granola\Component::get('link', [
                                    'title' => $label['name'],
                                    'url' => $label['url'],
                                    'classes' => [
                                        'g-button',
                                        'g-button--label',
                                    ],
                                ]);
                            } ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (!empty($args['content']['buttons'])) { ?>
                    <div class="page-header__buttons">
                        <ul class="flex-list">
                            <?php foreach ($args['content']['buttons'] as $button) { ?>
                                <li class="flex-list__item">
                                    <?= \Granola\Component::get('link', $button); ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>

            <?php if (!empty($args['content']['secondary-image'])) { ?>
                <div class="page-header__divider">
                    <?= \Granola\Component::get('divider', [
                        'icon' => 'ribbon-short',
                    ]); ?>
                    <?= \Granola\Component::get('divider', [
                        'icon' => 'ribbon-long',
                        'icon_color' => 'teal',
                    ]); ?>
                </div>

            <?php } ?>
        </div>


        <?= \Granola\Component::get('language', [
            'post_id' => $args['post_id'],
        ]); ?>

        <?php if (!empty($args['content']['background-image'])) { ?>
            <div class="page-header__background-image">
                <div class="page-header__background-image-inner img-fit">
                    <?php echo $args['content']['background-image']; ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>
