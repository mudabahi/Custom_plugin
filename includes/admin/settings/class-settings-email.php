<?php
/**
 * Give Settings Page/Tab
 *
 * @package     Give
 * @subpackage  Classes/Give_Settings_Email
 * @copyright   Copyright (c) 2016, Best Donation
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Give_Settings_Email' ) ) :

	/**
	 * Give_Settings_Email.
	 *
	 * @sine 1.8
	 */
	class Give_Settings_Email extends Give_Settings_Page {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id    = 'emails';
			$this->label = esc_html__( 'E-Mails', 'give' );

			$this->default_tab = 'donor-email';

			parent::__construct();

			$this->enable_save = ! ( Give_Admin_Settings::is_setting_page( 'emails', 'donor-email' ) || Give_Admin_Settings::is_setting_page( 'emails', 'admin-email' ) );

			add_action( 'give_admin_field_email_notification', array( $this, 'email_notification_setting' ) );
            add_action( 'give_admin_field_give_sendwp_button', [ $this, '_render_give_sendwp_button' ], 10, 3 );
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
		public function _render_give_sendwp_button( $field, $value ) {
            // Connection status partial label based on the state of the SendWP email sending setting (Tools -> SendWP)
            $connected  = '<a href="https://app.sendwp.com/dashboard" target="_blank" rel="noopener noreferrer">';
            $connected .= __( 'Greifen Sie auf Ihr SendWP-Konto zu', 'give' );
            $connected .= '</a>.';

            $disconnected = sprintf(
                __( '<em><strong>Note:</strong> Der E-Mail-Versand ist derzeit deaktiviert. <a href="' . admin_url( '/tools.php?page=sendwp' ) . '">klicken Sie hier</a> um es zu aktivieren.</em>', 'give' )
            );

            // Checks if SendWP is connected
            $client_connected = function_exists( 'sendwp_client_connected' ) && sendwp_client_connected() ? true : false;

            // Checks if email sending is enabled in SendWP
            $forwarding_enabled = function_exists( 'sendwp_forwarding_enabled' ) && sendwp_forwarding_enabled() ? true : false;

            // Output the appropriate button and label based on connection status
            if( $client_connected ) :
                ?>
                <tr valign="top" <?php echo ! empty( $field['wrapper_class'] ) ? 'class="' . $field['wrapper_class'] . '"' : ''; ?>>
                    <th scope="row" class="titledesc">
                        <label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['name'] ); ?></label>
                    </th>
                    <td class="give-forminp">
                        <p><?php _e( 'SendWP plugin activated.', 'give' ); ?> <?php echo $forwarding_enabled ? $connected : $disconnected ; ?></p>

                        <br style="margin-bottom: 0.5rem;"/>

                        <button id="give-sendwp-disconnect" class="button"><?php _e( 'Trennen Sie SendWP', 'give' ); ?></button>
                    </td>
                </tr>
                <?php
            else :
                ?>
                <tr valign="top" <?php echo ! empty( $field['wrapper_class'] ) ? 'class="' . $field['wrapper_class'] . '"' : ''; ?>>
                    <th scope="row" class="titledesc">
                        <label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['name'] ); ?></label>
                    </th>
                    <td class="give-forminp">
                        <div class="give-field-description">
                            <?php _e( 'Best Donation empfiehlt SendWP, um eine schnelle und zuverlässige Zustellung aller von Ihrer Website gesendeten E-Mails zu gewährleisten, z. B. Spendenbescheinigungen, Erinnerungen an wiederkehrende Spendenverlängerungen, Passwort-Resets und mehr.', 'give' ); ?> <?php printf( __( '%sLearn more%s', 'give' ), '<a href="#" target="_blank" rel="noopener noreferrer">', '</a>' ); ?>
                        </div>

                        <br style="margin-bottom: 0.5rem;"/>

                        <button type="button" id="give-sendwp-connect" class="button button-primary"><?php esc_html_e( 'Verbinden Sie sich mit SendWP', 'give' ); ?>
                    </button>
                    </td>
                </tr>

                <script>
                    jQuery('#give-sendwp-connect').on('click', function(e) {

                        e.preventDefault();
                        jQuery(this).html( 'Connecting <span class="give-loading"></span>' );
                        document.body.style.cursor = 'wait';
                        give_sendwp_remote_install();

                    });

                    jQuery('#give-sendwp-disconnect').on('click', function(e) {
                        e.preventDefault();
                        jQuery(this).html( 'Trennen <span class="give-loading dark"></span>' );
                        document.body.style.cursor = 'wait';
                        give_sendwp_disconnect();

                    });

                    function give_sendwp_remote_install() {
                        var data = {
                            'action': 'give_sendwp_remote_install',
                        };

                        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                        jQuery.post(ajaxurl, data, function( response ) {

                            if( ! response.success ) {

                                if( confirm( response.data.error ) ) {
                                    location.reload();
                                    return;
                                }
                            }

                            give_sendwp_register_client(
                                response.data.register_url,
                                response.data.client_name,
                                response.data.client_secret,
                                response.data.client_redirect,
                                response.data.partner_id
                            );
                        });
                    }

                    function give_sendwp_disconnect() {
                        var data = {
                            'action': 'give_sendwp_disconnect',
                        };

                        jQuery.post(ajaxurl, data, function( response ) {
                            location.reload();
                        });
                    }

                    function give_sendwp_register_client(register_url, client_name, client_secret, client_redirect, partner_id) {

                        var form = document.createElement("form");
                        form.setAttribute("method", 'POST');
                        form.setAttribute("action", register_url);

                        function give_sendwp_append_form_input(name, value) {
                            var input = document.createElement("input");
                            input.setAttribute("type", "hidden");
                            input.setAttribute("name", name);
                            input.setAttribute("value", value);
                            form.appendChild(input);
                        }

                        give_sendwp_append_form_input('client_name', client_name);
                        give_sendwp_append_form_input('client_secret', client_secret);
                        give_sendwp_append_form_input('client_redirect', client_redirect);
                        give_sendwp_append_form_input('partner_id', partner_id);

                        document.body.appendChild(form);
                        form.submit();
                    }
                </script>
                <?php
            endif;
		}

		/**
		 * Get settings array.
		 *
		 * @since  1.8
		 * @return array
		 */
		public function get_settings() {
			$settings        = array();
			$current_section = give_get_current_setting_section();

			switch ( $current_section ) {
				case 'email-settings':
					$settings = array(

						// Section 1: Email Sender Setting
						array(
							'id'   => 'give_title_email_settings_1',
							'type' => 'title',
						),
						array(
							'id'      => 'email_template',
							'name'    => esc_html__( 'E-Mail-Vorlage', 'give' ),
							'desc'    => esc_html__( 'Wählen Sie Ihre Vorlage aus den verfügbaren registrierten Vorlagentypen aus.', 'give' ),
							'type'    => 'select',
							'options' => give_get_email_templates(),
						),
						array(
							'id'   => 'email_logo',
							'name' => esc_html__( 'Logo', 'give' ),
							'desc' => esc_html__( 'Laden Sie ein Logo hoch oder wählen Sie ein Logo aus, das oben in den Spendenquittungs-E-Mails angezeigt werden soll. Wird nur in HTML-E-Mails angezeigt.', 'give' ),
							'type' => 'file',
						),
						array(
							'id'      => 'from_name',
							'name'    => esc_html__( 'Von Namen', 'give' ),
							'desc'    => esc_html__( 'Der Name, der im Feld „Von“ in allen Spenden-E-Mails von Best Donation erscheint.', 'give' ),
							'default' => get_bloginfo( 'name' ),
							'type'    => 'text',
						),
						array(
							'id'      => 'from_email',
							'name'    => esc_html__( 'Aus der Email', 'give' ),
							'desc'    => esc_html__( 'E-Mail-Adresse, von der aus alle Best Donation-E-Mails gesendet werden. Dies dient als Absender- und Antwort-E-Mail-Adresse.', 'give' ),
							'default' => get_bloginfo( 'admin_email' ),
							'type'    => 'text',
						),
                        array(
							'id'      => 'sendwp',
							'name'    => esc_html__( 'SendWP', 'give' ),
							'desc'    => esc_html__( 'Wir empfehlen SendWP, um eine schnelle und zuverlässige Zustellung aller von Ihrem Shop gesendeten E-Mails zu gewährleisten, z. B. Spendenquittungen, Erinnerungen an wiederkehrende Spendenverlängerungen, Passwort-Resets und mehr.', 'give' ),
							'type'    => 'give_sendwp_button',
						),
						array(
							'name'  => esc_html__( 'Link zu den Einstellungen für Spendenbenachrichtigungsdokumente', 'give' ),
							'id'    => 'donation_notification_settings_docs_link',
							'url'   => esc_url( 'http://Best Donation.com/settings-donation-notification' ),
							'title' => __( 'Einstellungen für Spendenbenachrichtigungen', 'give' ),
							'type'  => 'give_docs_link',
						),
						array(
							'id'   => 'give_title_email_settings_3',
							'type' => 'sectionend',
						),
					);
					break;

				case 'donor-email':
					$settings = array(

						// Section 1: Donor Email Notification Listing.
						array(
							'desc'       => __( 'E-Mail-Benachrichtigungen von Best Donation für Spender sind unten aufgeführt. Klicken Sie auf eine E-Mail, um sie zu konfigurieren.', 'give' ),
							'type'       => 'title',
							'id'         => 'give_donor_email_notification_settings',
							'table_html' => false,
						),
						array(
							'type' => 'email_notification',
						),
						array(
							'type' => 'sectionend',
							'id'   => 'give_donor_email_notification_settings',
						),

					);
					break;

				case 'admin-email':
					$settings = array(

						// Section 1: Admin Email Notification Listing.
						array(
							'desc'       => __( 'E-Mail-Benachrichtigungen, die von Best Donation für Administratoren gesendet wurden, sind unten aufgeführt. Klicken Sie auf eine E-Mail, um sie zu konfigurieren.', 'give' ),
							'type'       => 'title',
							'id'         => 'give_admin_email_notification_settings',
							'table_html' => false,
						),
						array(
							'type' => 'email_notification',
						),
						array(
							'type' => 'sectionend',
							'id'   => 'give_admin_email_notification_settings',
						),

					);
					break;

				case 'contact':
					$settings = array(

						array(
							'id'   => 'give_title_general_settings_5',
							'type' => 'title',
						),
						array(
							'name'    => __( 'Admin-E-Mail-Adresse', 'give' ),
							'id'      => 'contact_admin_email',
							'desc'    => sprintf( '%1$s <code>{admin_email}</code> %2$s', __( 'Standardmäßig ist die', 'give' ), __( 'Tag verwendet Ihre WordPress-Administrator-E-Mail. Wenn Sie diese Adresse anpassen möchten, können Sie dies im Feld oben tun.', 'give' ) ),
							'type'    => 'text',
							'default' => give_email_admin_email(),

						),
						array(
							'name'    => __( 'Offline-Postanschrift', 'give' ),
							'id'      => 'contact_offline_mailing_address',
							'desc'    => sprintf( '%1$s <code>{offline_mailing_address}</code> %2$s', __( 'Legen Sie die Postanschrift fest, an die Ihre Spender ihre Offline-Spenden senden sollen. Dadurch wird das angepasst', 'give' ), __( 'E-Mail-Tag für das Offline-Spenden-Zahlungsgateway.', 'give' ) ),
							'type'    => 'wysiwyg',
							'default' => '&nbsp;&nbsp;&nbsp;&nbsp;<em>' . get_bloginfo( 'sitename' ) . '</em><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>111 Not A Real St.</em><br>&nbsp;&nbsp;&nbsp;&nbsp;<em>Anytown, CA 12345 </em><br>',
						),
						array(
							'id'   => 'give_title_general_settings_4',
							'type' => 'sectionend',
						),
					);

					break;
			}// End switch().

			/**
			 * Filter the emails settings.
			 * Backward compatibility: Please do not use this filter. This filter is deprecated in 1.8
			 */
			$settings = apply_filters( 'give_settings_emails', $settings );

			/**
			 * Filter the settings.
			 *
			 * @since  1.8
			 *
			 * @param  array $settings
			 */
			$settings = apply_filters( 'give_get_settings_' . $this->id, $settings );

			// Output.
			return $settings;
		}

		/**
		 * Get sections.
		 *
		 * @since 1.8
		 * @return array
		 */
		public function get_sections() {
			$sections = array(
				'donor-email'    => esc_html__( 'Spender-E-Mails', 'give' ),
				'admin-email'    => esc_html__( 'Admin-E-Mails', 'give' ),
				'email-settings' => esc_html__( 'Email Einstellungen', 'give' ),
				'contact'        => esc_html__( 'Kontakt Information', 'give' ),
			);

			return apply_filters( 'give_get_sections_' . $this->id, $sections );
		}

		/**
		 * Render email_notification field type
		 *
		 * @since  2.0
		 * @access public
		 */
		public function email_notification_setting() {
			// Load email notification table.
			require_once GIVE_PLUGIN_DIR . 'includes/admin/emails/class-email-notification-table.php';

			// Init table.
			$email_notifications_table = new Give_Email_Notification_Table();

			// Print table.
			$email_notifications_table->prepare_items();
			$email_notifications_table->display();
		}

		/**
		 * Output the settings.
		 *
		 * Note: if you want to overwrite this function then manage show/hide save button in your class.
		 *
		 * @since  1.8
		 * @return void
		 */
		public function output() {
			if ( $this->enable_save ) {
				$GLOBALS['give_hide_save_button'] = apply_filters( 'give_hide_save_button_on_email_admin_setting_page', false );
			}

			$settings = $this->get_settings();

			Give_Admin_Settings::output_fields( $settings, 'give_settings' );
		}
	}

endif;

return new Give_Settings_Email();
