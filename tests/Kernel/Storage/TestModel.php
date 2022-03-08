<?php

namespace PhpDataMinerTests\Kernel\Storage;

use PhpDataMiner\Storage\Model\Discriminator\Discriminator;
use PhpDataMiner\Storage\Model\Discriminator\DiscriminatorInterface;
use PhpDataMiner\Storage\Model\Model as Base;

/**
 * Description of Miner
 *
 * @author Andres Pajo
 */
class TestModel extends Base
{
    /**
     * @param $value
     * @return DiscriminatorInterface
     */
    public static function createEntryDiscriminator($value): DiscriminatorInterface
    {
        return new Discriminator([
            $value->id,
        ]);
    }
}
