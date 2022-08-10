<?php if (!empty($args['items'])) { ?>
    <section <?= \Granola\Helpers::buildAttributes($args['attributes']); ?>>
        <div class="links__inner">
            <?php if (!empty($args['heading'])) { ?>
                <?= \Granola\Component::get('heading', [
                    'heading' => $args['heading'],
                    'id' => $args['heading_id'] ?? null,
                    'classes' => ['links__heading'],
                ]); ?>
            <?php } ?>
            <?php if (!empty($args['subheading'])) { ?>
                <div class="links__content">


                    <?php if (!empty($args['subheading'])) { ?>
                        <div class="links__subheading">
                            <?= wp_kses_post($args['subheading']); ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="links__items">
                <?php foreach ($args['items'] as $key => $item) { ?>
                    <div class="links__item">
                        <?php if (!empty($item['content'])) { ?>
                            <div class="links__item__icon">
                                <?= \Granola\SVG::get('icons/tick.svg'); ?>
                            </div>
                            <div class="links__item__content">
                                <?= wp_kses_post($item['content']); ?>
                            </div>
                        <?php } ?>
                        <?php if (!empty($item['link'])) { ?>
                            <div class="links__item__link">
                                <?= \Granola\Component::get('link', $item['link']); ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>


    </section>
<?php } ?>
