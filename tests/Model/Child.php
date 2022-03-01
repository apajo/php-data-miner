<?php

namespace PhpDataMinerTests\Model;


use PhpDataMiner\Model\Annotation\Ignore;
use PhpDataMiner\Model\Annotation\Model;
use PhpDataMiner\Model\Annotation\Property;

/**
 * @Model()
 */
class Child
{
    /**
     * @var int|null
     * @Property()
     */
    public ?int $age;

    /**
     * @var string|null
     * @Property()
     */
    public ?string $name;

    /**
     * @var string|null
     */
    public ?string $gender;


    /**
     * @var string|null
     * @Ignore()
     */
    public ?string $something;
}
