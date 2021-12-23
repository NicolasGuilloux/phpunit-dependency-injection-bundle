<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\TestCase;

use NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry\DefinitionRegistryInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

trait AutowiringTestTrait
{
    protected function autowire(ContainerInterface $container): void
    {
        /** @var DefinitionRegistryInterface $definitionRegistry */
        $definitionRegistry = $container->get(DefinitionRegistryInterface::class);
        $definition = $definitionRegistry->get(static::class);

        if ($definition === null) {
            return;
        }

        $this->resolveMethods($container, $definition->getMethodCalls());
        $this->resolveProperties($container, $definition->getProperties());
    }

    private function resolveMethods(ContainerInterface $container, array $methodCalls): void
    {
        foreach ($methodCalls as $methodCall) {
            [$method, $arguments] = $methodCall;
            $callback = [$this, $method];
            $arguments = array_map(
                function ($argument) use ($container) {
                    return $this->resolveValue($container, $argument);
                },
                $arguments
            );

            $callback(...$arguments);
        }
    }

    private function resolveProperties(ContainerInterface $container, array $properties): void
    {
        foreach ($properties as $key => $value) {
            $this->$key = $this->resolveValue($container, $value);
        }
    }

    private function resolveValue(ContainerInterface $container, $argument)
    {
        if ($argument instanceof Reference) {
            return $container->get((string) $argument);
        }

        return $argument;
    }
}