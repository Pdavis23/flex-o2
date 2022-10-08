<?php if (!empty($args['items'])) { ?>
    <section <?= \Granola\Helpers::buildAttributes($args['attributes']); ?>>
        <div class="benefits__inner">
            <?php if (!empty($args['heading']) || !empty($args['subheading'])) { ?>
                <div class="benefits__header">
                    <?php if (!empty($args['heading'])) { ?>
                        <?= \Granola\Component::get('heading', [
                            'heading' => $args['heading'],
                            'id' => $args['heading_id'] ?? null,
                            'classes' => ['benefits__heading'],
                        ]); ?>
                    <?php } ?>

                    <?php if (!empty($args['subheading'])) { ?>
                        <div class="benefits__subheading">
                            <?= wp_kses_post($args['subheading']); ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="benefits__items">
                <?php foreach ($args['items'] as $key => $item) { ?>
                    <div class="benefits__item">
                        <div class="benefits__icon has-background has-<?= $item['color']; ?>-background-color">
                            <?php if (!empty($item['icon'])) { ?>
                                <?= \Granola\SVG::get('icons/' . $item['icon'] . '.svg'); ?>
                            <?php } ?>
                        </div>
                        <div class="benefits__content">
                            <?php if (!empty($item['content'])) { ?>
                                <div class="benefits__content-inner">
                                    <?= wp_kses_post($item['content']); ?>
                                </div>
                            <?php } ?>

                            <?php if (!empty($item['link'])) { ?>
                                <div class="benefits__link">
                                    <?= \Granola\Component::get('link', $item['link']); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
    </section>
<?php } ?>
