<?php

declare(strict_types=1);

namespace Matth\Synchronizer\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

final class ClearCacheCommand extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct('cache:clear');
        $this->container = $container;
    }

    protected function configure()
    {
        $this->setDescription('Remove the cache folders for the current environment');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $filesystem = new Filesystem();

        $realCacheDir = $this->container->getParameter('kernel.cache_dir');
        $filesystem->remove($realCacheDir);

        $io->success(sprintf(
            'Removed the cache for environment: %s',
            $this->container->getParameter('kernel.environment'))
        );

        return 0;
    }
}
