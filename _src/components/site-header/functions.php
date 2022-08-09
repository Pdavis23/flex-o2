<?php

namespace Granola\Components\SiteHeader;

function filterArgs(array $args): array
{
    // ---------------------------------------
    // Default arguments.
    // ---------------------------------------
    $args = array_merge([
        'content' => [],
        'classes' => [
            'site-header',
        ],
        'languages' => [],
    ], $args);

    if ($header_call_to_action_1 = get_field('header_call_to_action_1', 'option')) {
        $args['content']['call_to_action_1'] = $header_call_to_action_1;
        $args['content']['call_to_action_1']['classes'] = [
            'g-button',
            'site-header__call-to-action-1',
        ];
    }

    // -------------------------------------------------------------------------
    // Language Switcher
    // -------------------------------------------------------------------------
    if (function_exists('pll_the_languages')) {
        $languages = \pll_the_languages([
            'raw' => 1,
        ]);

        $args['current_language'] = pll_current_language('name');

        $total_languages = count($languages);

        if ($total_languages < 3) {
            foreach ($languages as $language) {
                // Only include if not the current language.
                if (!$language['current_lang']) {

                    if ($language['slug'] === 'en') {
                        $language_taxonomy_url = '/';
                        $args['languages'][] = [
                            'title' => $language['name'],
                            'url' => $language_taxonomy_url,
                            'current' => $language['current_lang'],
                        ];
                    } else {
                        $language_taxonomy_url = '/' . $language['slug'];
                        $args['languages'][] = [
                            'title' => $language['name'],
                            'url' => $language_taxonomy_url,
                            'current' => $language['current_lang'],
                        ];
                    }
                }
            }
        } else {
            foreach ($languages as $language) {
                // Only include if not the current language.
                if (!$language['current_lang']) {
                    // Main site will be set to english so go to root.
                    if ($language['slug'] === 'en') {
                        $language_taxonomy_url = '/';
                        $args['languages'][] = [
                            'title' => $language['name'],
                            'url' => $language_taxonomy_url,
                            'current' => $language['current_lang'],
                        ];
                    } else {
                        // Otherwise, sublangauge sites will be in polylang format /langauge/fr
                        $language_taxonomy_url = '/' . $language['slug'];
                        $args['languages'][] = [
                            'title' => $language['name'],
                            'url' => $language_taxonomy_url,
                            'current' => $language['current_lang'],
                        ];
                    }
                }
            }
        }
    }

    // -------------------------------------------------------------------------
    // Return the filtered args.
    // -------------------------------------------------------------------------
    return $args;
}
