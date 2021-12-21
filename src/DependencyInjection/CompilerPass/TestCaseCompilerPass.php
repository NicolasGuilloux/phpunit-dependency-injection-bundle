<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\DependencyInjection\CompilerPass;

use HaydenPierce\ClassFinder\ClassFinder;
use NicolasGuilloux\PhpunitDependencyInjectionBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\Test;
use RichCongress\BundleToolbox\Configuration\AbstractCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class TestCaseCompilerPass extends AbstractCompilerPass
{
    public function process(ContainerBuilder $container): void
    {
        $testClasses = self::getTestClasses($container);

        foreach ($testClasses as $class) {
            $definition = new Definition($class);
            $definition->setPublic(true);
            $definition->setAutoconfigured(true);
            $definition->setAutowired(true);
            $definition->addTag(DefinitionRegistryCompilerPass::TAG);
            $definition->setSynthetic(true);

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