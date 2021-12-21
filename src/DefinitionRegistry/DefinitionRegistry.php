<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry;

use NicolasGuilloux\PhpunitDependencyInjectionBundle\Model\DefinitionExtraction;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class DefinitionRegistry implements DefinitionRegistryInterface
{
    /** @var array<string, DefinitionExtraction> */
    protected $definitions = [];

    public function setDefinitions(array $serializedDefinitions): void
    {
        foreach ($serializedDefinitions as $serviceId => $serializedDefinition) {
            $this->definitions[$serviceId] = unserialize(
                $serializedDefinition,
                [
                    'allowed_classes' => [
                        Definition::class,
                        Reference::class,
                    ]
                ]
            );
        }
    }

    public function has(string $serviceId): bool
    {
        return array_key_exists($serviceId, $this->definitions);
    }

    public function get(string $serviceId): ?Definition
    {
        return $this->definitions[$serviceId] ?? null;
    }
}