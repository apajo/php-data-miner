<?php

namespace PhpDataMinerTests\Model;


use DateTime;
use PhpDataMiner\Model\Annotation\Property;
use PhpDataMiner\Model\Annotation\Model;

/**
 * @Model(Model::IMPLICIT)
 */
class Invoice
{
    /**
     * @var string|null
     * @Property()
     */
    public ?string $number = null;

//    /**
//     * @var int|null
//     * @Property()
//     */
//    public ?int $vat = null;
//
//    /**
//     * @var float|null
//     * @Property()
//     */
//    public ?float $sum = null;
//
//    /**
//     * @var DateTime|null
//     * @Property()
//     */
//    public ?DateTime $duedate = null;
//
//    /**
//     * @var DateTime|null
//     * @Property()
//     */
//    public ?DateTime $received;
//
//
//    /**
//     * @var string|null
//     * @Property()
//     */
//    public ?string $ponumber = null;
//
//
//    /**
//     * @var string|null
//     * @Property()
//     */
//    public ?string $reference = null;

    public static function createModel (array $data = []): self
    {
        $entity = new Invoice();

        foreach ($data as $key => $d) {
            $value = $d;

            switch ($key) {
                case 'duedate':
                case 'received':
                    $value = DateTime::createFromFormat('d.m.Y', $d);
            }

            if  (!property_exists($entity, $key)) {
                continue;
            }

            $entity->{$key} = $value;
        }

        return $entity;
    }
}
