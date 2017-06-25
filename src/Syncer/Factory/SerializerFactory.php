<?php

namespace Syncer\Factory;

use JMS\Serializer\SerializerBuilder;

/**
 * Class SerializerFactory
 * @package Syncer\Factory
 *
 * @author Matthieu Calie <matthieu@calie.be>
 */
class SerializerFactory
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @var string
     */
    private $configDir;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * SerializerFactory constructor.
     *
     * @param bool $debug
     * @param string $configDir
     * @param string $cacheDir
     */
    public function __construct(bool $debug, string $configDir, string $cacheDir)
    {
        $this->debug = $debug;
        $this->configDir = $configDir;
        $this->cacheDir = $cacheDir;
    }

    /**
     * @return \JMS\Serializer\Serializer
     */
    public function createSerializer()
    {
        return SerializerBuilder::create()
            ->addMetadataDir($this->configDir, 'Syncer\Dto')
            ->setCacheDir($this->cacheDir)
            ->setDebug($this->debug)
            ->build();
    }
}
