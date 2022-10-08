<section <?= \Granola\Helpers::buildAttributes($args['attributes']); ?>>
    <div class="resellers__inner">
        <?php if (!empty($args['heading'])) { ?>
            <?= \Granola\Component::get('heading', [
                'heading' => $args['heading'],
                'classes' => ['resellers__heading'],
            ]); ?>
        <?php } ?>

        <?php if (!empty($args['subheading'])) { ?>
            <div class="resellers__content">
                <?= wp_kses_post($args['subheading']); ?>
            </div>
        <?php } ?>

        <?php if (!empty($args['resellers'])) { ?>
            <div class="resellers__items">
                <?php foreach ($args['resellers'] as $key => $item) { ?>
                    <div class="resellers__item has-background has-sand-background-color">
                        <?php if (!empty($item['name'])) { ?>
                            <?= \Granola\Component::get('heading', [
                                'heading' => $item['name'],
                                'classes' => ['resellers__item__heading', 'g-button--arrow'],
                                'el' => 'h4',
                                'link' => $item['content']['website'],
                            ]); ?>
                        <?php } ?>

                        <?php if (!empty($item['content'])) { ?>
                            <div class="resellers__item__inner">
                                <?php if (!empty($item['content']['address'])) { ?>
                                    <div class="resellers__item__address">
                                        <div class="resellers__item__icon">
                                            <?= \Granola\SVG::get('icons/address.svg'); ?>
                                        </div>
                                        <div class="resellers__item__value">
                                            <?= wpautop($item['content']['address']); ?>
                                            <?php if (!empty($item['content']['country'])) { ?>
                                                <span class="resellers__item__country">
                                                    <?= wp_kses_post($item['content']['country']); ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>



                                <?php if (!empty($item['content']['website'])) { ?>
                                    <div class="resellers__item__website">
                                        <div class="resellers__item__icon">
                                            <?= \Granola\SVG::get('icons/website.svg'); ?>
                                        </div>
                                        <div class="resellers__item__value">
                                            <?= \Granola\Component::get('link', [
                                                'title' => pll__('Website'),
                                                'target' => '__blank',
                                                'url' => wp_kses_post($item['content']['website']),
                                            ]); ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if (!empty($item['content']['email'])) { ?>
                                    <div class="resellers__item__email">
                                        <div class="resellers__item__icon">
                                            <?= \Granola\SVG::get('icons/email.svg'); ?>
                                        </div>
                                        <div class="resellers__item__value">
                                            <?= \Granola\Component::get('link', [
                                                'title' => wp_kses_post($item['content']['email']),
                                                'url' => 'mailto: ' . wp_kses_post($item['content']['email']),
                                            ]); ?>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php if (!empty($item['content']['phone'])) { ?>
                                    <div class="resellers__item__phone">
                                        <div class="resellers__item__icon">
                                            <?= \Granola\SVG::get('icons/phone.svg'); ?>
                                        </div>
                                        <div class="resellers__item__value">
                                            <?= \Granola\Component::get('link', [
                                                'title' => wp_kses_post($item['content']['phone']),
                                                'url' => 'tel: ' . wp_kses_post($item['content']['phone']),
                                            ]); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</section>
