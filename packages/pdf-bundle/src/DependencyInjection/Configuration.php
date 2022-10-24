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
                ->end()
                ->scalarNode('chrome_bin')
                    ->isRequired()
                ->end()
                ->scalarNode('pdfTmpDir')
                    ->isRequired()
                ->end()
                ->scalarNode('default_templates_dir')
                    ->defaultNull()
                ->end()
                ->scalarNode('default_cache_dir')
                    ->defaultNull()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
