<?php

namespace Theme\Plugins;

class YoastSEO
{
    public static function init(): void
    {
        \add_filter("wpseo_metabox_prio", [__CLASS__, 'priority']);
    }

    // ----------------------------------------------------
    // The YoastSEO meta box loves to sit above meta boxes
    // that contain content, this is not helpful...Lets
    // lower the priority of the meta box so its lower
    // on the page
    // ----------------------------------------------------
    public static function priority(): string
    {
        return "low";
    }
}
