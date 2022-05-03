<?php

namespace PhpDataMiner\Storage\Model;


use Doctrine\Common\Collections\Collection;
use PhpDataMiner\Model\Property\PropertyInterface;
use PhpDataMiner\Storage\Model\Discriminator\DiscriminatorInterface;

/**
 * Description of Model
 *
 * @author Andres Pajo
 */
interface ModelInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @return EntryInterface[]|Collection
     */
    public function getEntries(DiscriminatorInterface $discriminator = null): Collection;

    /**
     * @param DiscriminatorInterface $discriminator
     * @return EntryInterface|null
     */
    public function getEntry(DiscriminatorInterface $discriminator, bool $create = false): ?EntryInterface;

    /**
     * @param EntryInterface $entry
     * @return mixed
     */
    public function addEntry(EntryInterface $entry);

    /**
     * @param Entry $entry
     * @return mixed
     */
    public function removeEntry(Entry $entry);

    /**
     * @return LabelInterface|null
     */
    public function getLabel(PropertyInterface $property, string $value, bool $create = false): ?LabelInterface;

    /**
     * @return LabelInterface[]|Collection
     */
    public function getLabels(): Collection;

    /**
     * @param LabelInterface $label
     * @return mixed
     */
    public function addLabel(LabelInterface $label);

    /**
     * @param LabelInterface $label
     * @return mixed
     */
    public function removeLabel(LabelInterface $label);

    /**
     * @return string
     */
    public function getModel(): string;

    /**
     * @param string $model
     */
    public function setModel(string $model): void;


    /**
     * @return ModelProperty
     */
    public function getProperty(string $name, bool $create = false): ?ModelProperty;

    /**
     * @return ModelProperty[]|Collection
     */
    public function getPropertys(): Collection;

    public function addProperty(ModelProperty $property): void;

    public function removeProperty(ModelProperty $property): void;

    public function resolveSamples(PropertyInterface $property = null, EntryInterface $entry): array;

    public function resolveLabels(PropertyInterface $property = null): Collection;

    public static function createProperty(): ModelPropertyInterface;

    public static function createEntry(): EntryInterface;

    public static function createLabel(): LabelInterface;
}
