<?php

namespace DataMiner\Storage\Model;

use DataMiner\Model\Property\PropertyInterface;
use DataMiner\Storage\Model\Discriminator\Discriminator;
use DataMiner\Storage\Model\Discriminator\DiscriminatorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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

        $disc = (string)base64_encode($value);
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

    function __construct()
    {
        $this->labels = new ArrayCollection();
        $this->entries = new ArrayCollection();
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

    public function getEntry(DiscriminatorInterface $discriminator): ?EntryInterface
    {
        foreach ($this->getEntries() as $entry) {
            if (!$discriminator->matches($entry->getDiscriminator())) {
                continue;
            }

            return $entry;
        }

        return null;
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
    public function getLabel(PropertyInterface $property, string $value): ?LabelInterface
    {
        foreach ($this->getLabels() as $label) {
            if ($label->getProperty() !== $property->getPropertyPath()
                    || $label->getValue() !== $value) {
                continue;
            }

            return $label;
        }

        return null;
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

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }
}
