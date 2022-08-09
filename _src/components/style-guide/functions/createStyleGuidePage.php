<?php

namespace Granola\Components\StyleGuide;

/**
* Create the Style Guide page if it doesn't exist.
*/
function createStyleguidePage()
{
    /**
    * Test images for use in the style guide
    */
    $images = [
        [
            'url' => 'https://via.placeholder.com/1200x900/ee964b?text=+',
            'title' => 'placeholder_1200x900_ee964b',
        ],
        [
            'url' => 'https://via.placeholder.com/1200x900/F4D35E?text=+',
            'title' => 'placeholder_1200x900_F4D35E',
        ],
        [
            'url' => 'https://via.placeholder.com/1200x900/EBEBD3/c5c581?text=+',
            'title' => 'placeholder_1200x900_EBEBD3',
        ],
        [
            'url' => 'https://via.placeholder.com/1200x900/083d77/1a81ef?text=+',
            'title' => 'placeholder_1200x900_083d77',
        ],
        [
            'url' => 'https://via.placeholder.com/1000x1250/ee964b?text=+',
            'title' => 'placeholder_1000x1250_ee964b',
        ],
        [
            'url' => 'https://via.placeholder.com/1000x1250/F4D35E?text=+',
            'title' => 'placeholder_1000x1250_F4D35E',
        ],
        [
            'url' => 'https://via.placeholder.com/1000x1250/EBEBD3/c5c581?text=+',
            'title' => 'placeholder_1000x1250_EBEBD3',
        ],
        [
            'url' => 'https://via.placeholder.com/1000x1250/083d77/1a81ef?text=+',
            'title' => 'placeholder_1000x1250_083d77',
        ],
    ];

    $pages = [
        'style-guide' => [
            'post_title'    => 'Style Guide',
            'page_template' => 'style-guide.php',
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'post_content'  => sprintf(
                '<!-- wp:heading -->
                <h2>Editor Blocks</h2>
                <!-- /wp:heading -->

                <!-- wp:heading {"level":3} -->
                <h3>Type</h3>
                <!-- /wp:heading -->

                <!-- wp:heading {"level":5} -->
                <h5>Heading (H1 - H6)</h5>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p>Paragraph</p>
                <!-- /wp:paragraph -->

                <!-- wp:list -->
                <ul><li>Unordered List</li><li>Item 2<ul><li>Sub list</li></ul></li><li>Item 3</li></ul>
                <!-- /wp:list -->

                <!-- wp:list {"ordered":true} -->
                <ol><li>Ordered List</li><li>Item Two<ol><li>Sub list</li></ol></li><li>Item Three</li></ol>
                <!-- /wp:list -->

                <!-- wp:quote -->
                <blockquote class="wp-block-quote"><p>Quote Block</p><cite>Citation</cite></blockquote>
                <!-- /wp:quote -->

                <!-- wp:heading {"level":5} -->
                <h5>Buttons</h5>
                <!-- /wp:heading -->

                <!-- wp:buttons {"contentJustification":"center"} -->
                <div class="wp-block-buttons is-content-justification-center"><!-- wp:button -->
                <div class="wp-block-button"><a class="wp-block-button__link">Button1</a></div>
                <!-- /wp:button -->

                <!-- wp:button -->
                <div class="wp-block-button"><a class="wp-block-button__link">Button 2</a></div>
                <!-- /wp:button -->

                <!-- wp:button -->
                <div class="wp-block-button"><a class="wp-block-button__link">Button 3</a></div>
                <!-- /wp:button --></div>
                <!-- /wp:buttons -->

                <!-- wp:heading {"level":5} -->
                <h5>Separator</h5>
                <!-- /wp:heading -->

                <!-- wp:separator -->
                <hr class="wp-block-separator"/>
                <!-- /wp:separator -->

                <!-- wp:table {"hasFixedLayout":true,"className":"is-style-regular"} -->
                <figure class="wp-block-table is-style-regular">
                    <table class="has-fixed-layout">
                        <thead><tr><th>Table (header row)</th><th>Header 2</th><th>Header 3</th></tr></thead>
                        <tbody>
                            <tr><td>Row 1</td><td>Lorem ipsum dolor.</td><td>Lorem ipsum </td></tr>
                            <tr><td>Row 2</td><td>ipsum</td><td>Dolor sit amet</td></tr>
                        </tbody>
                        <tfoot>
                            <tr><td>Optional Footer Section</td><td></td><td></td></tr>
                        </tfoot>
                    </table>
                    <figcaption>Table Caption</figcaption>
                </figure>
                <!-- /wp:table -->

                <!-- wp:image {"align":"wide","id":633,"sizeSlug":"large","linkDestination":"none"} -->
                <figure class="wp-block-image alignwide size-large">
                    <img src="%1$s" alt=""/><figcaption>Image Caption</figcaption>
                </figure>
                <!-- /wp:image -->

                <!-- wp:gallery {
                    "ids":[633,626,624,623],
                    "columns":2,
                    "linkTo":"none",
                    "sizeSlug":"medium",
                    "align":"wide"} -->
                <figure class="wp-block-gallery alignwide columns-2 is-cropped"><ul class="blocks-gallery-grid">
                    <li class="blocks-gallery-item">
                        <figure><img src="%2$s" alt="" data-full-url="%2$s" data-link="%2$s" class="wp-image-633"/>
                        </figure>
                    </li>
                    <li class="blocks-gallery-item">
                        <figure><img src="%3$s" alt="" data-id="626" data-full-url="%3$s" data-link="%3$s"/>
                        </figure>
                    </li>
                    <li class="blocks-gallery-item">
                        <figure><img src="%4$s" alt="" data-id="624" data-full-url="%4$s" data-link="%4$s"/>
                        </figure>
                    </li>
                    <li class="blocks-gallery-item">
                        <figure><img src="%5$s" alt="" data-id="623" data-full-url="%5$s" data-link="%5$s"/>
                        </figure>
                    </li>
                    </ul><figcaption class="blocks-gallery-caption">Gallery Caption</figcaption></figure>
                <!-- /wp:gallery -->

                <!-- wp:cover {"url":"%3$s","id":626,"align":"wide"} -->
                <div class="wp-block-cover alignwide has-background-dim">
                    <img class="wp-block-cover__image-background wp-image-626" src="%3$s" data-object-fit="cover"/>
                    <div class="wp-block-cover__inner-container">
                        <!-- wp:paragraph {"align":"center","placeholder":"Write title…","fontSize":"large"} -->
                            <p class="has-text-align-center has-large-font-size">Cover Block</p>
                        <!-- /wp:paragraph -->
                    </div>
                </div>
                <!-- /wp:cover -->

                <!-- wp:acf/cards {"id":"block_617171b5fb63b",
                    "name":"acf/cards",
                    "data":{"heading":"Cards",
                    "_heading":"field_5e4ab7fd3a2ef",
                    "subheading":"Cards Subheading",
                    "_subheading":"field_5fa2899fcf16d",
                    "button":{"title":"Card Button",
                    "url":"/",
                    "target":"_blank"},"_button":"field_60a2687304feb",
                    "type":"recent",
                    "_type":"field_5e691705dc321",
                    "post_type":"post",
                    "_post_type":"field_5e6917d1dc324",
                    "limit":"2",
                    "_limit":"field_5e69183fdc325",
                    "background_color":"brand-2",
                    "_background_color":"field_5e66702e3e0ab",
                    "slider_on_mobile":"0",
                    "_slider_on_mobile":"field_60a27df4ed776"},"align":"",
                    "mode":"auto"} /-->

                <!-- wp:acf/media-content {"id":"block_617186b4fb63c",
                    "name":"acf/media-content",
                    "data":{"heading":"Media and Content Block (image)",
                    "_heading":"field_60a67f8182d68",
                    "content":"",
                    "_content":"field_60a67f8182d6e",
                    "button_1":{"title":"Button",
                    "url":"/",
                    "target":""},"_button_1":"field_60a681d68f9d2",
                    "media_type":"image",
                    "_media_type":"field_60a68014b968e",
                    "image":625,"_image":"field_60a67f8182d74",
                    "media_side":"left",
                    "_media_side":"field_60a67f8182d7f",
                    "background_color":"brand-1",
                    "_background_color":"field_60a67f8182d85"},"align":"full",
                    "mode":"auto"} /-->

                <!-- wp:acf/media-content {"id":"block_617186e9fb63d",
                    "name":"acf/media-content",
                    "data":{"heading":"Media and Content (Video)",
                    "_heading":"field_60a67f8182d68",
                    "content":"\u003cstrong\u003eSupports\u003c/strong\u003e\r\n\r\n
                        Backgrounds: [none, Brand 1, Brand 2, Brand 3]\r\n\r\nAlignment: [Wide, Full]",
                    "_content":"field_60a67f8182d6e",
                    "button_1":{"title":"Button",
                    "url":"/",
                    "target":""},"_button_1":"field_60a681d68f9d2",
                "media_type":"video",
                "_media_type":"field_60a68014b968e",
                "video":"https://www.youtube.com/watch?v=YF6gNnYtE9A",
                "_video":"field_5f6c5fc021d64",
                "image":625,"_image":"field_60a67f8182d74",
                "media_side":"right",
                "_media_side":"field_60a67f8182d7f",
                "background_color":"brand-3",
                "_background_color":"field_60a67f8182d85"},"align":"full",
                "mode":"auto"} /-->

                <!-- wp:group {"align":"wide",
                    "backgroundColor":"brand-1","layout":{"inherit":true}} -->
                <div class="wp-block-group alignwide has-brand-1-background-color has-background">
                <!-- wp:heading {"level":4} -->
                <h4>Heading for group</h4>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet<br>Consectetur adipiscing elit. </p>
                <!-- /wp:paragraph -->

                <!-- wp:paragraph -->
                <p>Aenean blandit sodales purus.<br>Finibus augue accumsan condimentum. </p>
                <!-- /wp:paragraph -->

                <!-- wp:paragraph -->
                <p>Maecenas purus nibh, imperdiet vel consequat sit amet.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:group -->

                <!-- wp:columns {"align":"wide"} -->
                <div class="wp-block-columns alignwide">
                <!-- wp:column -->
                <div class="wp-block-column">
                <!-- wp:gravityforms/form {"formId":"1"} /-->
                </div>
                <!-- /wp:column -->
                <!-- wp:column -->
                <div class="wp-block-column">
                <!-- wp:group {"backgroundColor":"brand-3"} -->
                <div class="wp-block-group has-brand-3-background-color has-background">
                <!-- wp:heading {"level":4} -->
                <h4>Heading for group</h4>
                <!-- /wp:heading -->
                <!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet<br>Consectetur adipiscing elit. </p>
                <!-- /wp:paragraph -->
                <!-- wp:paragraph -->
                <p>Aenean blandit sodales purus.<br>Finibus augue accumsan condimentum. </p>
                <!-- /wp:paragraph -->
                <!-- wp:paragraph -->
                <p>Maecenas purus nibh, imperdiet vel consequat sit amet.</p>
                <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
                </div>
                <!-- /wp:column -->
                </div>
                <!-- /wp:columns -->',
                $images[0]['url'],
                $images[1]['url'],
                $images[2]['url'],
                $images[3]['url'],
                $images[4]['url'],
                $images[5]['url'],
            )
        ],
    ];

    foreach ($pages as $key => $page) {
        // If the page doesn't already exist, create it
        if (empty(get_page_by_path($key, OBJECT, 'page'))) {
            $page = \wp_insert_post($page);
        }
    }
}
