<?php

namespace PhpDataMiner\Storage;

use PhpDataMiner\Helpers\OptionsBuilderTrait;
use PhpDataMiner\Model\Property\PropertyInterface;
use PhpDataMiner\Normalizer\Document\Pointer;
use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Storage\Model\Discriminator\DiscriminatorInterface;
use PhpDataMiner\Storage\Model\EntryInterface;
use PhpDataMiner\Storage\Model\LabelInterface;
use PhpDataMiner\Storage\Model\Model;
use PhpDataMiner\Storage\Model\ModelInterface;
use PhpDataMiner\Storage\Model\Property as StorageProperty;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait StorageTrait
{
    use OptionsBuilderTrait;

    abstract public function save(ModelInterface $model): bool;

    abstract public function load($entity, array $options = []): ModelInterface;

    public function filterEntries(ModelInterface $model, array $filter = null)
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

    protected function buildOptions(array $options = [])
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
