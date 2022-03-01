<?php

namespace PhpDataMiner\Storage\Builder;


use PhpDataMiner\Storage\StorageInterface;

/**
 * Description of Builder
 *
 * @author Andres Pajo
 */
class Builder
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    function __construct (StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    function rebuild ($entity, array $objectVector = null)
    {

    }
}
