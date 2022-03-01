<?php

namespace PhpDataMiner;

use PhpDataMiner\Helpers\OptionsBuilderTrait;
use PhpDataMiner\Helpers\ResolveResult;
use PhpDataMiner\Kernel\KernelInterface;
use PhpDataMiner\Model\Describer;
use PhpDataMiner\Model\Mapper;
use PhpDataMiner\Model\Property\Property;
use PhpDataMiner\Model\Property\PropertyInterface;
use PhpDataMiner\Model\Property\Provider;
use PhpDataMiner\Model\Property\Registry;
use PhpDataMiner\Normalizer\Document\Document;
use PhpDataMiner\Normalizer\Normalizer;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use PhpDataMiner\Storage\Model\EntryInterface;
use PhpDataMiner\Storage\Model\ModelInterface;
use PhpDataMiner\Storage\StorageInterface;
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
            'properties' => new Provider(new Registry([
                new Property(),
            ]))
        ]);
    }
}
