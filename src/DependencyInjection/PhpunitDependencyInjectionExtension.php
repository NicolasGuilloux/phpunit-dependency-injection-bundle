<?php

declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\DependencyInjection;

use HaydenPierce\ClassFinder\ClassFinder;
use NicolasGuilloux\PhpunitDependencyInjectionBundle\DependencyInjection\CompilerPass\DefinitionRegistryCompilerPass;
use PHPUnit\Framework\Test;
use RichCongress\BundleToolbox\Configuration\AbstractExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PhpunitDependencyInjectionExtension extends AbstractExtension
{
    /** @param array<string, mixed> $configs */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->parseConfiguration(
            $container,
            new Configuration(),
            $configs
        );

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources'));
        $loader->load('services.xml');

        $container->registerForAutoconfiguration(Test::class)->addTag(DefinitionRegistryCompilerPass::TAG);
        $this->registerTestCases($container);
    }

    private function registerTestCases(ContainerBuilder $container): void
    {
        $testClasses = self::getTestClasses($container);

        foreach ($testClasses as $class) {
            if ($container->has($class)) {
                continue;
            }

            $definition = new Definition($class);
            $definition->setPublic(true);
            $definition->setAutoconfigured(true);
            $definition->setAutowired(true);
            $definition->addTag(DefinitionRegistryCompilerPass::TAG);

            $container->setDefinition($class, $definition);
        }
    }

    /** @return string[]|Test[] */
    private static function getTestClasses(ContainerBuilder $container): array
    {
        $testCasesNamespace = Configuration::get('tests_namespace', $container->getParameterBag());

        try {
            $classes = ClassFinder::getClassesInNamespace($testCasesNamespace, ClassFinder::RECURSIVE_MODE);
        } catch (\Exception $e) {
            return [];
        }

        return array_filter(
            $classes,
            static function (string $class): bool {
                return is_a($class, Test::class, true);
            }
        );
    }
}
