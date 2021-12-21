<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\TestCase;

use NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry\DefinitionRegistryInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

trait AutowiringTestTrait
{
    /** @var ContainerInterface */
    private $autowiringContainer;

    protected function autowire(ContainerInterface $container): void
    {
        /** @var DefinitionRegistryInterface $definitionRegistry */
        $definitionRegistry = $container->get(DefinitionRegistryInterface::class);
        $definition = $definitionRegistry->get(static::class);

        if ($definition === null) {
            return;
        }

        $this->autowiringContainer = $container;
        $this->resolveMethods($definition->getMethodCalls());
        $this->resolveProperties($definition->getProperties());
    }

    private function resolveMethods(array $methodCalls): void
    {
        foreach ($methodCalls as $methodCall) {
            [$method, $arguments] = $methodCall;
            $callback = [$this, $method];
            $arguments = array_map([$this, 'resolveValue'], $arguments);

            $callback(...$arguments);
        }
    }

    private function resolveProperties(array $properties): void
    {
        foreach ($properties as $key => $value) {
            $this->$key = $this->resolveValue($value);
        }
    }

    private function resolveValue($argument)
    {
        if ($argument instanceof Reference) {
            $serviceId = (string) $argument;
            return $this->autowiringContainer->get($serviceId);
        }

        return $argument;
    }
}