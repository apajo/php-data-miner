<?php

namespace PhpDataMiner\Storage\Model;


use PhpDataMiner\Model\Property\PropertyInterface;
use PhpDataMiner\Storage\Model\Discriminator\DiscriminatorInterface;
use Doctrine\Common\Collections\Collection;

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
    public function getEntry(DiscriminatorInterface $discriminator): ?EntryInterface;

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
    public function getLabel(PropertyInterface $property, string $value): ?LabelInterface;

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

    public function resolveSamples (PropertyInterface $property = null, EntryInterface $entry): array;
    public function resolveLabels (PropertyInterface $property = null): Collection;
}
