<section <?= \Granola\Helpers::buildAttributes($args['attributes']); ?>>
    <div class="team__inner">
        <?php if (!empty($args['heading'])) { ?>
            <?= \Granola\Component::get('heading', [
                'heading' => $args['heading'],
                'classes' => ['team__heading'],
            ]); ?>
        <?php } ?>

        <?php if (!empty($args['subheading'])) { ?>
            <div class="team__content">
                <?= wp_kses_post($args['subheading']); ?>
            </div>
        <?php } ?>

        <?php if (!empty($args['team'])) { ?>
            <div class="team__items">
                <?php foreach ($args['team'] as $key => $item) { ?>
                    <div class="team__item has-background has-<?= $item['background_color']; ?>-background-color">
                        <?php if (!empty($item['name'])) { ?>
                            <?= \Granola\Component::get('heading', [
                                'heading' => $item['name'],
                                'classes' => ['team__item__heading'],
                                'el' => 'h4',
                            ]); ?>
                        <?php } ?>


                        <?php if (!empty($item['content']['image'])) { ?>
                            <div class="team__item__image img-fit">
                                <?= \Granola\Component::get('image', ['ID' => $item['content']['image']]); ?>
                            </div>
                        <?php } ?>

                        <?php if (!empty($item['content'])) { ?>
                            <div class="team__item__inner">
                                <?php foreach ($item['content'] as $key => $field) { ?>
                                    <?php if (!empty($field) && $key !== 'website' && $key !== 'image') { ?>
                                        <div class="team__item__<?= $key; ?>">
                                            <div class="team__item__icon">
                                                <?= \Granola\SVG::get('icons/' . $key . '.svg'); ?>
                                            </div>
                                            <div class="team__item__value">
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
