<?php
/**
 * Give Settings Page/Tab
 *
 * @package     Give
 * @subpackage  Classes/Give_Settings_Display
 * @copyright   Copyright (c) 2016, Best Donation
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Give_Settings_Display' ) ) :

	/**
	 * Give_Settings_Display.
	 *
	 * @sine 1.8
	 */
	class Give_Settings_Display extends Give_Settings_Page {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id    = 'display';
			$this->label = __( 'Standardoptionen', 'give' );

			$this->default_tab = 'display-settings';

			parent::__construct();
		}

		/**
		 * Get settings array.
		 *
		 * @since  1.8
		 * @return array
		 */
		public function get_settings() {
			$settings        = [];
			$current_section = give_get_current_setting_section();

			switch ( $current_section ) {
				case 'display-settings':
					$settings = [
						// Section 1: Display
						[
							'id'   => 'give_title_display_settings_1',
							'type' => 'title',
						],
						[
							'name'    => __( 'Namenstitel-Präfix', 'give' ),
							'desc'    => __( 'Möchten Sie, dass vor dem Vornamen ein Namenstitel-Präfixfeld angezeigt wird?', 'give' ),
							'id'      => 'name_title_prefix',
							'type'    => 'radio_inline',
							'default' => 'disabled',
							'options' => [
								'disabled' => __( 'Deaktiviert', 'give' ),
								'required' => __( 'Erforderlich', 'give' ),
								'optional' => __( 'Optional', 'give' ),
							],
						],
						[
							'name'                => __( 'Titelpräfixe', 'give' ),
							'desc'                => __( 'Fügen Sie über das Feld oben Anreden zur Dropdown-Liste hinzu oder entfernen Sie sie.', 'give' ),
							'id'                  => 'title_prefixes',
							'type'                => 'chosen',
							'data_type'           => 'multiselect',
							'allow-custom-values' => true,
							'wrapper_class'       => 'give-hidden give-title-prefixes-settings-wrap',
							'style'               => 'width: 30%',
							'options'             => give_get_default_title_prefixes(),
						],
						[
							'name'    => __( 'Firmenfeld', 'give' ),
							'desc'    => __( 'Möchten Sie, dass auf allen Spendenformularen nach den Feldern „Vorname“ und „Nachname“ ein Firmenfeld angezeigt wird? Sie können diese Option auch pro Formular aktivieren.', 'give' ),
							'id'      => 'company_field',
							'type'    => 'radio_inline',
							'default' => 'disabled',
							'options' => [
								'disabled' => __( 'Deaktiviert', 'give' ),
								'required' => __( 'Erforderlich', 'give' ),
								'optional' => __( 'Optional', 'give' ),
							],
						],
						[
							'name'    => __( 'Nachname-Feld erforderlich', 'give' ),
							'desc'    => __( 'Möchten Sie die Vor- und Nachnamen des Spenders anfordern? Standardmäßig ist bei einer Spende nur das Feld „Vorname“ erforderlich. Diese Einstellung ist auch pro Formular konfigurierbar.', 'give' ),
							'id'      => 'last_name_field_required',
							'type'    => 'radio_inline',
							'default' => 'optional',
							'options' => [
								'required' => __( 'Erforderlich', 'give' ),
								'optional' => __( 'Optional', 'give' ),
							],
						],
						[
							'name'    => __( 'Anonyme Spenden', 'give' ),
							'desc'    => __( 'Möchten Sie Spendern die Möglichkeit geben, sich beim Spenden als anonym zu kennzeichnen? Dadurch wird verhindert, dass ihre Informationen öffentlich auf Ihrer Website angezeigt werden. Sie erhalten ihre Informationen jedoch weiterhin für Ihre Unterlagen im Admin-Bereich.', 'give' ),
							'id'      => 'anonymous_donation',
							'type'    => 'radio_inline',
							'default' => 'disabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'    => __( 'Spenderkommentare', 'give' ),
							'desc'    => __( 'Möchten Sie Spendern die Möglichkeit geben, ihrer Spende einen Kommentar hinzuzufügen? Der Kommentar wird öffentlich auf der Pinnwand des Spenders angezeigt, wenn er sich nicht dafür entscheidet, anonym zu spenden.', 'give' ),
							'id'      => 'donor_comment',
							'type'    => 'radio_inline',
							'default' => 'disabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'  => __( 'Link zu Anzeigeeinstellungen und Dokumenten', 'give' ),
							'id'    => 'display_settings_docs_link',
							'url'   => esc_url( 'http://docs.#.com/settings-form-options' ),
							'title' => __( 'Anzeigeoptionen-Einstellungen', 'give' ),
							'type'  => 'give_docs_link',
						],
						[
							'id'   => 'give_title_display_settings_1',
							'type' => 'sectionend',
						],
					];
					break;

				case 'post-types':
					$settings = [
						[
							'id'   => 'give_title_display_settings_2',
							'type' => 'title',
						],
						[
							'name'    => __( 'Einzelansichten bilden', 'give' ),
							'desc'    => __( 'Standardmäßig sind für alle Spendenformulare Einzelansichten aktiviert. Dadurch wird auf Ihrer Website eine spezifische URL für dieses Formular erstellt. Wenn Sie „Deaktiviert“ auswählen, wird die Einzelansicht nicht öffentlich angezeigt. Hinweis: Wenn Sie „Deaktiviert“ auswählen, müssen Sie jedes Formular mithilfe eines Blocks, eines Shortcodes oder eines Widgets einbetten, um es anzuzeigen.', 'give' ),
							'id'      => 'forms_singular',
							'type'    => 'radio_inline',
							'default' => 'enabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'    => __( 'Formulararchive', 'give' ),
							'desc'    => sprintf(
								wp_kses(
									__( 'Auf den Archivseiten werden alle von Ihnen erstellten Spendenformulare aufgelistet. Diese Option deaktiviert nur die Archivseite(n) des Formulars. Die Einzelansicht des Formulars wird weiterhin angezeigt. Hinweis: Das müssen Sie tun <a href="%s">Aktualisieren Sie Ihre Permalinks</a>nachdem diese Option aktiviert wurde.', 'give' ),
									[
										'a' => [
											'href'   => [],
											'target' => [],
										],
									]
								),
								esc_url( admin_url( 'options-permalink.php' ) )
							),
							'id'      => 'forms_archives',
							'type'    => 'radio_inline',
							'default' => 'enabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'    => __( 'Formularauszüge', 'give' ),
							'desc'    => __( 'Der Auszug ist eine optionale Zusammenfassung oder Beschreibung eines Spendenformulars; Kurz gesagt, eine Zusammenfassung, warum der Benutzer etwas geben sollte.', 'give' ),
							'id'      => 'forms_excerpt',
							'type'    => 'radio_inline',
							'default' => 'enabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'    => __( 'Ausgewähltes Bild bilden', 'give' ),
							'desc'    => __( 'Wenn Sie die vorgestellte Bildfunktion nicht nutzen möchten, können Sie sie mit dieser Option deaktivieren. Sie wird dann nicht für einzelne Spendenformulare angezeigt.', 'give' ),
							'id'      => 'form_featured_img',
							'type'    => 'radio_inline',
							'default' => 'enabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'    => __( 'Empfohlene Bildgröße', 'give' ),
							'desc'    => __( 'Das vorgestellte Bild ist ein Bild, das als repräsentatives Bild für ein Spendenformular ausgewählt wird. Einige Themen verfügen möglicherweise über benutzerdefinierte Bildgrößen. Bitte wählen Sie die Größe aus, die Sie für das Bild Ihres einzelnen Spendenformulars anzeigen möchten.', 'give' ),
							'id'      => 'featured_image_size',
							'type'    => 'select',
							'default' => 'large',
							'options' => give_get_featured_image_sizes(),
						],
						[
							'name'    => __( 'Einzelformular-Seitenleiste', 'give' ),
							'desc'    => __( 'Über die Seitenleiste können Sie der Einzelformularansicht „Beste Spende“ zusätzliche Widgets hinzufügen. Wenn Sie die Seitenleiste nicht verwenden möchten, können Sie sie mit dieser Option deaktivieren.', 'give' ),
							'id'      => 'form_sidebar',
							'type'    => 'radio_inline',
							'default' => 'enabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'  => __( 'Link zu Beitragstypendokumenten', 'give' ),
							'id'    => 'post_types_settings_docs_link',
							'url'   => esc_url( 'http://docs.#.com/settings-post-types' ),
							'title' => __( 'Einstellungen für Beitragstypen', 'give' ),
							'type'  => 'give_docs_link',
						],
						[
							'id'   => 'give_title_display_settings_2',
							'type' => 'sectionend',
						],
					];
					break;

				case 'taxonomies':
					$settings = [
						[
							'id'   => 'give_title_display_settings_3',
							'type' => 'title',
						],
						[
							'name'    => __( 'Formularkategorien', 'give' ),
							'desc'    => __( 'Aktivieren Sie Kategorien für alle besten Spendenformulare.', 'give' ),
							'id'      => 'categories',
							'type'    => 'radio_inline',
							'default' => 'disabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'    => __( 'Formular-Tags', 'give' ),
							'desc'    => __( 'Aktivieren Sie Tags für alle besten Spendenformulare.', 'give' ),
							'id'      => 'tags',
							'type'    => 'radio_inline',
							'default' => 'disabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'  => __( 'Link zu Taxonomien-Dokumenten', 'give' ),
							'id'    => 'taxonomies_settings_docs_link',
							'url'   => esc_url( 'http://docs.#.com/settings-taxonomies' ),
							'title' => __( 'Taxonomie-Einstellungen', 'give' ),
							'type'  => 'give_docs_link',
						],
						[
							'id'   => 'give_title_display_settings_3',
							'type' => 'sectionend',
						],
					];
					break;

				case 'term-and-conditions':
					$settings = [
						[
							'id'   => 'give_title_display_settings_4',
							'type' => 'title',
						],
						[
							'name'    => __( 'Geschäftsbedingungen', 'give' ),
							'desc'    => __( 'Möchten Sie verlangen, dass Spender bei einer Spende Ihren Bedingungen zustimmen? Hinweis: Sie können diese Option aktivieren/deaktivieren und die Bedingungen auch pro Formular anpassen.', 'give' ),
							'id'      => 'terms',
							'type'    => 'radio_inline',
							'default' => 'disabled',
							'options' => [
								'enabled'  => __( 'Ermöglicht', 'give' ),
								'disabled' => __( 'Deaktiviert', 'give' ),
							],
						],
						[
							'name'       => __( 'Den Bedingungen zustimmen. Label', 'give' ),
							'desc'       => __( 'Die Beschriftung wird neben dem Kontrollkästchen „Akzeptiere die Bedingungen“ angezeigt. Passen Sie es hier an oder lassen Sie es leer, um den Standard-Platzhaltertext zu verwenden. Hinweis: Sie können die Beschriftung pro Formular anpassen.', 'give' ),
							'id'         => 'agree_to_terms_label',
							'attributes' => [
								'placeholder' => esc_attr__( 'Stimmen Sie den Bedingungen zu?', 'give' ),
								'rows'        => 1,
							],
							'type'       => 'textarea',
						],
						[
							'name' => __( 'Vertragstext', 'give' ),
							'desc' => __( 'Dies ist der eigentliche Text, dem der Benutzer zustimmen muss, um spenden zu können. Hinweis: Sie können den Inhalt pro Formular nach Bedarf anpassen.', 'give' ),
							'id'   => 'agreement_text',
							'type' => 'wysiwyg',
						],
						[
							'name'  => __( 'Link zu den Allgemeinen Geschäftsbedingungen und Dokumenten', 'give' ),
							'id'    => 'terms_settings_docs_link',
							'url'   => esc_url( 'http://docs.estdonation.com/settings-terms' ),
							'title' => __( 'Allgemeine Geschäftsbedingungen-Einstellungen', 'give' ),
							'type'  => 'give_docs_link',
						],
						[
							'id'   => 'give_title_display_settings_4',
							'type' => 'sectionend',
						],
					];
					break;
			}

			/**
			 * Filter the display options settings.
			 * Backward compatibility: Please do not use this filter. This filter is deprecated in 1.8
			 */
			$settings = apply_filters( 'give_settings_display', $settings );

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
			$sections = [
				'display-settings'    => __( 'Form Fields', 'give' ),
				'post-types'          => __( 'Post Types', 'give' ),
				'taxonomies'          => __( 'Taxonomies', 'give' ),
				'term-and-conditions' => __( 'Terms and Conditions', 'give' ),
			];

			return apply_filters( 'give_get_sections_' . $this->id, $sections );
		}
	}

endif;

return new Give_Settings_Display();
