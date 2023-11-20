<?php
/**
 * Give Settings Page/Tab
 *
 * @package     Give
 * @subpackage  Classes/Give_Settings_Advanced
 * @copyright   Copyright (c) 2016, Best Donation
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.8
 */

use Give\Onboarding\Setup\Page as SetupPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Give_Settings_Advanced' ) ) :

	/**
	 * Give_Settings_Advanced.
	 *
	 * @sine 1.8
	 */
	class Give_Settings_Advanced extends Give_Settings_Page {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id    = 'advanced';
			$this->label = __( 'Fortschrittlich', 'give' );

			$this->default_tab = 'advanced-options';

			if ( $this->id === give_get_current_setting_tab() ) {
				add_action(
					'give_admin_field_remove_cache_button',
					[
						$this,
						'render_remove_cache_button',
					],
					10,
					1
				);
				add_action( 'give_save_settings_give_settings', [ $this, 'validate_settngs' ] );
			}

			parent::__construct();
		}

		/**
		 * Get settings array.
		 *
		 * @return array
		 * @since  1.8
		 */
		public function get_settings() {
			$settings = [];

			$current_section = give_get_current_setting_section();
            $setupPage = esc_url(admin_url('edit.php?post_type=give_forms&page=give-setup'));

			switch ( $current_section ) {
				case 'advanced-options':
					$settings = [
						[
							'id'   => 'give_title_data_control_2',
							'type' => 'title',
						],
						[
							'name'    => __( 'Default Best Donation Styles', 'give' ),
							'desc'    => __( 'Dies steuert die Standardstile von Best Donation für ältere Spendenformulare und andere Front-End-Elemente. Wenn Sie diese Option deaktivieren, müssen Sie Ihre eigenen Stile bereitstellen.', 'give' ),
							'id'      => 'css',
							'type'    => 'radio_inline',
							'default' => 'enabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'    => __( 'Daten bei der Deinstallation entfernen', 'give' ),
							'desc'    => __( 'Wenn das Plugin gelöscht wird, entfernen Sie alle Best Donation-Daten vollständig. Dazu gehören alle Einstellungen für die beste Spende, Formulare, Formular-Meta, Spender, Spenderdaten und Spenden. Alles.', 'give' ),
							'id'      => 'uninstall_on_delete',
							'type'    => 'radio_inline',
							'default' => 'disabled',
							'options' => [
								'enabled'  => __( 'Ja, alle Daten entfernen', 'give' ),
								'disabled' => __( 'Nein, ich behalte meine Einstellungen und Spendendaten für die beste Spende bei', 'give' ),
							],
						],
						[
							'name'    => __( 'Standardbenutzerrolle', 'give' ),
							'desc'    => __( 'Benutzer erhalten diese Benutzerrolle, wenn sie sich für die Erstellung eines WordPress-/Site-Kontos zusammen mit ihrer Spende entscheiden.', 'give' ),
							'id'      => 'donor_default_user_role',
							'type'    => 'select',
							'default' => 'give_donor',
							'options' => give_get_user_roles(),
						],
						[
							/* translators: %s: the_content */
							'name'    => sprintf( __( '%s filter', 'give' ), '<code>the_content</code>' ),
							/* translators: 1: https://codex.wordpress.org/Plugin_API/Filter_Reference/the_content 2: the_content */
							'desc'    => sprintf( __( 'Dies steuert, ob der Inhalt alter Best Donation-Formulare wie WordPress-Inhalt behandelt wird oder nicht. Das Deaktivieren dieser Option bedeutet, dass Dinge wie Social Sharing und andere durch Themes oder Plugins hinzugefügte Funktionen zum Verbessern oder Anhängen von Inhalten nicht auf den alten Best Donation-Formularinhalt angewendet werden. <a href="%1$s" target="_blank">Erfahren Sie mehr</a> about %2$s filter.', 'give' ), esc_url( 'https://codex.wordpress.org/Plugin_API/Filter_Reference/the_content' ), '<code>the_content</code>' ),
							'id'      => 'the_content_filter',
							'default' => 'enabled',
							'type'    => 'radio_inline',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'    => __( 'Speicherort für das Laden des Skripts', 'give' ),
							'desc'    => __( 'Dadurch können Sie Ihre Best Donation-Skripte entweder im <code>&lt;head&gt;</code> oder in der Fußzeile Ihrer Website laden.', 'give' ),
							'id'      => 'scripts_footer',
							'type'    => 'radio_inline',
							'default' => 'disabled',
							'options' => [
								'disabled' => __( 'Head', 'give' ),
								'enabled'  => __( 'Footer', 'give' ),
							],
						],
						[
							'name'          => __( 'Setup Page', 'give' ),
							/* translators: %s: about page URL */
							'desc'          => sprintf(
                                wp_kses(
                                    __(
                                        'This option controls the display of the %s when Best Donation is first installed.',
                                        'give'
                                    ),
                                    [
                                        'a' => [
                                            'href' => [],
                                            'target' => [],
                                        ],
                                    ]
                                ),
                                SetupPage::getSetupPageEnabledOrDisabled(
                                ) === SetupPage::ENABLED ? "<a href='$setupPage' target='_blank'>Best Donation Setup page</a>" : 'Best Donation Setup page'
                            ),
							'id'            => 'setup_page_enabled',
							'type'          => 'radio_inline',
							'default'       => give_is_setting_enabled(
								SetupPage::getSetupPageEnabledOrDisabled()
							)
								? SetupPage::ENABLED
								: SetupPage::DISABLED,
							'options'       => [
								SetupPage::ENABLED  => __( 'Ermöglicht', 'give' ),
								SetupPage::DISABLED => __( 'Deaktiviert', 'give' ),
							],
							'wrapper_class' => version_compare( get_bloginfo( 'version' ), '5.0', '<=' ) ? 'give-hidden' : null,
						],
						[
							'name'    => __( 'URL-Präfix der Formularseite', 'give' ),
							'desc'    => sprintf(
								__( 'Dieser Slug wird als Basis für die (für Benutzer/Site-Besucher unsichtbare) Iframe-URL verwendet, die alle Formularvorlagen außer der alten Formularvorlage enthält. Die URL sieht derzeit so aus: %1$s. Mit dieser Option können Sie diese URL ändern, um Konflikte zu vermeiden, die möglicherweise mit anderen Seiten und URLs auf der Website bestehen.', 'give' ),
								'<code>' . trailingslashit( home_url() ) . Give()->routeForm->getBase() . '/{form-slug}</code>'
							),
							'id'      => Give()->routeForm->getOptionName(),
							'type'    => 'text',
							'default' => Give()->routeForm->getBase(),
						],
						[
							'name'    => __( 'Erweiterte Datenbankaktualisierungen', 'give' ),
							'desc'    => __( 'Diese Option ist nur für fortgeschrittene Benutzer und/oder Benutzer, die vom Best Donation-Support angewiesen werden. Sobald Sie dies aktivieren, haben Sie die Möglichkeit, die Ausführungsreihenfolge zu überschreiben und eine erneute Ausführung für Datenbankaktualisierungen unter Spenden > Tools > Daten zu erzwingen. Wenn Sie nicht wissen, was Sie tun, können Sie mit aktivierter Option leicht Probleme verursachen. Lassen Sie diese Option nicht aktiviert, nachdem Sie die Fehlerbehebung abgeschlossen haben.', 'give' ),
							'id'      => 'enable_database_updates',
							'type'    => 'radio_inline',
							'default' => 'disabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'        => 'Bester Spenden-Cache',
							'id'          => 'give-clear-cache',
							'buttonTitle' => __( 'Cache leeren', 'give' ),
							'desc'        => __( 'Klicken Sie auf diese Schaltfläche, wenn Sie den Cache von Best Donation löschen möchten. Das Plugin speichert allgemeine Einstellungen und Abfragen im Cache, um die Leistung zu optimieren. Durch das Leeren des Caches werden diese gespeicherten Abfragen entfernt und mit der Neuerstellung begonnen.', 'give' ),
							'type'        => 'remove_cache_button',
						],
						[
							'name'  => __( 'Link zu den Dokumenten zu den erweiterten Einstellungen', 'give' ),
							'id'    => 'advanced_settings_docs_link',
							'url'   => esc_url( 'http://docs.best.com/settings-advanced' ),
							'title' => __( 'Erweiterte Einstellungen', 'give' ),
							'type'  => 'give_docs_link',
						],
						[
							'id'   => 'give_title_data_control_2',
							'type' => 'sectionend',
						],
					];
					break;

				case 'akismet-spam-protection':
					$settings = [
						[
							'id'   => 'give_setting_advanced_section_akismet_spam_protection',
							'type' => 'title',
						],
						[
							'name'    => __( 'Akismet SPAM Protection', 'give' ),
							'desc'    => __( 'Fügen Sie mit Akismet Ihren Spendeneinsendungen eine Ebene des SPAM-Schutzes hinzu. Wenn möglich, werden Spendeneinsendungen zunächst über die SPAM-Prüf-API von Akismet gesendet, sofern Sie das Plugin aktiviert und konfiguriert haben.', 'give' ),
							'id'      => 'akismet_spam_protection',
							'type'    => 'radio_inline',
							'default' => ( give_check_akismet_key() ) ? 'enabled' : 'disabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'             => __( 'Whitelist per E-Mail', 'give' ),
							'desc'             => sprintf(
								'%1$s %2$s',
								__( 'Fügen Sie E-Mails einzeln hinzu, um sicherzustellen, dass Spenden über diese E-Mail den Akismet-SPAM-Filter von Best Donation umgehen. E-Mails, die hier zur Liste hinzugefügt werden, dürfen immer spenden, auch wenn sie von Akismet markiert wurden.', 'give' ),
								sprintf(
									__( 'Um dauerhaft zu verhindern, dass E-Mails von Akismet als SPAM gekennzeichnet werden <a href="%1$s" target="_blank">contact their team here</a>.', 'give' ),
									esc_url( 'https://akismet.com/contact/' )
								)
							),
							'id'               => 'akismet_whitelisted_email_addresses',
							'type'             => 'email',
							'attributes'       => [
								'placeholder' => 'test@example.com',
							],
							'repeat'           => true,
							'repeat_btn_title' => esc_html__( 'Add Email', 'give' ),
						],
						[
							'id'   => 'give_setting_advanced_section_akismet_spam_protection',
							'type' => 'sectionend',
						],
					];
					break;
			}

			/**
			 * Hide caching setting by default.
			 *
			 * @since 2.0
			 */
			if ( apply_filters( 'give_settings_advanced_show_cache_setting', false ) ) {
				array_splice(
					$settings,
					1,
					0,
					[
						[
							'name'    => __( 'Zwischenspeicher', 'give' ),
							'desc'    => __( 'Wenn das Caching aktiviert ist, beginnt das Plugin mit dem Caching benutzerdefinierter Beitragstyp-bezogener Abfragen und reduziert die Gesamtladezeit.', 'give' ),
							'id'      => 'cache',
							'type'    => 'radio_inline',
							'default' => 'enabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
					]
				);
			}

			/**
			 * Filter the advanced settings.
			 * Backward compatibility: Please do not use this filter. This filter is deprecated in 1.8
			 */
			$settings = apply_filters( 'give_settings_advanced', $settings );

			/**
			 * Filter the settings.
			 *
			 * @param array $settings
			 *
			 * @since  1.8
			 */
			$settings = apply_filters( 'give_get_settings_' . $this->id, $settings );

			// Output.
			return $settings;
		}

		/**
		 * Get sections.
		 *
		 * @return array
		 * @since 1.8
		 */
		public function get_sections() {
			$sections = [
				'advanced-options'        => __( 'Erweiterte Optionen', 'give' ),
				'akismet-spam-protection' => __( 'Akismet SPAM Protection', 'give' ),
			];

			return apply_filters( 'give_get_sections_' . $this->id, $sections );
		}


		/**
		 *  Render remove_cache_button field type
		 *
		 * @param array $field
		 *
		 * @since 2.25.2  add nonce field
		 * @since  2.1
		 * @access public
		 */
		public function render_remove_cache_button( $field ) {
			?>
			<tr valign="top" <?php echo ! empty( $field['wrapper_class'] ) ? 'class="' . $field['wrapper_class'] . '"' : ''; ?>>
				<th scope="row" class="titledesc">
					<label
						for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['name'] ); ?></label>
				</th>
				<td class="give-forminp">
					<button type="button" id="<?php echo esc_attr( $field['id'] ); ?>"
							class="button button-secondary"><?php echo esc_html( $field['buttonTitle'] ); ?></button>
					<?php echo Give_Admin_Settings::get_field_description( $field ); ?>
                    <?php wp_nonce_field('give_cache_flush', 'give_cache_flush_nonce'); ?>
				</td>
			</tr>
			<?php
		}


		/**
		 * Validate setting
		 *
		 * @param array $options
		 *
		 * @since  2.2.0
		 * @access public
		 */
		public function validate_settngs( $options ) {
			// Sanitize data.
			$akismet_spam_protection = isset( $options['akismet_spam_protection'] )
				? $options['akismet_spam_protection']
				: ( give_check_akismet_key() ? 'enabled' : 'disabled' );

			// Show error message if Akismet not configured and Admin try to save 'enabled' option.
			if (
				give_is_setting_enabled( $akismet_spam_protection )
				&& ! give_check_akismet_key()
			) {
				Give_Admin_Settings::add_error(
					'give-akismet-protection',
					__( 'Please properly configure Akismet to enable SPAM protection.', 'give' )
				);

				give_update_option( 'akismet_spam_protection', 'disabled' );
			}
		}
	}

endif;

return new Give_Settings_Advanced();
