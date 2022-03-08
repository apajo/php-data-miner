<?php

namespace PhpDataMiner;

use PhpDataMiner\Kernel\KernelInterface;

class DataMiner
{
    public static function create ($entity, array $options = []): Miner
    {
        $miner = new Miner($entity, $options);

        return $miner;
    }
}
