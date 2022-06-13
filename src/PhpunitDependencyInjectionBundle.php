<?php

declare(strict_types=1);

namespace NicolasGuilloux\PhpunitDependencyInjectionBundle;

use NicolasGuilloux\PhpunitDependencyInjectionBundle\DependencyInjection\CompilerPass\DefinitionRegistryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PhpunitDependencyInjectionBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new DefinitionRegistryCompilerPass(), DefinitionRegistryCompilerPass::TYPE);
    }
}
