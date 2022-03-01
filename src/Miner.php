<?php

namespace DataMiner;

use DataMiner\Helpers\OptionsBuilderTrait;
use DataMiner\Helpers\ResolveResult;
use DataMiner\Kernel\KernelInterface;
use DataMiner\Model\Describer;
use DataMiner\Model\Mapper;
use DataMiner\Model\Property\Property;
use DataMiner\Model\Property\PropertyInterface;
use DataMiner\Model\Property\Provider;
use DataMiner\Model\Property\Registry;
use DataMiner\Normalizer\Document\Document;
use DataMiner\Normalizer\Normalizer;
use DataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use DataMiner\Storage\Model\EntryInterface;
use DataMiner\Storage\Model\ModelInterface;
use DataMiner\Storage\StorageInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Miner
{
    use OptionsBuilderTrait;

    /**
     * @var KernelInterface
     */
    protected $kernel;

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

    function __construct( KernelInterface $kernel, $entity, array $options = [])
    {
        $this->buildOptions($options);

        /** @var KernelInterface kernel */
        $this->kernel = $kernel;

        $this->mapper = new Mapper([
            'provider' => new Provider(new Registry($this->options['property_types']))
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
            $token = $this->getKernel($property)->predict($this->model, $property, $doc);

            if (!$token) {
                continue;
            }

            $changes->offsetSet($property->getPropertyPath(), $token);
            $result->add($property, $token, $token->getValue());

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

                $modelProp = $this->storage->getProperty($entry, $prop->getPropertyPath());
                $modelLabel = $this->storage->getLabel($this->model, $prop, $token->getText());

                $modelProp->setLabel($modelLabel);

                $entry->addProperty($modelProp);
                $this->model->addEntry($entry);


                $this->getKernel($prop)->buildVectors($modelProp, $token, $prop);
                $this->getKernel($prop)->train($entry, $prop);

                $result->add($prop, $token, $modelLabel->getValue());
            }
        }

        $this->storage->save($this->model);

        return $result;
    }

    public function normalize (string $content, array $normalizerOptions = [], array $documentOptions = []): ?Document
    {
        $document = new Document($content, $documentOptions);

        $normalizer = new Normalizer($normalizerOptions);
        $normalizer->normalize($document);

        return $document;
    }

    public function getModel()
    {
        return $this->model;
    }

    protected function getKernel (PropertyInterface $property): ?KernelInterface
    {
        if ($property->getKernel()) {
            return $property->getKernel();
        }

        return $this->kernel;
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'storage' => null,
            'property_types' => [
                new Property(),
            ]
        ]);
    }

    protected function buildVectors(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'storage' => null,
            'property_types' => [
                new Property(),
            ]
        ]);
    }
}
