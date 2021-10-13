<?php

namespace App\Enum;

use App\Utils\EnumManipulator;

class CustomPageEnum
{
    const PAGE_ENABLE = 1;
    const PAGE_DISABLE = -1;
    const PAGE_DRAFT = 0;

    /**
     * @param null|int $key
     * @return string
     */
    public static function getCustomPageKey(?int $key): string
    {
        if ($key === self::PAGE_ENABLE) {
            return 'entity.custom_page.status.enable.label';
        } elseif ($key === self::PAGE_DISABLE) {
            return 'entity.custom_page.status.disable.label';
        } elseif ($key === self::PAGE_DRAFT) {
            return 'entity.custom_page.status.draffted.label';
        }

        return '';
    }

    public static function getFormChoices(): array
    {
        $choices = [];
        $statusList = EnumManipulator::getEnumConstants(self::class);

        foreach ($statusList as $constValue) {
            $choices[self::getCustomPageKey($constValue)] = $constValue;
        }

        return $choices;
    }
}