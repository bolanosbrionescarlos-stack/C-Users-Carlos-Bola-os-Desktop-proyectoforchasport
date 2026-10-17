<?php

namespace App\Enums;

enum CouponType: string
{
    case Percent = 'percent';
    case Fixed = 'fixed';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::Percent->value => 'Porcentaje (%)',
            self::Fixed->value => 'Monto fijo (S/)',
        ];
    }
}
