<?php

namespace Drosalys\Bundle\PdfBundle;

use Drosalys\Bundle\PdfBundle\DependencyInjection\DrosalysPdfExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DrosalysPdfBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $ext = new DrosalysPdfExtension([], $container);
    }
}