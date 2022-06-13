<?php

declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Configuration implements ConfigurationInterface
{
    public const CONFIG_NODE = 'phpunit_dependency_injection';

    public static function get(string $path, ParameterBagInterface $parameterBag)
    {
        return $parameterBag->get(self::CONFIG_NODE . '.' . $path);
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::CONFIG_NODE);
        $rootNode = \method_exists(TreeBuilder::class, 'getRootNode')
            ? $treeBuilder->getRootNode()
            : $treeBuilder->root(self::CONFIG_NODE);

        $this->buildConfig($rootNode->children());

        return $treeBuilder;
    }

    protected function buildConfig(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder->scalarNode('tests_namespace');
    }
}
