<?php

namespace PhpDataMiner;

use PhpDataMiner\Kernel\KernelInterface;

class DataMiner
{
    public static function create (KernelInterface $kernel, $entity, array $options = []): Miner
    {
        $miner = new Miner($kernel, $entity, $options);

        return $miner;
    }
}
