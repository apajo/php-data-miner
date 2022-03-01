<?php

namespace PhpDataMiner;

use PhpDataMinerKernel\KernelInterface;

class DataMiner
{
    public static function create (KernelInterface $kernel, $entity, array $options = []): Miner
    {
        $miner = new Miner($kernel, $entity, $options);

        return $miner;
    }
}
