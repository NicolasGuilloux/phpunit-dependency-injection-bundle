<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry;

use Symfony\Component\DependencyInjection\Definition;

interface DefinitionRegistryInterface
{
    public function has(string $class): bool;
    public function get(string $class): ?Definition;
}