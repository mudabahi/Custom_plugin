<?php

declare(strict_types=1);

namespace Give\DonationForms\ListTable\Columns;

use Give\DonationForms\Models\DonationForm;
use Give\Framework\ListTable\ModelColumn;

/**
 * @since 2.24.0
 *
 * @extends ModelColumn<DonationForm>
 */
class GoalColumn extends ModelColumn
{
    /**
     * @since 2.24.0
     *
     * @inheritDoc
     */
    public static function getId(): string
    {
        return 'goal';
    }

    /**
     * @since 2.24.0
     *
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __('Ziel', 'give');
    }

    /**
     * @since 2.24.0
     *
     * @inheritDoc
     *
     * @param DonationForm $model
     */
    public function getCellValue($model): string
    {
        if ( ! $model->goalOption) {
            return __('Kein Ziel gesetzt', 'give');
        }

        $goal = give_goal_progress_stats($model->id);
        $goalPercentage = ('percentage' === $goal['format']) ? str_replace('%', '',
            $goal['actual']) : max(min($goal['progress'], 100), 0);

        $template = '
            <div
                role="progressbar"
                aria-labelledby="giveDonationFormsProgressBar-%1$d"
                aria-valuenow="%2$s"
                aria-valuemin="0"
                aria-valuemax="100"
                class="goalProgress"
            >
                <span style="width: %2$s%%"></span>
            </div>
            <div id="giveDonationFormsProgressBar-%1$d">
                <span class="goal">%3$s</span>%4$s %5$s
            </div>
        ';

        return sprintf(
            $template,
            $model->id,
            $goalPercentage,
            $goal['actual'],
            sprintf(
                ($goal['format'] !== 'percentage' ? ' %s %s' : ''),
                __('of', 'give'),
                $goal['goal']
            ),
            sprintf(
                ($goal['progress'] >= 100 ? '<span class="goalProgress--achieved"><img src="%1$s" alt="%2$s" />%3$s</span>' : ''),
                GIVE_PLUGIN_URL . 'assets/dist/images/list-table/star-icon.svg',
                __('Goal achieved icon', 'give'),
                __('Ziel erreicht!', 'give')
            )
        );
    }
}
