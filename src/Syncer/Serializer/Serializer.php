<?php

namespace Syncer\Serializer;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

/**
 * Class Serializer
 * @package Syncer\Serializer
 *
 * @author Matthieu Calie <matthieu@calie.be>
 */
class Serializer implements SerializerInterface
{
    /**
     * @var string
     */
    private $configDir;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Serializer constructor.
     * @param string $configDir
     * @param string $cacheDir
     */
    public function __construct(string $configDir, string $cacheDir)
    {
        $this->configDir = $configDir;
        $this->cacheDir = $cacheDir;
    }

    /**
     *  Set up the serializer
     */
    public function setup()
    {
        $this->serializer = SerializerBuilder::create()
            ->addMetadataDir($this->configDir, 'Syncer\Dto')
            ->setCacheDir($this->cacheDir)
            ->setDebug(true)
            ->build();
    }

    /**
     * @param object|array|scalar $data
     * @param string $format
     * @param SerializationContext $context
     *
     * @return string
     */
    public function serialize($data, $format, SerializationContext $context = null) : string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    /**
     * @param string $data
     * @param string $type
     * @param string $format
     * @param DeserializationContext|null $context
     *
     * @return array|\JMS\Serializer\scalar|object
     */
    public function deserialize($data, $type, $format, DeserializationContext $context = null)
    {
        return $this->serializer->deserialize($data, $type, $format, $context);
    }
}
