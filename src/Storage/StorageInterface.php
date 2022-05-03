<?php

namespace PhpDataMiner\Storage;


use PhpDataMiner\Model\Property\PropertyInterface;
use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Storage\Model\Discriminator\DiscriminatorInterface;
use PhpDataMiner\Storage\Model\EntryInterface;
use PhpDataMiner\Storage\Model\LabelInterface;
use PhpDataMiner\Storage\Model\ModelInterface;
use PhpDataMiner\Storage\Model\Property;

/**
 * Description of AbstractStorage
 *
 * @author Andres Pajo
 */
interface StorageInterface
{
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
