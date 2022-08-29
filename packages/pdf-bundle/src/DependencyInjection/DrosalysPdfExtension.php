<?php

namespace Drosalys\PdfBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DrosalysPdfExtension extends Extension implements PrependExtensionInterface
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('drosalys.pdf.chrome_bin', $config['chrome_bin']);
        $container->setParameter('drosalys.pdf.save_dir', $config['save_dir']);

//        if(!$config['tmp_dir']){
//            $container->setParameter('pdf_tmp_dir', '%kernel.cache_dir%/pdf_tmp');
//        }else{
//            $container->setParameter('pdf_tmp_dir', $config['tmp_dir']);
//        }

    }

    public function prepend(ContainerBuilder $container)
    {
        // TODO: Implement prepend() method.
    }

    public function getAlias()
    {
        return parent::getAlias();
    }
}