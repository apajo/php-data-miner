<?php

namespace PhpDataMinerStorage\Model;


use PhpDataMinerStorage\Model\Discriminator\Discriminator;
use PhpDataMinerStorage\Model\Discriminator\DiscriminatorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Description of Entry
 *
 * @author Andres Pajo
 */
class Entry implements EntryInterface
{
    /**
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * @var Model|null
     */
    protected ?Model $model = null;

    /**
     * @var string|null
     */
    protected ?string $discriminator = null;

    /**
     * @var Collection|PropertyInterface[]
     */
    protected Collection $properties;


    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id = null)
    {
        $this->id = $id;
    }

    public function getModel(): ?ModelInterface
    {
        return $this->model;
    }

    public function setModel(?ModelInterface $model): void
    {
        $this->model = $model;
    }

    public function getDiscriminator(): ?DiscriminatorInterface
    {
        return new Discriminator($this->discriminator);
    }

    public function setDiscriminator(?DiscriminatorInterface $discriminator = null): void
    {
        $this->discriminator = $discriminator ? $discriminator->getString() : null;
    }

    /**
     * @return PropertyInterface
     */
    public function getProperty(string $property): ?PropertyInterface
    {
        foreach ($this->getProperties() as $prop) {
            if ($prop->getName() !== $property) {
                continue;
            }

            return $prop;
        }

        return null;
    }

    /**
     * @return PropertyInterface[]
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }
    
    public function addProperty(PropertyInterface $property)
    {
        $this->properties->add($property);
        $property->setEntry($this);
    }
    
    public function removeProperty(PropertyInterface $property)
    {
        $this->properties->removeElement($property);
        $property->setEntry(null);
    }
}
