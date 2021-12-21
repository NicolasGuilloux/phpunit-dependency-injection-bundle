<?php

declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\DependencyInjection;

use RichCongress\BundleToolbox\Configuration\AbstractConfiguration;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class Configuration extends AbstractConfiguration
{
    public const CONFIG_NODE = 'phpunit_dependency_injection';

    protected function buildConfiguration(ArrayNodeDefinition $rootNode): void
    {
        $children = $rootNode->children();
        $this->addConfig($children);
    }

    protected function addConfig(NodeBuilder $nodeBuilder): void
    {
        // Do something
    }
}
