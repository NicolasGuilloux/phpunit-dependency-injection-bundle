<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class DefinitionRegistry implements DefinitionRegistryInterface
{
    public const SERIALIZATION_ALLOWED_CLASSES = [Definition::class, Reference::class];

    /** @var array<string, Definition> */
    protected $definitions = [];

    public function setDefinitions(array $serializedDefinitions): void
    {
        $options = ['allowed_classes' => self::SERIALIZATION_ALLOWED_CLASSES];

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