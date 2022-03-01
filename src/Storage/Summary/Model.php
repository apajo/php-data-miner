<?php
namespace PhpDataMinerStorage\Summary;

use PhpDataMinerModel\Property\PropertyInterface;
use PhpDataMinerStorage\Model\Discriminator\DiscriminatorInterface;
use PhpDataMinerStorage\Model\Entry;
use PhpDataMinerStorage\Model\LabelInterface;
use PhpDataMinerStorage\Model\ModelInterface;
use PhpDataMinerStorage\Model\PropertyInterface as StoragePropertyInterface;
use PhpDataMinerStorage\StorageInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Model
 *
 * @author Andres Pajo
 */
class Model
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function build (ModelInterface $model, PropertyInterface $property = null, DiscriminatorInterface $discriminator = null)
    {
        $props = $model->getProperties();

        $objects = new ArrayCollection();
        foreach ($model->getEntries() as $entry) {
            if  ($objects->contains($entry->getDiscriminator())){
                continue;
            }

            $objects->add($entry->getDiscriminator());
        }

        $summary = new Summary();

        $summary->setColumns($props->map(function (StoragePropertyInterface  $a) {
            return $a->getName();
        })->toArray());

        foreach ($objects as $obj) {
            $row = [];

            $objectEntries = $model->getEntries()->filter(function (Entry $a) use ($obj) {
                return (!$obj || $obj === $a->getDiscriminator());
            });

            /** @var ModelInterface $entry */
            $entry = !$objectEntries->isEmpty() ? $objectEntries->first() : null;

            foreach ($props as $prop) {
                $propEntries = $objectEntries->filter(function (Entry $a) use ($prop) {
                    return $a->getProperty()->getName() === $prop->getName();
                });

                /** @var StoragePropertyInterface $propEntry */
                $propEntry = !$propEntries->isEmpty() ? $propEntries->first() : null;

                if (!$propEntry) {
                    continue;
                }

                $row[$propEntry->getName()] = implode(', ', $entry->getLabels()->map(function (LabelInterface $a) {
                    return $a->getValue();
                })->toArray());
            }


        }


dump($objects);

        dump($summary);
    }
}
