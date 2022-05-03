<?php

namespace PhpDataMiner\Storage\Model;

use Doctrine\Common\Collections\Collection;

/**
 * Interface PropertyInterface
 * @package PhpDataMiner\Storage\Model
 */
interface PropertyInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    public function getModelProperty(): ?ModelProperty;

    public function setModelProperty(?ModelProperty $modelProperty): void;

    /**
     * @return EntryInterface|null
     */
    public function getEntry(): ?EntryInterface;

    /**
     * @param EntryInterface|null $entry
     * @return mixed
     */
    public function setEntry(?EntryInterface $entry = null);

    /**
     * @return LabelInterface|null
     */
    public function getLabel(bool $create = false): ?LabelInterface;

    /**
     * @param LabelInterface|null $value
     */
    public function setLabel(?LabelInterface $value): void;

    /**
     * @return Feature[]|Collection
     */
    public function getFeatures(): Collection;

    /**
     * @param Feature $PropertyFeature
     * @return mixed
     */
    public function addFeature(Feature $PropertyFeature);

    /**
     * @param Feature $PropertyFeature
     * @return mixed
     */
    public function removeFeature(Feature $PropertyFeature);

    public static function createFeature (): FeatureInterface;
}
