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
                                <?php foreach ($item['content'] as $key => $field) { ?>
                                    <?php if (!empty($field) && $key !== 'website') { ?>
                                        <div class="resellers__item__<?= $key; ?>">
                                            <div class="resellers__item__icon">
                                                <?= \Granola\SVG::get('icons/' . $key . '.svg'); ?>
                                            </div>
                                            <div class="resellers__item__value">
                                                <?= wp_kses_post($field); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</section>
