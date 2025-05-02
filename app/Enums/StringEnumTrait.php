<?php

namespace App\Enums;

trait StringEnumTrait
{
    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, static::cases());
    }
}
