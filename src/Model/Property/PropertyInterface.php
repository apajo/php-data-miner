<?php

namespace PhpDataMiner\Model\Property;

use PhpDataMiner\Kernel\KernelInterface;
use PhpDataMiner\Model\Property\Feature\Feature;
use Doctrine\Common\Collections\Collection;
use PhpDataMiner\Model\Property\Feature\FeatureInterface;

/**
 *
 */
interface PropertyInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return KernelInterface|null
     */
    public function getKernel(): ?KernelInterface;

    /**
     * @return string|null
     */
    public function getTypeName(): ?string;

    /**
     * @return string|null
     */
    public function getPropertyPath(): ?string;

    /**
     * @param string $name
     * @return mixed
     */
    public function getOption(string $name);

    /**
     * @param $entity
     * @return mixed
     */
    public function getValue($entity);

    /**
     * @param $entity
     * @param $value
     * @return mixed
     */
    public function setValue($entity, $value);

    /**
     * @param mixed $value
     * @param mixed $entity
     * @return mixed
     */
    public function normalize($value, $entity);


    /**
     * @param string $type
     * @param null $entity
     * @return bool
     */
    public function supports(string $type, $entity = null): bool;

    /**
     * @param string $name
     * @param array $options
     * @return AbstractProperty
     */
    public function create(string $name, array $options = []): \PhpDataMiner\Model\Property\AbstractProperty;

    /**
     * @return FeatureInterface[]|Collection
     */
    public function getFeatures();

    /**
     * @param FeatureInterface $feature
     * @return mixed
     */
    public function addFeature(FeatureInterface $feature);

    /**
     * @param FeatureInterface $feature
     * @return mixed
     */
    public function removeFeature(FeatureInterface $feature);
}
