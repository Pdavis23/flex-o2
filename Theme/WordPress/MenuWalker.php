<?php

// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Theme\WordPress;

// ------------------------------------------
// Wraps submenus and provides a class with the depth of that submenu
// to improve styling.
// ------------------------------------------

class MenuWalker extends \Walker_Nav_Menu
{
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class='sub-menu sub-menu--depth-$depth'>
    <ul class='sub-menu__inner'>\n";
    }

    public function end_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>
</div>\n";
    }
}
