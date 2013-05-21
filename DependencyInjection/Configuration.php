<?php
/**
* This file is part of SopinetAboutmagicBundle.
*
* (c) 2013 by Fernando Hidalgo - Sopinet
*/

namespace Sopinet\AboutmagicBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
* Configuration
*
* @codeCoverageIgnore
*/
class Configuration implements ConfigurationInterface
{
    /**
* {@inheritDoc}
*/
    public function getConfigTreeBuilder()
    {
        return $this->buildConfigTree();
    }

    private function buildConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sopinet_aboutmagic');

        $rootNode
            ->children()
                ->scalarNode('key')
                    ->defaultValue('')
                ->end()
			->end();

        return $treeBuilder;
    }
}