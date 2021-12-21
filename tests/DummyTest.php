<?php

declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\Tests;

use NicolasGuilloux\PhpunitDependencyInjectionBundle\DependencyInjection\Configuration;
use NicolasGuilloux\PhpunitDependencyInjectionBundle\PhpunitDependencyInjectionBundle;
use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @covers \NicolasGuilloux\PhpunitDependencyInjectionBundle\PhpunitDependencyInjectionBundle
 */
class DummyTest extends TestCase
{
    public function testInstantiateBundle(): void
    {
        $bundle = new PhpunitDependencyInjectionBundle();

        self::assertInstanceOf(PhpunitDependencyInjectionBundle::class, $bundle);
    }

    /** @TestConfig("container") */
    public function testCanInstantiateContainer(): void
    {
        /** @var ParameterBagInterface $parameterBag */
        $parameterBag = $this->getService(ParameterBagInterface::class);

        self::assertInstanceOf(ParameterBagInterface::class, $parameterBag);
        self::assertNotNull($parameterBag->get(Configuration::CONFIG_NODE));
    }
}
