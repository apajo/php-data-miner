<?php

namespace PhpDataMiner\Storage\Model;


use PhpDataMiner\Model\Property\PropertyInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * Description of Model
 *
 * @author Andres Pajo
 */
trait ModelHelperTrait
{
    /**
     * @param PropertyInterface|null $property
     * @return LabelInterface[]|Collection
     */
    public function resolveLabels (PropertyInterface $property = null): Collection
    {
        $result = new ArrayCollection();

        foreach ($this->getEntries() as $entry) {
            $prop = $entry->getProperty();

            if (!$prop) {
                continue;
            }

            if ($result->contains($prop->getLabel())){
                continue;
            }

            $result->add($prop->getLabel());
        }

        return $result;
    }

    /**
     * @param PropertyInterface|null $property
     * @return Collection
     */
    public function resolveSamples (PropertyInterface $property = null, EntryInterface $target = null): array
    {
        $samples = [];

        foreach ($this->getEntries() as $entry) {
            if ($target && $target->getId() !== $entry->getId()) {
                continue;
            }

            $prop = $entry->getProperty($property->getPropertyPath());

            if (!$prop) {
                continue;
            }

            $label = $prop->getLabel();

            if (!isset($samples[$label->getValue()])) {
                $samples[$label->getValue()] = [];
            }

            $samples[$label->getValue()][] = $label->getValue();
        }

        return $samples;
    }
}
