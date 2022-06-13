<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\Tests;

use NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry\DefinitionRegistryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @covers \NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry\DefinitionRegistry
 */
final class DefinitionRegistryTest extends KernelTestCase
{
    #[Required]
    public LoggerInterface $attributeLogger;

    /** @required */
    public LoggerInterface $annotationLogger;

    /** @required */
    public function autowiringMethod(LoggerInterface $logger): void
    {
        // Empty
    }

    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    public function testRegistryDeclaration(): void
    {
        $definitionRegistry = self::getContainer()->get(DefinitionRegistryInterface::class);
        self::assertTrue($definitionRegistry->has(self::class));

        $definition = $definitionRegistry->get(self::class);
        self::assertInstanceOf(Definition::class, $definition);
        self::assertSame(self::class, $definition->getClass());

        $methodCall = $definition->getMethodCalls()[0] ?? null;
        self::assertContains('autowiringMethod', $methodCall);

        $properties = $definition->getProperties();
        self::assertArrayHasKey('annotationLogger', $properties);
    }

    /** @requires PHP >= 8.0.0 */
    public function testRegistryDeclarationForAttributeWithPHPAbove8(): void
    {
        $definitionRegistry = self::getContainer()->get(DefinitionRegistryInterface::class);
        self::assertTrue($definitionRegistry->has(self::class));

        $definition = $definitionRegistry->get(self::class);
        self::assertInstanceOf(Definition::class, $definition);
        self::assertSame(self::class, $definition->getClass());

        $properties = $definition->getProperties();
        self::assertArrayHasKey('attributeLogger', $properties);
    }

    /** @requires PHP < 8.0.0 */
    public function testRegistryDeclarationForAttributeWithPHPUnder8(): void
    {
        $definitionRegistry = self::getContainer()->get(DefinitionRegistryInterface::class);
        self::assertTrue($definitionRegistry->has(self::class));

        $definition = $definitionRegistry->get(self::class);
        self::assertInstanceOf(Definition::class, $definition);
        self::assertSame(self::class, $definition->getClass());

        $properties = $definition->getProperties();
        self::assertArrayNotHasKey('attributeLogger', $properties);
    }
}