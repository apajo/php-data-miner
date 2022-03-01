<?php

namespace DataMiner\Model\Property;

use DataMiner\Kernel\KernelInterface;
use DataMiner\Model\Property\Feature\Feature;
use Doctrine\Common\Collections\Collection;

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
    public function create(string $name, array $options = []): \DataMiner\Model\Property\AbstractProperty;

    /**
     * @return Feature[]|Collection
     */
    public function getFeatures();

    /**
     * @param Feature $feature
     * @return mixed
     */
    public function addFeature(Feature $feature);

    /**
     * @param Feature $feature
     * @return mixed
     */
    public function removeFeature(Feature $feature);
}
