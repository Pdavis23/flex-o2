<section <?= \Granola\Helpers::buildAttributes($args['attributes']); ?>>
    <div class="clinical-study__inner">
        <div class="clinical-study__file">
            <?= \Granola\Component::get('link', [
                'url' => $args['button']['url'],
                'content' => \Granola\SVG::get('icons/file.svg'),
                'content_filter' => false,
                'target' => '_blank',
                'attributes' => [
                    'aria-label' => __('Download Case Study: ' . $args['heading'], 'granola'),
                ],
            ]); ?>

            <?php if (!empty($args['button'])) { ?>
                <div class="clinical-study__download">
                    <?= \Granola\Component::get('link', $args['button']); ?>

                    <?php if (!empty($args['file_meta'])) { ?>
                        <div class="clinical-study__file-meta">
                            <?= wp_kses_post($args['file_meta']); ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <div class="clinical-study__content">
            <?php if (!empty($args['heading'])) { ?>
                <?= \Granola\Component::get('heading', [
                    'heading' => $args['heading'],
                    'classes' => ['clinical-study__heading'],
                ]); ?>
            <?php } ?>

            <?php if (!empty($args['content'])) { ?>
                <div class="clinical-study__content">
                    <?= wp_kses_post($args['content']); ?>
                </div>
            <?php } ?>

            <?php if (!empty($args['items'])) { ?>
                <?= \Granola\Component::get('links', [
                    'items' => $args['items'],
                    'display' => 'ticks',
                    'align' => null,
                ]); ?>
            <?php } ?>
        </div>
    </div>
</section>
