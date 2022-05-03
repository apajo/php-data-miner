<?php

namespace PhpDataMiner;

use PhpDataMiner\Kernel\KernelInterface;
use PhpDataMiner\Model\Property\Provider;

class DataMiner
{
    public static function create ($entity, Provider $provider, array $filters = [], array $options = []): Miner
    {
        $miner = new Miner($entity, $provider, $filters, $options);

        return $miner;
    }
}
