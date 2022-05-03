<?php

namespace PhpDataMiner\Storage\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PhpDataMiner\Storage\Model\Discriminator\Discriminator;
use PhpDataMiner\Storage\Model\Discriminator\DiscriminatorInterface;

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
    public function getProperty(string $property, bool $create = false): ?PropertyInterface
    {
        foreach ($this->getProperties() as $prop) {
            if ($prop->getName() !== $property) {
                continue;
            }

            return $prop;
        }

        if (!$create) {
            return null;
        }

        $modelProp = $this->getModel()->getProperty($property, true);
        $modelProp->setName($property);

        $new = self::createProperty();
        $new->setModelProperty($modelProp);
        $this->addProperty($new);

        return $new;
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

    public static function createProperty(): PropertyInterface
    {
        return new Property();
    }
}
