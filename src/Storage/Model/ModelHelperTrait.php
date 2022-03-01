<?php

namespace DataMiner\Storage\Model;


use DataMiner\Model\Property\PropertyInterface;
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
            if ($property && $entry->getProperty()->getName() !== $property->getPropertyPath()){
                continue;
            }

            if ($result->contains($entry->getLabel())){
                continue;
            }

            $result->add($entry->getLabel());
        }

        return $result;
    }

    /**
     * @param PropertyInterface|null $property
     * @return Collection
     */
    public function resolveSamples (PropertyInterface $property = null, EntryInterface $entry): array
    {
        $samples = [];
        $discriminator = $entry->getDiscriminator();

        foreach ($this->getEntries($discriminator) as $entry) {
            if ($entry->getProperty()->getName() !== $property->getPropertyPath()) {
                continue;
            }

            $label = $entry->getLabel();

            if (!isset($samples[$label->getValue()])) {
                $samples[$label->getValue()] = [];
            }

            $samples[$label->getValue()][] = $label->getValue();
        }

        return $samples;
    }
}
