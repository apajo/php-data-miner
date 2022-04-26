<?php

namespace PhpDataMiner;

use PhpDataMiner\Helpers\OptionsBuilderTrait;
use PhpDataMiner\Helpers\ResolveResult;
use PhpDataMiner\Kernel\KernelInterface;
use PhpDataMiner\Model\Describer;
use PhpDataMiner\Model\Mapper;
use PhpDataMiner\Model\Property\AbstractProperty;
use PhpDataMiner\Model\Property\DateProperty;
use PhpDataMiner\Model\Property\Feature\DefaultFeature;
use PhpDataMiner\Model\Property\Feature\WordTreeFeature;
use PhpDataMiner\Model\Property\FloatProperty;
use PhpDataMiner\Model\Property\IntegerProperty;
use PhpDataMiner\Model\Property\Property;
use PhpDataMiner\Model\Property\PropertyInterface;
use PhpDataMiner\Model\Property\Provider;
use PhpDataMiner\Model\Property\Registry;
use PhpDataMiner\Normalizer\Document\Document;
use PhpDataMiner\Normalizer\Document\Pointer;
use PhpDataMiner\Normalizer\Normalizer;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use PhpDataMiner\Storage\Model\EntryInterface;
use PhpDataMiner\Storage\Model\ModelInterface;
use PhpDataMiner\Storage\StorageInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PhpDataMinerTests\Kernel\TestKernel;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Miner
{
    use OptionsBuilderTrait;

    /**
     * @var ModelInterface
     */
    protected $model;

    /**
     * @var Mapper
     */
    protected $mapper;

    /**
     * @var StorageInterface
     */
    protected $storage;

    function __construct($entity, array $options = [])
    {
        $this->buildOptions($options);

        $this->mapper = new Mapper([
            'provider' => $this->options['properties']
        ]);

        $this->storage =  $this->getOption('storage');
        $this->model = $this->storage->load($entity);
    }

    public function predict (&$entity, Document $doc)
    {
        /** @var PropertyInterface[] $iterator */
        $iterator = $this->mapper->getIterator($entity);

        $changes = new ArrayCollection();
        $result = new ResolveResult();

        /** @var PropertyInterface $property */
        foreach ($iterator as $property) {
            /** @var TokenInterface $token */
            $token = $property->getKernel()->predict($this->model, $property, $doc);

            if (!$token) {
                continue;
            }

            $changes->offsetSet($property->getPropertyPath(), $token);
            $result->add($property, $token, $token->getValue(), [
                'vector' => (string)new Pointer($token->getOption('index'))
            ]);

            $property->setValue($entity, $token);
        }

        return $result;
    }

    public function train (&$entity, Document $doc)
    {
        /** @var Describer $description */
        $description = $this->mapper->describe($entity);

        $discriminator = $this->model::createEntryDiscriminator($entity);
        $result = new ResolveResult($discriminator->getString());

        /** @var PropertyInterface $prop */
        foreach ($description->iterator as $prop) {
            $token = $doc->traverser->search($entity, $prop);
            $value = $prop->getValue($entity);

            if ($value && $token) {
                /** @var EntryInterface $entry */
                $entry = $this->storage->getEntry(
                    $this->model,
                    $discriminator
                );

                $pointer = new Pointer($token->getoption('index'));
                $modelProp = $this->storage->getProperty($entry, $prop->getPropertyPath());
                $modelLabel = $this->storage->getLabel($this->model, $prop, $token);
                $modelLabel->setText($token->getText());

                $modelLabel->setValue((string)$pointer);

                $modelProp->setLabel($modelLabel);

                $entry->addProperty($modelProp);
                $this->model->addEntry($entry);


                $prop->getKernel()->buildVectors($modelProp, $token, $prop);
                $prop->getKernel()->train($entry, $prop);

                $result->add($prop, $token, $modelLabel->getValue());
            }
        }

        $this->storage->save($this->model);

        return $result;
    }

    public function normalize (string $content, array $normalizerOptions = [], array $documentOptions = []): ?Document
    {
        $document = new Document($content, $documentOptions);

        $normalizer = new Normalizer(array_merge([
            'filters' => $this->getOption('filters')
        ], $normalizerOptions));

        $normalizer->normalize($document);

        return $document;
    }

    public function getModel()
    {
        return $this->model;
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $kernel = new TestKernel();
        $feature = new WordTreeFeature();

        $resolver->setDefaults([
            'storage' => null,
            'properties' => new Provider(new Registry([
                new FloatProperty($kernel, [$feature]),
                new IntegerProperty($kernel, [$feature]),
                new DateProperty($kernel, [$feature]),
                new Property($kernel, [$feature]),
            ])),
            'filters' => new Provider(new Registry([
                new FloatProperty($kernel, [$feature]),
                new IntegerProperty($kernel, [$feature]),
                new DateProperty($kernel, [$feature]),
                new Property($kernel, [$feature]),
            ]))

        ]);
    }
}
