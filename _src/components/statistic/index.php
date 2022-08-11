<section <?= \Granola\Helpers::buildAttributes($args['attributes']); ?>>
    <div class="statistic__inner">
        <?php if (!empty($args['statistic_value'])) { ?>
            <?= \Granola\Component::get('heading', [
                'heading' => $args['statistic_value'],
                'classes' => ['statistic__value'],
                'el' => 'h3',
            ]); ?>
        <?php } ?>

        <?php if (!empty($args['statistic_label'])) { ?>
            <div class="statistic__label">
                <?= wp_kses_post($args['statistic_label']); ?>
            </div>
        <?php } ?>

        <?php if (!empty($args['icon'])) { ?>
            <div class="statistic__icon">
                <?= \Granola\SVG::get('icons/' . $args['icon'] . '.svg') ?>
            </div>
        <?php } ?>
    </div>
</section>
