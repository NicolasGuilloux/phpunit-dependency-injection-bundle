<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\Tests;

use NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry\DefinitionRegistry;
use NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry\DefinitionRegistryInterface;
use NicolasGuilloux\PhpunitDependencyInjectionBundle\TestCase\AutowiringTestTrait;
use Psr\Log\LoggerInterface;
use RichId\AutoconfigureBundle\Annotation as Service;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @covers \NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry\DefinitionRegistry
 * @covers NicolasGuilloux\PhpunitDependencyInjectionBundle\TestCase\AutowiringTestTrait
 *
 * @Service\Property(property="environment", value="kernel.environment", type="parameter")
 */
final class AutowiringTest extends KernelTestCase
{
    use AutowiringTestTrait;

    #[Required]
    public LoggerInterface $logger;

    public string $environment;

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
        self::assertNull($this->logger ?? null);
        self::assertNull($this->methodLogger);
    }

    public function testInjection(): void
    {
        self::assertNull($this->environment ?? null);
        self::assertNull($this->logger ?? null);
        self::assertNull($this->methodLogger);

        $this->autowire(self::getContainer());

        self::assertSame('test', $this->environment ?? null);
        self::assertInstanceOf(LoggerInterface::class, $this->logger ?? null);
        self::assertInstanceOf(LoggerInterface::class, $this->methodLogger);
    }
}