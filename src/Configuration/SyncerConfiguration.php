<?php

declare(strict_types=1);

namespace Matth\Synchronizer\Configuration;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class SyncerConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('syncer');
        $rootNode = $treeBuilder->getRootNode();

        $this->addTogglSection($rootNode);
        $this->addInvoiceNinjaSection($rootNode);
        $this->addMappingSection($rootNode);

        return $treeBuilder;
    }

    private function addTogglSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('toggl')
                    ->children()
                        ->scalarNode('api_key')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('base_uri')
                            ->defaultValue('https://www.toggl.com/api/')
                        ->end()
                        ->scalarNode('reports_base_uri')
                            ->defaultValue('https://www.toggl.com/reports/api/')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addInvoiceNinjaSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('invoice_ninja')
                    ->children()
                        ->scalarNode('api_key')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('base_uri')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->booleanNode('auto_create_clients_and_projects')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('use_project_as_client_name')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addMappingSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('clients')
                    ->useAttributeAsKey('toggl_id')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('projects')
                    ->useAttributeAsKey('toggl_id')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;
    }
}
