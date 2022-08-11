<section <?= \Granola\Helpers::buildAttributes($args['attributes']); ?>>
    <div class="divider__inner">
        <?php if (!empty($args['icon'])) { ?>
            <div class="divider__icon">
                <?= \Granola\SVG::get('icons/' . $args['icon'] . '.svg') ?>
            </div>
        <?php } ?>
    </div>
</section>
