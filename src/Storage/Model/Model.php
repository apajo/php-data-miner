<?php

namespace PhpDataMiner\Storage\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PhpDataMiner\Model\Property\PropertyInterface;
use PhpDataMiner\Storage\Model\Discriminator\Discriminator;
use PhpDataMiner\Storage\Model\Discriminator\DiscriminatorInterface;

/**
 * Description of Model
 *
 * @author Andres Pajo
 */
class Model implements ModelInterface
{
    use ModelHelperTrait;

    public static function createEntryDiscriminator($value): DiscriminatorInterface
    {
        if (is_array($value)) {
            return new Discriminator($value);
        }

        if (is_callable([$value, 'getId'])) {
            return new Discriminator([$value->getId()]);
        }

        $disc = (string)base64_encode(serialize($value));
        return new Discriminator([intval($disc, 64)]);
    }

    /**
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var string|null
     */
    protected ?string $model = null;

    /**
     * @var Collection|LabelInterface[]
     */
    protected ?Collection $labels;

    /**
     * @var Collection|EntryInterface[]
     */
    protected ?Collection $entries;

    /**
     * @var Collection|ModelProperty[]
     */
    protected Collection $propertys;

    function __construct()
    {
        $this->labels = new ArrayCollection();
        $this->entries = new ArrayCollection();
        $this->propertys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEntry(DiscriminatorInterface $discriminator, bool $create = false): ?EntryInterface
    {
        foreach ($this->getEntries() as $entry) {
            if (!$discriminator->matches($entry->getDiscriminator())) {
                continue;
            }

            return $entry;
        }

        if (!$create) {
            return null;
        }

        $entry = self::createEntry();
        $entry->setDiscriminator($discriminator);
        $this->addEntry($entry);

        return $entry;
    }

    /**
     * @return EntryInterface[]|Collection
     */
    public function getEntries(DiscriminatorInterface $discriminator = null): Collection
    {
        if ($discriminator) {
            $this->entries = $this->entries->filter(function (EntryInterface $entry) use ($discriminator) {
                return $entry->getDiscriminator()->matches($discriminator);
            });

            return $this->entries;
        }

        return $this->entries;
    }

    public function addEntry(EntryInterface $entry)
    {
        $this->entries->add($entry);
        $entry->setModel($this);
    }

    public function removeEntry(Entry $entry)
    {
        $this->entries->removeElement($entry);
    }

    /**
     * @return LabelInterface|null
     */
    public function getLabel(PropertyInterface $property, string $value, bool $create = false): ?LabelInterface
    {
        foreach ($this->getLabels() as $label) {
            if ($label->getProperty() !== $property->getPropertyPath()
                || $label->getValue() !== $value) {
                continue;
            }

            return $label;
        }

        if (!$create) {
            return null;
        }

        $label = self::createLabel();
        $label->setValue($value);
        $label->setProperty($property->getPropertyPath());
        $this->addLabel($label);

        return $label;
    }

    /**
     * @return LabelInterface[]|Collection
     */
    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function addLabel(LabelInterface $label)
    {
        $this->labels->add($label);
        $label->setModel($this);
    }

    public function removeLabel(LabelInterface $label)
    {
        $this->labels->removeElement($label);
    }

    /**
     * @return ModelProperty
     */
    public function getProperty(string $name, bool $create = false): ?ModelProperty
    {
        foreach ($this->propertys as $property) {
            if ($property->getName() !== $name) {
                continue;
            }

            return $property;
        }

        if (!$create) {
            return null;
        }

        $property = self::createProperty();
        $property->setName($name);
        $this->addProperty($property);

        return $property;
    }

    /**
     * @return ModelProperty[]|Collection
     */
    public function getPropertys(): Collection
    {
        return $this->propertys;
    }

    public function addProperty(ModelProperty $property): void
    {
        $this->propertys->add($property);
        $property->setModel($this);
    }

    public function removeProperty(ModelProperty $property): void
    {
        $this->propertys->removeElement($property);
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public static function createProperty(): ModelPropertyInterface
    {
        return new ModelProperty();
    }

    public static function createEntry(): EntryInterface
    {
        return new Entry();
    }

    public static function createLabel(): LabelInterface
    {
        return new Label();
    }
}
