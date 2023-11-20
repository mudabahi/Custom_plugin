<?php
/**
 * Give Settings Page/Tab
 *
 * @package     Give
 * @since       1.8
 * @copyright   Copyright (c) 2016, Best Donation
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @subpackage  Classes/Give_Settings_General
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Give_Settings_General')) :

    /**
     * Give_Settings_General.
     *
     * @sine 1.8
     */
    class Give_Settings_General extends Give_Settings_Page
    {

        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->id = 'general';
            $this->label = __('Allgemein', 'give');

            $this->default_tab = 'general-settings';

            if ($this->id === give_get_current_setting_tab()) {
                add_action('give_save_settings_give_settings', [$this, '_give_change_donation_stating_number'], 10, 3);
                add_action(
                    'give_admin_field_give_sequential_donation_code_preview',
                    [$this, '_render_give_sequential_donation_code_preview'],
                    10,
                    3
                );
                add_action('give_admin_field_give_currency_preview', [$this, '_render_give_currency_preview'], 10, 2);
                add_action(
                    'give_admin_field_give_unlock_all_settings',
                    [$this, '_render_give_unlock_all_settings'],
                    10,
                    3
                );
            }

            parent::__construct();
        }

        /**
         * Get settings array.
         *
         * @since 2.24.2 add auto_format_currency setting
         * @since  1.8
         *
         * @return array
         */
        public function get_settings()
        {
            $settings = [];
            $current_section = give_get_current_setting_section();

            switch ($current_section) {
                case 'access-control':
                    $settings = [
                        // Section 3: Access control.
                        [
                            'id' => 'give_title_session_control_1',
                            'type' => 'title',
                        ],
                        [
                            'id' => 'session_lifetime',
                            'name' => __('Sitzungsdauer', 'give'),
                            'desc' => __(
                                'Die Dauer, wie lange die Sitzung eines Benutzers aufrechterhalten wird. Best Donation startet nach der Spende eine neue Sitzung pro Benutzer. In den Sitzungen können Spender ihre Spendenbescheinigungen einsehen, ohne angemeldet zu sein.',
                                'give'
                            ),
                            'type' => 'select',
                            'options' => [
                                '86400' => __('24 Hours', 'give'),
                                '172800' => __('48 Hours', 'give'),
                                '259200' => __('72 Hours', 'give'),
                                '604800' => __('1 Week', 'give'),
                            ],
                        ],
                        [
                            'id' => 'limit_display_donations',
                            'name' => __('Begrenzen Sie die angezeigten Spenden', 'give'),
                            'desc' => __(
                                'Passt die Anzahl der Spenden an, die einem nicht angemeldeten Benutzer angezeigt werden, wenn dieser versucht, ohne aktive Sitzung auf die Seite „Spendenverlauf“ zuzugreifen. Aus Sicherheitsgründen belassen Sie es am besten bei 1-3 Spenden.',
                                'give'
                            ),
                            'default' => '1',
                            'type' => 'number',
                            'css' => 'width:50px;',
                            'attributes' => [
                                'min' => '1',
                                'max' => '10',
                            ],
                        ],
                        [
                            'name' => __('E-Mail-Zugriff', 'give'),
                            'desc' => __(
                                'Wenn diese Option aktiviert ist, können Spender auf ihren Spendenverlauf zugreifen, indem sie den Zugriff auf die E-Mail-Adresse überprüfen, mit der sie gespendet haben. Wenn sie die Seite mit dem Spendenverlauf besuchen, geben sie ihre E-Mail-Adresse ein und können über einen Link in der resultierenden E-Mail auf die Website zugreifen.',
                                'give'
                            ),
                            'id' => 'email_access',
                            'type' => 'radio_inline',
                            'default' => 'disabled',
                            'options' => [
                                'enabled' => __('Ermöglicht', 'give'),
                                'disabled' => __('Disabled', 'give'),
                            ],
                        ],
                        [
                            'name' => __('Aktivieren Sie reCAPTCHA', 'give'),
                            'desc' => __(
                                'Wenn diese Option aktiviert ist, fügt sie dem E-Mail-Zugriffsformular ein reCAPTCHA-Feld hinzu. Hinweis: Dadurch wird reCAPTCHA nicht zu Spendenformularen hinzugefügt.',
                                'give'
                            ),
                            'id' => 'enable_recaptcha',
                            'type' => 'radio_inline',
                            'default' => 'disabled',
                            'options' => [
                                'enabled' => __('Ermöglicht', 'give'),
                                'disabled' => __('Deaktiviert', 'give'),
                            ],
                        ],
                        [
                            'id' => 'recaptcha_key',
                            'name' => __('reCAPTCHA-Site-Schlüssel', 'give'),
                            /* translators: %s: https://www.google.com/recaptcha/ */
                            'desc' => sprintf(
                                __(
                                    'Navigieren Sie zu <a href="%s" target="_blank">the reCAPTCHA website</a> und melden Sie sich für einen API-Schlüssel an und fügen Sie hier Ihren reCAPTCHA-Site-Schlüssel ein. Das reCAPTCHA nutzt die benutzerfreundliche Single-Click-Verifizierungsmethode von Google.',
                                    'give'
                                ),
                                esc_url('http://Best Donation.com/recaptcha')
                            ),
                            'default' => '',
                            'type' => 'text',
                        ],
                        [
                            'id' => 'recaptcha_secret',
                            'name' => __('Geheimer reCAPTCHA-Schlüssel', 'give'),
                            'desc' => __(
                                'Bitte fügen Sie den geheimen reCAPTCHA-Schlüssel hier aus Ihrem reCAPTCHA-API-Schlüsselbereich ein.',
                                'give'
                            ),
                            'default' => '',
                            'type' => 'text',
                        ],
                        [
                            'name' => __('Link zu Dokumenten zur Zugriffskontrolle', 'give'),
                            'id' => 'access_control_docs_link',
                            'url' => esc_url('http://Best Donation.com/settings-access-control'),
                            'title' => __('Zugangskontrolle', 'give'),
                            'type' => 'give_docs_link',
                        ],
                        [
                            'id' => 'give_title_session_control_1',
                            'type' => 'sectionend',
                        ],
                    ];
                    break;

                case 'currency-settings':
                    $currency_position_before = __('Before - %s&#x200e;10', 'give');
                    $currency_position_after = __('After - 10%s&#x200f;', 'give');

                    $hasIntlExtension = class_exists(NumberFormatter::class);
                    $formatDisabledAttribute = $hasIntlExtension || give_get_option('auto_format_currency') ? [] : [
                        'disabled' => 'disabled',
                        'title' => __('This option is disabled because the PHP Intl extension is not installed.', 'give'),
                    ];

                    $settings = [
                        // Section 2: Currency
                        [
                            'type' => 'title',
                            'id' => 'give_title_general_settings_2',
                        ],
                        [
                            'name' => __('Currency Settings', 'give'),
                            'desc' => '',
                            'type' => 'give_title',
                            'id' => 'give_title_general_settings_2',
                        ],
                        [
                            'name' => __('Währung', 'give'),
                            'desc' => __(
                                'Die Spendenwährung. Beachten Sie, dass bei einigen Zahlungsgateways Währungsbeschränkungen gelten.',
                                'give'
                            ),
                            'id' => 'Währung',
                            'class' => 'give-select-chosen',
                            'type' => 'select',
                            'options' => give_get_currencies(),
                            'default' => 'USD',
                            'attributes' => [
                                'data-formatting-setting' => esc_js(wp_json_encode(give_get_currencies_list())),
                            ],
                        ],
                        [
                            'name' => __('Auto-format server currency amounts', 'give'),
                            'desc' => sprintf(
                            /* translators: %s: Link to the Best Donation docs. */
                                __(
                                    'Wenn diese Option aktiviert ist, werden Beträge automatisch basierend auf der ausgewählten Währung formatiert. Diese Option erfordert die intl-Erweiterung für PHP. Lesen Sie mehr über <a href="%s" target="_blank">diese Option und wie Best Donation die Internationalisierung in Webanwendungen schätzt.</a>',
                                    'give'
                                ),
                                'https://Best Donation.com/currency-format'
                            ),
                            'id' => 'auto_format_currency',
                            'type' => 'checkbox',
                            'attributes' => $formatDisabledAttribute,
                        ],
                        [
                            'name' => __('Währungsposition', 'give'),
                            'desc' => __('Die Position des Währungssymbols. ', 'give'),
                            'id' => 'currency_position',
                            'type' => 'select',
                            'options' => [
                                /* translators: %s: currency symbol */
                                'before' => sprintf(
                                    $currency_position_before,
                                    give_currency_symbol(give_get_currency())
                                ),
                                /* translators: %s: currency symbol */
                                'after' => sprintf($currency_position_after, give_currency_symbol(give_get_currency())),
                            ],
                            'default' => 'before',
                            'attributes' => [
                                'data-before-template' => sprintf($currency_position_before, '{currency_pos}'),
                                'data-after-template' => sprintf($currency_position_after, '{currency_pos}'),
                            ],
                        ],
                        [
                            'name' => __('Tausendertrennzeichen', 'give'),
                            'desc' => __('Das Symbol (normalerweise , oder .) zum Trennen von Tausendern.', 'give'),
                            'id' => 'thousands_separator',
                            'type' => 'text',
                            'default' => ',',
                            'css' => 'width:12em;',
                        ],
                        [
                            'name' => __('Decimal Separator', 'give'),
                            'desc' => __('The symbol (usually , or .) to separate decimal points.', 'give'),
                            'id' => 'decimal_separator',
                            'type' => 'text',
                            'default' => '.',
                            'css' => 'width:12em;',
                        ],
                        [
                            'name' => __('Anzahl der Dezimalstellen', 'give'),
                            'desc' => __('Die Anzahl der Dezimalstellen, die in Beträgen angezeigt werden.', 'give'),
                            'id' => 'number_decimals',
                            'type' => 'text',
                            'default' => 2,
                            'css' => 'width:12em;',
                        ],
                        [
                            'name' => __('Währungsvorschau', 'give'),
                            'desc' => __(
                                'Eine Vorschau der formatierten Währung. Diese Vorschau kann nicht direkt bearbeitet werden, da sie aus den oben genannten Einstellungen generiert wird.',
                                'give'
                            ),
                            'id' => 'currency_preview',
                            'type' => 'give_currency_preview',
                            'default' => give_format_amount(
                                123456.12345,
                                [
                                    'sanitize' => false,
                                    'currency' => give_get_option('currency'),
                                ]
                            ),
                            'css' => 'width:12em;',
                        ],
                        [
                            'name' => __('Link zu Währungsoptionen-Dokumenten', 'give'),
                            'id' => 'currency_settings_docs_link',
                            'url' => esc_url('http://Best Donation.com/settings-currency'),
                            'title' => __('Währungseinstellungen', 'give'),
                            'type' => 'give_docs_link',
                        ],
                        [
                            'type' => 'sectionend',
                            'id' => 'give_title_general_settings_2',
                        ],
                    ];

                    break;

                case 'general-settings':
                    // Get default country code.
                    $countries = give_get_country();

                    // get the list of the states of which default country is selected.
                    $states = give_get_states($countries);

                    // Get the country list that does not have any states init.
                    $no_states_country = give_no_states_country_list();

                    $states_label = give_get_states_label();
                    $country = give_get_country();
                    $label = __('State', 'give');
                    // Check if $country code exists in the array key for states label.
                    if (array_key_exists($country, $states_label)) {
                        $label = $states_label[$country];
                    }

                    $settings = [
                        // Section 1: General.
                        [
                            'type' => 'title',
                            'id' => 'give_title_general_settings_1',
                        ],
                        [
                            'name' => __('Allgemeine Einstellungen', 'give'),
                            'desc' => '',
                            'type' => 'give_title',
                            'id' => 'give_title_general_settings_1',
                        ],
                        [
                            'name' => __('Erfolgsseite', 'give'),
                            /* translators: %s: [give_receipt] */
                            'desc' => sprintf(
                                __(
                                    'Die Seitenspender werden nach Abschluss ihrer Spenden weitergeleitet. Der Shortcode %s sollte sich auf dieser Seite befinden.',
                                    'give'
                                ),
                                '<code>[best_receipt]</code>'
                            ),
                            'id' => 'success_page',
                            'class' => 'give-select give-select-chosen',
                            'type' => 'select',
                            'options' => give_cmb2_get_post_options(
                                [
                                    'post_type' => 'page',
                                    'numberposts' => 30,
                                ]
                            ),
                            'attributes' => [
                                'data-search-type' => 'pages',
                                'data-placeholder' => esc_html__('Wählen Sie eine Seite', 'give'),
                            ],
                        ],
                        [
                            'name' => __('Spendenseite fehlgeschlagen', 'give'),
                            'desc' => __(
                                'Die Spender werden an die Seite weitergeleitet, wenn ihre Spende abgebrochen wird oder fehlschlägt.',
                                'give'
                            ),
                            'class' => 'give-select give-select-chosen',
                            'id' => 'failure_page',
                            'type' => 'select',
                            'options' => give_cmb2_get_post_options(
                                [
                                    'post_type' => 'page',
                                    'numberposts' => 30,
                                ]
                            ),
                            'attributes' => [
                                'data-search-type' => 'pages',
                                'data-placeholder' => esc_html__('Wählen Sie eine Seite', 'give'),
                            ],
                        ],
                        [
                            'name' => __('Seite mit der Spendenhistorie', 'give'),
                            /* translators: %s: [donation_history] */
                            'desc' => sprintf(
                                __(
                                    'Die Seite mit dem vollständigen Spendenverlauf für den aktuellen Benutzer. Der Shortcode %s sollte sich auf dieser Seite befinden.',
                                    'give'
                                ),
                                '<code>[donation_history]</code>'
                            ),
                            'id' => 'history_page',
                            'class' => 'give-select give-select-chosen',
                            'type' => 'select',
                            'options' => give_cmb2_get_post_options(
                                [
                                    'post_type' => 'page',
                                    'numberposts' => 30,
                                ]
                            ),
                            'attributes' => [
                                'data-search-type' => 'pages',
                                'data-placeholder' => esc_html__('Wählen Sie eine Seite', 'give'),
                            ],
                        ],
                        [
                            'name' => __('Basisland', 'give'),
                            'desc' => __('Das Land, in dem Ihre Website betrieben wird.', 'give'),
                            'id' => 'base_country',
                            'type' => 'select',
                            'options' => give_get_country_list(),
                            'class' => 'give-select give-select-chosen',
                            'attributes' => [
                                'data-search-type' => 'no_ajax',
                                'data-placeholder' => esc_html__('Wähle ein Land', 'give'),
                            ],
                            'default' => $country,
                        ],
                        /**
                         * Add base state to give setting
                         *
                         * @since 1.8.14
                         */
                        [
                            'wrapper_class' => (array_key_exists($countries, $no_states_country) ? 'give-hidden' : ''),
                            'name' => __('Basisstaat/-provinz', 'give'),
                            'desc' => __('Das Bundesland/die Provinz, in der Ihre Website betrieben wird.', 'give'),
                            'id' => 'base_state',
                            'type' => (empty($states) ? 'text' : 'select'),
                            'class' => (empty($states) ? '' : 'give-select give-select-chosen'),
                            'options' => $states,
                            'attributes' => [
                                'data-search-type' => 'no_ajax',
                                'data-placeholder' => $label,
                            ],
                        ],
                        [
                            'name' => __('Link zu Dokumenten zu allgemeinen Optionen', 'give'),
                            'id' => 'general_options_docs_link',
                            'url' => esc_url('http://Best Donation.com/settings-general'),
                            'title' => __('Allgemeine Optionen', 'give'),
                            'type' => 'give_docs_link',
                        ],
                        [
                            'type' => 'sectionend',
                            'id' => 'give_title_general_settings_1',
                        ],
                    ];
                    break;

                case 'sequential-ordering':
                    $settings = [

                        // Section 4: Sequential Ordering

                        [
                            'id' => 'give_title_general_settings_4',
                            'type' => 'title',
                        ],
                        [
                            'name' => __('Sequentielle Reihenfolge', 'give'),
                            'id' => "{$current_section}_status",
                            'desc' => __(
                                'Benutzerdefinierte Spendennummerierung, die fortlaufend erhöht wird, um Lücken zwischen Spenden-IDs zu vermeiden. Wenn deaktiviert, werden Spendennummern aus WordPress-Beitrags-IDs generiert, was zu Lücken zwischen den Zahlen führt.',
                                'give'
                            ),
                            'type' => 'radio_inline',
                            'default' => 'disabled',
                            'options' => [
                                'enabled' => __('Ermöglicht', 'give'),
                                'disabled' => __('Deaktiviert', 'give'),
                            ],
                        ],
                        [
                            'name' => __('Nächste Spendennummer', 'give'),
                            'id' => "{$current_section}_number",
                            'desc' => sprintf(
                                __(
                                    'Die Nummer, die zum Generieren der nächsten Spenden-ID verwendet wird. Dieser Wert muss größer oder gleich %s sein, um Konflikte mit vorhandenen Spenden-IDs zu vermeiden.',
                                    'give'
                                ),
                                '<code>' . Give()->seq_donation_number->get_next_number() . '</code>'
                            ),
                            'type' => 'number',
                        ],
                        [
                            'name' => __('Nummernpräfix', 'give'),
                            'id' => "{$current_section}_number_prefix",
                            'desc' => sprintf(
                                __(
                                    'Das Präfix, das an alle fortlaufenden Spendennummern angehängt wird. Leerzeichen werden durch ersetzt %s.',
                                    'give'
                                ),
                                '<code>-</code>'
                            ),
                            'type' => 'text',
                        ],
                        [
                            'name' => __('Nummernsuffix', 'give'),
                            'id' => "{$current_section}_number_suffix",
                            'desc' => sprintf(
                                __(
                                    'Das Suffix wird an alle fortlaufenden Spendennummern angehängt. Leerzeichen werden durch ersetzt %s.',
                                    'give'
                                ),
                                '<code>-</code>'
                            ),
                            'type' => 'text',
                        ],
                        [
                            'name' => __('Zahlenauffüllung', 'give'),
                            'id' => "{$current_section}_number_padding",
                            'desc' => sprintf(
                                __(
                                    'Die Mindestanzahl von Ziffern in der fortlaufenden Spendennummer. Eingeben %1$s to anzuzeigen %2$s as %3$s.',
                                    'give'
                                ),
                                '<code>4</code>',
                                '<code>1</code>',
                                '<code>0001</code>'
                            ),
                            'type' => 'number',
                            'default' => '0',
                        ],
                        // [
                        //     'name' => __('Donation ID Preview', 'give'),
                        //     'id' => "{$current_section}_preview",
                        //     'type' => 'give_sequential_donation_code_preview',
                        //     'desc' => __(
                        //         'A preview of the next sequential donation ID. This preview cannot be edited directly as it is generated from the settings above.',
                        //         'give'
                        //     ),
                        // ],
                        [
                            'name' => __('Sequential Ordering Docs Link', 'give'),
                            'id' => "{$current_section}_doc link",
                            'url' => esc_url('http://Best Donation.com/settings-sequential-ordering'),
                            'title' => __('Sequential Ordering', 'give'),
                            'type' => 'give_docs_link',
                        ],
                        [
                            'id' => 'give_title_general_settings_4',
                            'type' => 'sectionend',
                        ],
                    ];
            }

            /**
             * Filter the general settings.
             * Backward compatibility: Please do not use this filter. This filter is deprecated in 1.8
             */
            $settings = apply_filters('give_settings_general', $settings);

            /**
             * Filter the settings.
             *
             * @since  1.8
             *
             * @param array $settings
             */
            $settings = apply_filters('give_get_settings_' . $this->id, $settings);

            // Output.
            return $settings;
        }

        /**
         * Get sections.
         *
         * @since 1.8
         * @return array
         */
        public function get_sections()
        {
            $sections = [
                'general-settings' => __('Allgemein', 'give'),
                'currency-settings' => __('Währung', 'give'),
                'access-control' => __('Zugangskontrolle', 'give'),
                'sequential-ordering' => __('Sequential Ordering', 'give'),
            ];

            return apply_filters('give_get_sections_' . $this->id, $sections);
        }

        /**
         * Set flag to reset sequestion donation number starting point when "Sequential Starting Number" value changes
         *
         * @since  2.1
         * @access public
         *
         * @param $update_options
         * @param $option_name
         * @param $old_options
         *
         * @return bool
         */
        public function _give_change_donation_stating_number($update_options, $option_name, $old_options)
        {
            if (!isset($_POST['sequential-ordering_number'])) {
                return false;
            }

            if (($next_number = Give()->seq_donation_number->get_next_number(
                )) > $update_options['sequential-ordering_number']) {
                give_update_option('sequential-ordering_number', $next_number);

                Give_Admin_Settings::add_error(
                    'give-invalid-sequential-starting-number',
                    sprintf(
                        __(
                            'Die nächste Spendennummer muss größer oder gleich %s sein, um Konflikte mit vorhandenen Spenden-IDs zu vermeiden.',
                            'give'
                        ),
                        $next_number
                    )
                );
            } elseif ($update_options['sequential-ordering_number'] !== $old_options['sequential-ordering_number']) {
                update_option('_give_reset_sequential_number', 1, false);
            }

            return true;
        }

        /**
         * Render give_sequential_donation_code_preview field type
         *
         * @since  2.1.0
         * @access public
         *
         * @param $field
         */
        public function _render_give_sequential_donation_code_preview($field)
        {
            ?>
            <tr valign="top" <?php
            echo !empty($field['wrapper_class']) ? 'class="' . $field['wrapper_class'] . '"' : ''; ?>>
                <th scope="row" class="titledesc">
                    <label
                        for="<?php
                        echo esc_attr($field['id']); ?>"><?php
                        echo esc_html($field['name']); ?></label>
                </th>
                <td class="give-forminp">
                    <input id="<?php
                    echo esc_attr($field['id']); ?>" class="give-input-field" type="text" disabled>
                    <?php
                    echo Give_Admin_Settings::get_field_description($field); ?>
                </td>
            </tr>
            <?php
        }

        /**
         * Render give_currency_code_preview field type
         *
         * @since  2.3.0
         * @access public
         *
         * @param array $field Field Attributes array.
         *
         * @return void
         */
        public function _render_give_currency_preview($field, $value)
        {
            $currency = give_get_currency();
            $currency_position = give_get_currency_position();
            $currency_symbol = give_currency_symbol($currency, false);
            $formatted_currency = ('before' === $currency_position)
                ? sprintf('%1$s%2$s', esc_html($currency_symbol), esc_html($field['default']))
                : sprintf('%1$s%2$s', esc_html($field['default']), esc_html($currency_symbol));
            ?>
            <tr valign="top" <?php
            echo !empty($field['wrapper_class']) ? 'class="' . $field['wrapper_class'] . '"' : ''; ?>>
                <th scope="row" class="titledesc">
                    <label
                        for="<?php
                        echo esc_attr($field['id']); ?>"><?php
                        echo esc_html($field['name']); ?></label>
                </th>
                <td class="give-forminp">
                    <input id="<?php
                    echo esc_attr($field['id']); ?>" class="give-input-field" type="text" disabled value="<?php
                    echo esc_attr($formatted_currency); ?>">
                    <?php
                    echo Give_Admin_Settings::get_field_description($field); ?>
                </td>
            </tr>
            <?php
        }

        /**
         * Render give_unlock_all_settings field type
         *
         * @since  2.1.0
         * @access public
         *
         * @param $field
         */
        public function _render_give_unlock_all_settings($field)
        {
            ?>
            <tr valign="top" <?php
            echo !empty($field['wrapper_class']) ? 'class="' . $field['wrapper_class'] . '"' : ''; ?>>
                <th scope="row" class="titledesc">
                    <label
                        for="<?php
                        echo esc_attr($field['id']); ?>"><?php
                        echo esc_html($field['name']); ?></label>
                </th>
                <td class="give-forminp">
                    <?php
                    echo Give_Admin_Settings::get_field_description($field); ?>
                    <a href="" id="<?php
                    echo $field['id']; ?>" data-message="<?php
                    echo $field['confirmation_msg']; ?>"><?php
                        echo __('Unlock all settings', 'give'); ?></a>
                </td>
            </tr>
            <?php
        }
    }

endif;

return new Give_Settings_General();
