<section <?= \Granola\Helpers::buildAttributes($args['attributes']); ?>>
    <div class="form__inner">
        <?php if (!empty($args['heading'])) { ?>
            <?= \Granola\Component::get('heading', [
                'heading' => $args['heading'],
                'classes' => ['form__heading'],
            ]); ?>
        <?php } ?>

        <?php if (!empty($args['subheading'])) { ?>
            <div class="form__content">
                <?= wp_kses_post($args['subheading']); ?>
            </div>
        <?php } ?>

        <?php if (!empty($args['form_id'])) { ?>
            <?= do_shortcode('[hf_form slug="' . $args['form_id'] . '"]'); ?>
        <?php } ?>
    </div>
</section>
