<?php

declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle\Tests\Resources\Kernel;

use RichCongress\WebTestBundle\Kernel\DefaultTestKernel;

class TestKernel extends DefaultTestKernel
{
    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function getConfigurationDir(): ?string
    {
        return __DIR__ . '/config';
    }
}
