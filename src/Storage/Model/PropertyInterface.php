<?php

namespace DataMiner\Storage\Model;

use Doctrine\Common\Collections\Collection;

/**
 * Interface PropertyInterface
 * @package DataMiner\Storage\Model
 */
interface PropertyInterface
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
    public function getLabel(): ?LabelInterface;

    /**
     * @param LabelInterface|null $value
     */
    public function setLabel(?LabelInterface $value): void;

    /**
     * @return FeatureVector[]|Collection
     */
    public function getFeatureVectors(): Collection;

    /**
     * @param FeatureVector $PropertyFeature
     * @return mixed
     */
    public function addFeatureVector(FeatureVector $PropertyFeature);

    /**
     * @param FeatureVector $PropertyFeature
     * @return mixed
     */
    public function removeFeatureVector(FeatureVector $PropertyFeature);
}
