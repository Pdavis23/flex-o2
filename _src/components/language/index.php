<?php if (!empty($args['languages'])) { ?>

    <section <?= \Granola\Helpers::buildAttributes($args['attributes']); ?>>
        <div class="language__inner">
            <?php if (!empty($args['label'])) { ?>
                <div class="language__label">
                    <?= wp_kses_post($args['label']); ?>
                </div>
            <?php } ?>

            <div class="language__items">
                <?php foreach ($args['languages'] as $key => $language) { ?>
                    <div class="language__item">
                        <span class="language__item__language"> <?= wp_kses_post($language['language']); ?>
                        </span>
                        <?= \Granola\Component::get('link', $language); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>
