<?php

namespace Drosalys\PdfBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('drosalys_pdf');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('asset_output_path')
                    ->defaultNull()
                ->end()
                ->scalarNode('chrome_bin')
                    ->defaultNull()
                ->end()
                ->scalarNode('pdfTmpDir')
                    ->defaultNull()
                ->end()
                ->scalarNode('templates_dir')
                ->end()
            ->end();

        return $treeBuilder;
    }
}