<?php

namespace PhpDataMinerTests\Model;


use PhpDataMiner\Model\Annotation\Property;
use PhpDataMiner\Model\Annotation\Model;

/**
 * @Model(Model::IMPLICIT)
 */
class Ancestor
{
    /**
     * @var int|null
     * @Property()
     */
    public ?int $age = null;

    /**
     * @var float|null
     * @Property()
     */
    public ?float $number = null;

    /**
     * @var string|null
     */
    public ?string $name = null;

    /**
     * @var string|null
     * @Property()
     */
    public ?string $alias = null;

    /**
     * @var Child[]|null
     */
    public array $children;

    /**
     * @var int
     * @Property()
     */
    public ?int $id = null;

    /**
     * @var \DateTime|null
     * @Property()
     */
    public ?\DateTime $date = null;

    function __construct ()
    {
        $this->id = rand(10000, 99999);
        $this->children = [];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static function createModel (): Ancestor
    {
        $entity = new Ancestor();

        $entity->age = rand(55, 60);
        $entity->number = 360.00;
        $entity->alias = 'EE137700771004808317';
        $entity->name = 'Eelika Puunurm';
        $entity->date = (new \DateTime())->setTimestamp(rand(1641077089,1672440289));

        for ($i = 1; $i < 1; $i++) {
            $child = new Child();

            $child->age = rand(10, 99);
            $child->name = rand(1000, 9999);
            $child->something = 'Never seen';

            $entity->children = [...$entity->children, ...[
                $child
            ]] ;
        }

        return $entity;
    }
}
