<?php

namespace PhpDataMiner\Model\Property;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Description of Registry
 *
 * @author Andres Pajo
 */
class Registry
{
    /**
     * @var Collection|PropertyInterface[]
     */
    private Collection $types;

    public function __construct(array $types = [])
    {
        $this->types = new ArrayCollection();

        foreach ($types as $type) {
            $this->addType($type);
        }
    }

    /**
     * @return PropertyInterface[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    /**
     * @return PropertyInterface[]
     */
    public function getDefaultType(): PropertyInterface
    {
        return $this->types[0];
    }

    public function addType(PropertyInterface $type)
    {
        $this->types->add($type);
    }

    public function removeType(PropertyInterface $type)
    {
        $this->types->removeElement($type);
    }
}
