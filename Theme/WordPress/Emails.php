<?php

namespace Theme\WordPress;

class Emails
{
    public static function init(): void
    {
        // Filter default WordPress transactional email 'from' address.
        \add_action('wp_mail_from', [__CLASS__, 'filterWordPressFromEmailAddress']);

        // Filter default WordPress transactional email 'from' name.
        \add_action('wp_mail_from_name', [__CLASS__, 'filterWordPressFromName']);
    }

    /**
     * Filter the default WordPress 'from' email address.
     *
     * Uses an ACF option, defaulting to 'noreply@...' if no value found or ACF not active.
     *
     * @param string $email Default email address to send from.
     * @return string The filtered email address to send from.
     */
    public static function filterWordPressFromEmailAddress($email): string
    {
        $default = str_replace('wordpress@', 'noreply@', $email);

        if (!function_exists('get_field')) {
            return $default;
        }

        $option = \get_field('site_email_address', 'option');

        if (empty($option)) {
            return $default;
        }

        return $option;
    }

    /**
     * Filter the default WordPress 'from' name.
     *
     * Uses an ACF option, defaulting to the site name if no value found or ACF not active.
     *
     * @param string $name Default name to send from ("WordPress");
     * @return string The filtered name to send from.
     */
    public static function filterWordPressFromName($name): string
    {
        $default = \get_bloginfo('name');

        if (!function_exists('get_field')) {
            return $default;
        }

        $option = \get_field('site_email_sender_name', 'option');

        if (empty($option)) {
            return $default;
        }

        return $option;
    }
}
