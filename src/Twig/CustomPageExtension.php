<?php

namespace App\Twig;

use App\Entity\CustomPage;
use App\Enum\CustomPageEnum;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CustomPageExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('get_key_enum_status', [$this, 'getKeyEnumStatus']),
        ];
    }

    public function getFunctions(): array
    {
        return [
//            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function getKeyEnumStatus(CustomPage $customPage)
    {
        return CustomPageEnum::getCustomPageKey($customPage->getStatus());
    }
}
