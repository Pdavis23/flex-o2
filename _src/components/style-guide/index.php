<nav role="navigation" class="style-guide-toc">
    <div class="style-guide-toc__inner">
        <details open>
            <summary>
                <h2 class="style-guide-toc__heading">Components</h2>
            </summary>
            <ol class="style-guide-toc__list">
                <li>
                    <a class="style-guide-toc__item" href="#style-guide-html-elements">HTML Elements</a>
                </li>
                <li>
                    <a class="style-guide-toc__item" href="#style-guide-objects">Objects</a>
                </li>
                <?php foreach ($args['components'] as $key => $component) {
                    $json = $component['json']; ?>
                    <li>
                        <a class="style-guide-toc__item" href="#style-guide-<?= $component['id'] ?>">
                            <?= $json->name; ?>
                        </a>
                    </li>
                <?php } ?>
            </ol>
        </details>
    </div>
</nav>

<h2 class="style-guide__heading style-guide__heading--section" id="style-guide-html-elements">
    <?= __('HTML Elements', 'granola'); ?>
</h2>

<?= \Granola\Component::get('style-guide/html-elements'); ?>

<h2 class="style-guide__heading style-guide__heading--section" id="style-guide-objects">
    <?= __('Objects', 'granola'); ?>
</h2>

<?= \Granola\Component::get('style-guide/objects'); ?>

<h2 class="style-guide__heading style-guide__heading--section">
    <?= __('Components', 'granola'); ?>
</h2>

<?php
// Loop the components
foreach ($args['components'] as $key => $component) {
    $json = $component['json']; ?>

    <h2 class="style-guide__heading style-guide__heading--component" id="style-guide-<?= $component['id'] ?>">
        <?= $json->name; ?>
    </h2>

    <?php
    echo \Granola\Component::get($component['path'], $component['args']);

    foreach ($component['variations'] as $key => $variation) { ?>
        <h2 class="style-guide__heading style-guide__heading--variation">
            <?= $variation['_variation']; ?>
        </h2>
        <?php
        echo \Granola\Component::get($component['path'], $variation);
    }
} ?>

<?php // \Granola\Component::get('style-guide/theme-editor'); ?>
