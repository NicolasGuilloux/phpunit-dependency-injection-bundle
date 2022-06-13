<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\Tests;

use NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry\DefinitionRegistry;
use NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry\DefinitionRegistryInterface;
use NicolasGuilloux\PhpunitDependencyInjectionBundle\TestCase\AutowiringTestTrait;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @covers \NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry\DefinitionRegistry
 * @covers \NicolasGuilloux\PhpunitDependencyInjectionBundle\TestCase\AutowiringTestTrait
 */
final class AutowiringTest extends KernelTestCase
{
    use AutowiringTestTrait;

    #[Required]
    public LoggerInterface $attributeLogger;

    /** @required */
    public LoggerInterface $annotationLogger;

    /** @var LoggerInterface */
    private $methodLogger;

    /** @required */
    public function autowiringMethod(LoggerInterface $logger): void
    {
        $this->methodLogger = $logger;
    }

    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        unset($this->logger);
        $this->methodLogger = null;
    }

    public function testNoLoudFailIfNotFound(): void
    {
        $container = new Container();
        $container->set(DefinitionRegistryInterface::class, new DefinitionRegistry());

        $this->autowire($container);
        self::assertNull($this->attributeLogger ?? null);
        self::assertNull($this->methodLogger);
    }

    /** @requires PHP 8 */
    public function testInjectionWithAttributeForAbovePHP8(): void
    {
        self::assertNull($this->attributeLogger ?? null);

        $this->autowire(self::getContainer());

        self::assertInstanceOf(LoggerInterface::class, $this->attributeLogger ?? null);
    }

    /** @requires PHP 7 */
    public function testInjectionWithAttributeForUnderPHP8(): void
    {
        self::assertNull($this->attributeLogger ?? null);

        $this->autowire(self::getContainer());

        self::assertNull($this->attributeLogger ?? null);
    }

    public function testInjection(): void
    {
        self::assertNull($this->annotationLogger ?? null);
        self::assertNull($this->methodLogger);

        $this->autowire(self::getContainer());

        self::assertInstanceOf(LoggerInterface::class, $this->annotationLogger ?? null);
        self::assertInstanceOf(LoggerInterface::class, $this->methodLogger);
    }
}