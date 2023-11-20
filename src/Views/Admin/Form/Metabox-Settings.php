<?php

global $post;

use Give\Form\Template;
use Give\Helpers\Form\Template as FormTemplateUtils;
use Give\Helpers\Form\Template\Utils\Admin as AdminFormTemplateUtils;

$activatedTemplate = FormTemplateUtils::getActiveID($post->ID);
$registeredTemplates = Give()->templates->getTemplates();
?>
<div class="form_template_options_wrap inner-panel<?php
echo $activatedTemplate ? ' has-activated-template' : ''; ?>">
    <strong class="templates-list-heading"><?php
        _e('Verfügbare Formularvorlagen von Best Donation', 'give'); ?></strong>
    <div class="templates-list">
        <?php
        /* @var Template $template */
        foreach ($registeredTemplates as $template) {
            $isActive = $activatedTemplate === $template->getID();

            printf(
                '<div class="template-info %1$s" data-id="%2$s">
                            <img class="template-image" src="%3$s"/>
							<div class="action">
								<div class="template-name">%4$s <span class="badge">%5$s</span></div>
								<button class="button %7$s">%6$s</button>
							</div>
						</div>',
                $template->getID() . ($isActive ? ' active' : ''),
                $template->getID(),
                $template->getImage(),
                $template->getName(),
                __('active', 'give'),
                $isActive ? __('Deaktivieren', 'give') : __('aktivieren Sie', 'give'),
                $isActive ? 'js-template--deactivate' : 'js-template--activate'
            );
        }
        ?>
    </div>

    <div class="form-template-introduction">
        <p>
            <?php
            _e('Was sind Formularvorlagen? Beste Spende', 'give'); ?>
        </p>
        <p class="give-field-description form-template-description"><?php
            _e(
                'Mit Formularvorlagen können Sie das Erscheinungsbild eines # Spendenformulars auf Ihrer Website ändern. Jede Vorlage hat ein anderes Design, Layout und andere Funktionen. Wählen Sie diejenige, die Ihrem Geschmack und den Anforderungen Ihres Anliegens entspricht. Hinweis: Die Kompatibilität mit Add-ons und Plugins oder Themes von Drittanbietern kann nicht garantiert werden. Gehen Sie Ihre Spendenformulare immer sorgfältig durch, bevor Sie online gehen!',
                'give'
            ); ?></p>

        <!-- <div class="form-template-notice">
            <img src="<?= esc_url(GIVE_PLUGIN_URL . 'assets/dist/images/admin/cap-books.svg'); ?>" alt="" />
            <p>
                <?= esc_html__('Erfahren Sie, wie Sie mit Best Donation das perfekte Spendenformular erstellen#', 'give'); ?>
            </p>
            <a href="http://docs.#.com/form-templates/" target="_blank">
                <?= __('Learn More', 'give'); ?>
                <svg viewbox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                  <path d="m10.96 9.68 6.897-6.896M18.53 6.148V2.11h-4.037M9.279 2.11H7.597c-4.205 0-5.887 1.683-5.887 5.888v5.046c0 4.205 1.682 5.887 5.887 5.887h5.046c4.205 0 5.887-1.682 5.887-5.887v-1.682" stroke="#fff" stroke-width="1.261" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                </svg>
            </a>
        </div> -->
    </div>

    <div class="form-template-options-introduction">
        <strong>
            <?php
            _e('Optionen für Formularvorlagen', 'give'); ?>
        </strong>
        <p class="give-field-description"><?php
            _e(
                'Passen Sie die Formularvorlage mit den folgenden Optionen an. Sehen Sie sich diese Anpassungen jederzeit über die Schaltfläche „Vorschau“ an.',
                'give'
            ); ?></p>
    </div>

    <div class="form-template-options">
        <?php
        /* @var Template $template */
        foreach ($registeredTemplates as $template) {
            printf(
                '<div class="template-options %1$s" data-id="%2$s">%3$s</div>',
                $template->getID() . ($activatedTemplate === $template->getID() ? ' active' : ''),
                $template->getID(),
                AdminFormTemplateUtils::renderMetaboxSettings($template)
            );
        }
        ?>
    </div>
</div>
