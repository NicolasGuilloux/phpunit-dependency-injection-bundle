<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry;

use Symfony\Component\DependencyInjection\Definition;

class DefinitionRegistry implements DefinitionRegistryInterface
{
    /** @var array<string, Definition> */
    protected $definitions = [];

    public function setDefinitions(array $serializedDefinitions): void
    {
        $options = ['allowed_classes' => true];

        foreach ($serializedDefinitions as $class => $serializedDefinition) {
            $this->definitions[$class] = unserialize($serializedDefinition, $options);
        }
    }

    public function has(string $class): bool
    {
        return array_key_exists($class, $this->definitions);
    }

    public function get(string $class): ?Definition
    {
        return $this->definitions[$class] ?? null;
    }
}