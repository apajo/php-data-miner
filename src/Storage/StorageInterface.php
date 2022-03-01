<?php

namespace PhpDataMinerStorage;


use PhpDataMinerModel\Property\PropertyInterface;
use PhpDataMinerStorage\Model\Discriminator\DiscriminatorInterface;
use PhpDataMinerStorage\Model\EntryInterface;
use PhpDataMinerStorage\Model\LabelInterface;
use PhpDataMinerStorage\Model\ModelInterface;
use PhpDataMinerStorage\Model\Property;

/**
 * Description of AbstractStorage
 *
 * @author Andres Pajo
 */
interface StorageInterface
{
    /**
     * @param $entity
     * @return ModelInterface|null
     */
    public function getModel($entity): ?ModelInterface;

    /**
     * @param ModelInterface $model
     * @param DiscriminatorInterface|null $discriminator
     * @param bool $create
     * @return EntryInterface|null
     */
    public function getEntry(ModelInterface $model, DiscriminatorInterface $discriminator = null, bool $create = true): ?EntryInterface;

    /**
     * @param EntryInterface $entry
     * @param string $property
     * @param bool $create
     * @return Property|null
     */
    public function getProperty(EntryInterface $entry, string $property, bool $create = true): ?Property;

    /**
     * @param ModelInterface $model
     * @param PropertyInterface $property
     * @param string $label
     * @param bool $create
     * @return LabelInterface|null
     */
    public function getLabel(ModelInterface $model, PropertyInterface $property, string $label, bool $create = true): ?LabelInterface;

    /**
     * @param string $name
     * @return mixed
     */
    public function getOption(string $name);

    /**
     * @param ModelInterface $model
     * @return bool
     */
    public function save(ModelInterface $model): bool;

    /**
     * @param $entity
     * @param array $options
     * @return ModelInterface
     */
    public function load($entity, array $options = []): ModelInterface;

    /**
     * @param ModelInterface $model
     * @param array|null $filter
     * @return mixed
     */
    public function filterEntries(ModelInterface $model, array $filter = null);
}
