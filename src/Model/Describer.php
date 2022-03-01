<?php

namespace PhpDataMinerModel;

use ArrayObject;

/**
 * Description of Describer
 *
 * @author Andres Pajo
 */
class Describer
{
    /**
     * @var ArrayObject|null
     */
    public ?ArrayObject $iterator;

    /**
     * @var string|null
     */
    public ?string $strategy;

    /**
     * @var string
     */
    public ?string $storageModel;


    /**
     * @var string|null
     */
    public ?string $model;

    public $entity;
}
