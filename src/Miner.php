<?php

namespace PhpDataMiner;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PhpDataMiner\Helpers\OptionsBuilderTrait;
use PhpDataMiner\Helpers\ResolveResult;
use PhpDataMiner\Model\Describer;
use PhpDataMiner\Model\Mapper;
use PhpDataMiner\Model\Property\Property;
use PhpDataMiner\Model\Property\PropertyInterface;
use PhpDataMiner\Model\Property\Provider;
use PhpDataMiner\Normalizer\Document\Document;
use PhpDataMiner\Normalizer\Document\Pointer;
use PhpDataMiner\Normalizer\Normalizer;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use PhpDataMiner\Normalizer\Transformer\FilterInterface;
use PhpDataMiner\Storage\Model\Entry;
use PhpDataMiner\Storage\Model\EntryInterface;
use PhpDataMiner\Storage\Model\ModelInterface;
use PhpDataMiner\Storage\StorageInterface;
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

    /**
     * @var Provider
     */
    protected $provider;

    /**
     * @var Collection|FilterInterface[]
     */
    private Collection $filters;


    function __construct($entity, Provider $provider, StorageInterface $storage, array $filters = [], array $options = [])
    {
        $this->buildOptions($options);

        $this->mapper = new Mapper([
            'provider' => $provider
        ]);

        $this->provider = $provider;
        $this->filters = new ArrayCollection($filters);

        $this->storage =  $storage;
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

    public function train (&$entity, Document $doc): Entry
    {
        /** @var Describer $description */
        $description = $this->mapper->describe($entity);

        $discriminator = $this->model::createEntryDiscriminator($entity);

        /** @var EntryInterface $entry */
        $entry = $this->model->getEntry(
            $discriminator,
            false
        );

        if (!$entry) {
            $entry = $this->model::createEntry();
            $entry->setDiscriminator($discriminator);
            $entry->setModel($this->model);
        }

        /** @var PropertyInterface $prop */
        foreach ($description->iterator as $prop) {
            $token = $doc->traverser->search($entity, $prop);
            $value = $prop->getValue($entity);

            if ($value && $token) {
                $property = $entry->getProperty($prop->getName(), true);

                $pointer = new Pointer($token->getOption('index'));
                $label = $property->getLabel(true);
                $property->setLabel($label);

                $label->setValue($pointer);
                $label->setText($value);

                $prop->getKernel()->buildVectors($property, $token, $prop);
                $prop->getKernel()->train($entry, $prop);
            }
        }

        $this->model->addEntry($entry);
        $this->storage->save($this->model);

        return $entry;
    }

    public function normalize (string $content, array $normalizerOptions = [], array $documentOptions = []): ?Document
    {
        $document = new Document($content, $documentOptions);

        $normalizer = new Normalizer(array_merge([
            'filters' => $this->collectFilters()
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
        $resolver->setDefaults([
            'storage' => null,
        ]);
    }

    /**
     * @return FilterInterface[]
     */
    public function collectFilters(): array
    {
        $result = [];

        /** @var Property $property */
        foreach ($this->provider->getRegistry() as $property) {
            foreach ($property->getFilters() as $filter) {
                $class = get_class($filter);

                if (isset($result[$class])) {
                    continue;
                }

                $result[$class] = $filter;
            }
        }

        foreach ($this->filters as $filter) {
            $class = get_class($filter);

            if (isset($result[$class])) {
                continue;
            }

            $result[$class] = $filter;
        }

        return $result;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters(): Collection
    {
        return $this->filters;
    }

    public function addFilter(FilterInterface $filter): void
    {
        $this->filters->add($filter);
    }

    public function removeFilter(FilterInterface $filter): void
    {
        $this->filters->removeElement($filter);
    }
}
