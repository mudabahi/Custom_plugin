<?php

namespace Give\Views\Admin;

use Give_License;

/**
 * Class UpsellNotices
 * @package Give\Views\Admin
 *
 * @since 2.8.0
 */
class UpsellNotice
{
    /**
     * Upsell notice for recurring addon
     */
    public static function recurringAddon()
    {
        if (Give_License::get_plugin_by_slug('give-recurring')) {
            return '';
        }

        $addon_link_url = esc_url('#');
        $addon_button_url = esc_url('#');

        return sprintf(
            '
			
			',
            sprintf(
                __(
                    'Aktivieren Sie die <a href="%1$s" title="%2$s" target="_blank">Recurring Donations add-on</a> und bieten Sie Ihren Spendern flexible Abonnementoptionen.',
                    'give'
                ),
                $addon_link_url,
                esc_html__('Klicken Sie hier, um das Add-on „Wiederkehrende Spenden“ anzuzeigen', 'give')
            ),
            $addon_button_url,
            esc_html__('Add-on anzeigen', 'give')
        );
    }
}

// <div class="give-upsell-notice">
// <span class="icon dashicons dashicons-update-alt"></span>
// <span class="description">%1$s</span>
// <a class="view-addon-link button" href="%2$s" target="_blank">%3$s</a>
// </div>