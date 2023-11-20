<?php

namespace Give\DonationForms\ValueObjects;

use Give\Framework\Support\ValueObjects\Enum;

/**
 * @since 2.24.0
 *
 * @method static DonationFormStatus PENDING()
 * @method static DonationFormStatus PUBLISHED()
 * @method static DonationFormStatus PRIVATE()
 * @method static DonationFormStatus DRAFT()
 * @method static DonationFormStatus TRASH()
 * @method bool isPending()
 * @method bool isPublished()
 * @method bool isPrivate()
 * @method bool isDraft()
 * @method bool isTrashed()
 */
class DonationFormStatus extends Enum
{
    const PENDING = 'pending';
    const PUBLISHED = 'publish';
    const PRIVATE = 'private';
    const DRAFT = 'draft';
    const TRASH = 'trash';

    /**
     * @since 2.24.0
     *
     * @return array
     */
    public static function labels(): array
    {
        return [
            self::PENDING => __( 'Ausstehend', 'give' ),
            self::PUBLISHED => __( 'Veröffentlicht', 'give' ),
            self::PRIVATE => __( 'Privat', 'give' ),
            self::DRAFT => __( 'Entwurf', 'give' ),
            self::TRASH => __( 'Müll', 'give' ),
        ];
    }

    /**
     * @since 2.24.0
     *
     * @return string
     */
    public function label(): string
    {
        return self::labels()[ $this->getValue() ];
    }
}
