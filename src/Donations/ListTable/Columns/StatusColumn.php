<?php

declare(strict_types=1);

namespace Give\Donations\ListTable\Columns;

use Give\Donations\Models\Donation;
use Give\Framework\ListTable\ModelColumn;

/**
 * @since 2.24.0
 *
 * @extends ModelColumn<Donation>
 */
class StatusColumn extends ModelColumn
{
    protected $sortColumn = 'status';

    /**
     * @since 2.24.0
     *
     * @inheritDoc
     */
    public static function getId(): string
    {
        return 'status';
    }

    /**
     * @since 2.24.0
     *
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __('Tor', 'give');
    }

    /**
     * @since 2.24.0
     *
     * @inheritDoc
     *
     * @param Donation $model
     */
    public function getCellValue($model): string
    {
        return sprintf(
        '<div class="statusBadge statusBadge--%1$s" style="background:rgb(236,108,54) !important;"><p>%2$s</p></div>',
            $model->status->getValue(),
            $model->status->label()
        );
    }
}
