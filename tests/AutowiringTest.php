<?php declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\Tests;

use NicolasGuilloux\PhpunitDependencyInjectionBundle\TestCase\AutowiringTestTrait;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @covers \NicolasGuilloux\PhpunitDependencyInjectionBundle\DefinitionRegistry\DefinitionRegistry
 * @covers NicolasGuilloux\PhpunitDependencyInjectionBundle\TestCase\AutowiringTestTrait
 */
final class AutowiringTest extends KernelTestCase
{
    use AutowiringTestTrait;

    #[Required]
    public LoggerInterface $logger;

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

    public function testLoggerNotInjected(): void
    {
        self::assertNull($this->logger ?? null);
        self::assertNull($this->methodLogger);
    }

    public function testMethodInjection(): void
    {
        $this->autowire(self::getContainer());

        self::assertInstanceOf(LoggerInterface::class, $this->methodLogger);
    }

    public function testPropertyInjection(): void
    {
        $this->autowire(self::getContainer());

        self::assertInstanceOf(LoggerInterface::class, $this->logger ?? null);
    }
}