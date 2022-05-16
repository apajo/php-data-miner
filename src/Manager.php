<?php

namespace PhpDataMiner;

use PhpDataMiner\Model\Property\Provider;
use PhpDataMiner\Storage\StorageInterface;

class Manager
{
    public static function create ($entity, Provider $provider, StorageInterface $storage, array $filters = [], array $options = []): Miner
    {
        $miner = new Miner($entity, $provider, $storage, $filters, $options);

        return $miner;
    }
}
