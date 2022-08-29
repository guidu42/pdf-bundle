<?php

namespace Drosalys\PdfBundle\Twig\Extension;

use Drosalys\PdfBundle\Twig\Runtime\CssInlinerRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CssInlinerExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('inline_css_webpack', [CssInlinerRuntime::class, 'foo'], ['is_safe' => ['all']]),
        ];
    }
}