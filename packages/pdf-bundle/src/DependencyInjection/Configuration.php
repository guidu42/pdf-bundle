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
                ->scalarNode('save_dir')
                    ->defaultNull()
                ->end()
                ->scalarNode('chrome_bin')
                    ->defaultNull()
                ->end()
            ->end();

        return $treeBuilder;
    }
}