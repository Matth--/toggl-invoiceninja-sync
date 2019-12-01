<?php

declare(strict_types=1);

namespace Matth\Synchronizer\DependencyInjection\CompilerPass;

use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class CollectCommandsToApplicationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $applicationDefinition = $container->getDefinition(Application::class);

        foreach ($container->findTaggedServiceIds('console.command') as $id => $tags) {
            $applicationDefinition->addMethodCall('add', [new Reference($id)]);
        }
    }
}
