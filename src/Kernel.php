<?php

declare(strict_types=1);

namespace Matth\Synchronizer;

use Matth\Synchronizer\Configuration\SyncerConfiguration;
use Matth\Synchronizer\DependencyInjection\CompilerPass\CollectCommandsToApplicationCompilerPass;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Yaml\Yaml;


final class Kernel extends BaseKernel
{

    public function registerBundles()
    {
        return [];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/Resources/config/services.yml');
    }

    protected function build(ContainerBuilder $container)
    {
        $this->handleConfiguration($container);
        $container->addCompilerPass(new CollectCommandsToApplicationCompilerPass);
    }

    private function handleConfiguration(ContainerBuilder $container): void
    {
        $processor = new Processor();
        $syncerConfiguration = new SyncerConfiguration();
        $processedConfiguration = $processor->processConfiguration(
            $syncerConfiguration,
            Yaml::parse(file_get_contents(__DIR__ . '/../config/parameters.yml'))
        );

        $container->setParameter('toggl.api_key', $processedConfiguration['toggl']['api_key']);
        $container->setParameter('toggl.base_uri', $processedConfiguration['toggl']['base_uri']);
        $container->setParameter('toggl.reports_base_uri', $processedConfiguration['toggl']['reports_base_uri']);

        $container->setParameter('invoice_ninja.api_key', $processedConfiguration['invoice_ninja']['api_key']);
        $container->setParameter('invoice_ninja.base_uri', $processedConfiguration['invoice_ninja']['base_uri']);

        $container->setParameter('clients', $processedConfiguration['clients']);
        $container->setParameter('projects', $processedConfiguration['projects']);
    }
}
