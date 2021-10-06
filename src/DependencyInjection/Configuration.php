<?php

namespace Bechir\ViteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidDefinitionException;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('bechir_vite');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->validate()
                ->ifTrue(function (array $v): bool {
                    return false === $v['output_path'] && empty($v['builds']);
                })
                ->thenInvalid('Default build can only be disabled if multiple entry points are defined.')
            ->end()
            ->children()
                ->scalarNode('output_path')
                    ->isRequired()
                ->end()
                ->enumNode('crossorigin')
                    ->defaultFalse()
                    ->values([false, 'anonymous', 'use-credentials'])
                ->end()
                ->booleanNode('preload')
                    ->defaultFalse()
                ->end()
                ->booleanNode('cache')
                    ->defaultFalse()
                ->end()
                ->booleanNode('strict_mode')
                    ->defaultTrue()
                ->end()
                ->arrayNode('builds')
                    ->useAttributeAsKey('name')
                    ->normalizeKeys(false)
                    ->scalarPrototype()
                        ->validate()
                        ->always(function ($values) {
                            if (isset($values['_default'])) {
                                throw new InvalidDefinitionException("Key '_default' can't be used as build name.");
                            }

                            return $values;
                        })
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('script_attributes')
                    ->useAttributeAsKey('name')
                    ->normalizeKeys(false)
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('link_attributes')
                    ->useAttributeAsKey('name')
                    ->normalizeKeys(false)
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
