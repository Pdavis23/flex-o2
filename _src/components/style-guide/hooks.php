<?php

namespace Granola\Components\StyleGuide;

\add_filter('granola/partial/assets/components/style-guide', __NAMESPACE__ . '\\filterArgs');
\add_action('after_switch_theme', __NAMESPACE__ . '\\createStyleguidePage', 10);
