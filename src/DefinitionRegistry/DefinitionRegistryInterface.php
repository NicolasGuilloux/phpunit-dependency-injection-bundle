<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry;

use Symfony\Component\DependencyInjection\Definition;

interface DefinitionRegistryInterface
{
    public function has(string $serviceId): bool;
    public function get(string $serviceId): ?Definition;
}