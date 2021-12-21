<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\DependencyInjection\CompilerPass;

use NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry\DefinitionRegistry;
use RichCongress\BundleToolbox\Configuration\AbstractCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class DefinitionRegistryCompilerPass extends AbstractCompilerPass
{
    public const TYPE = PassConfig::TYPE_AFTER_REMOVING;
    public const TAG = 'phpunit_test';

    public function process(ContainerBuilder $container): void
    {
        $serviceIds = array_keys($container->findTaggedServiceIds(self::TAG));
        $definition = $container->getDefinition(DefinitionRegistry::class);
        $testDefinitions = [];

        foreach ($serviceIds as $serviceId) {
            $testDefinition = $container->getDefinition($serviceId);
            $testDefinitions[$serviceId] = serialize($testDefinition);
        }

        $definition->addMethodCall('setDefinitions', [$testDefinitions]);
    }
}