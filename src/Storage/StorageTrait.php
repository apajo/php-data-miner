<?php

namespace DataMiner\Storage;

use DataMiner\Helpers\OptionsBuilderTrait;
use DataMiner\Model\Property\PropertyInterface;
use DataMiner\Storage\Model\Discriminator\DiscriminatorInterface;
use DataMiner\Storage\Model\EntryInterface;
use DataMiner\Storage\Model\LabelInterface;
use DataMiner\Storage\Model\Model;
use DataMiner\Storage\Model\ModelInterface;
use DataMiner\Storage\Model\Property as StorageProperty;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait StorageTrait
{
    use OptionsBuilderTrait;

    abstract public function save(ModelInterface $model): bool;

    abstract public function load($entity, array $options = []): ModelInterface;

    public function getModel($entity): ?ModelInterface
    {
        $model = new Model();
        $model->setModel(get_class($entity));

        return $model;
    }

    public function getEntry(ModelInterface $model, DiscriminatorInterface $discriminator = null, bool $create = true): ?EntryInterface
    {
        $entry = $model->getEntry($discriminator);

        if ($entry) {
            return $entry;
        }

        if (!$create) {
            return null;
        }

        /** @var EntryInterface $entry */
        $entry = new $this->entryModel();
        $entry->setDiscriminator($discriminator);

        $model->addEntry($entry);

        return $entry;
    }

    public function getProperty(EntryInterface $entry, string $property, bool $create = true): ?StorageProperty
    {
        $prop = $entry->getProperty($property);

        if ($prop) {
            return $prop;
        }

        if (!$create) {
            return null;
        }

        $prop = new $this->propertyModel();
        $prop->setName($property);
        $entry->addProperty($prop);

        return $prop;
    }

    public function getLabel(ModelInterface $model, PropertyInterface $property, string $value, bool $create = true): ?LabelInterface
    {
        $label = $model->getLabel($property, $value);

        if (!$label && $create) {
            /** @var LabelInterface $new */
            $label = new $this->labelModel();
            $label->setProperty($property->getPropertyPath());
            $label->setValue($value);

            $model->addLabel($label);
        }

        return $label;
    }

    public function filterEntries (ModelInterface $model, array $filter = null)
    {
        $model->filterEntries(function (EntryInterface $a) use ($filter) {
            $regex = '/' . implode('\.', array_map(function ($a) {
                    return '(' . ($a ?: '\d*') . ')';
                }, $filter)) . '/';
            $target = $a->getDiscriminator()->getString();

            preg_match($regex, $target, $matches);

            return (bool)$matches;
        });
    }

    protected function buildOptions (array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'discriminator' => null,
            'property' => null,
        ));
    }
}
