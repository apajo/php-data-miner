<?php

namespace PhpDataMiner\Model\Property;

use PhpDataMiner\Helpers\OptionsBuilderTrait;
use PhpDataMiner\Kernel\KernelInterface;
use PhpDataMiner\Model\Property\Feature\FeatureInterface;
use PhpDataMiner\Model\Property\Transformer\Transformer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PhpDataMiner\Normalizer\Transformer\FilterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class AbstractProperty implements PropertyInterface, FlattenFeatureVectors
{
    use OptionsBuilderTrait;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string|null
     */
    protected ?string $propertyPath = null;

    /**
     * @var PropertyAccessor|null
     */
    protected ?PropertyAccessor $accessor;

    /**
     * @var KernelInterface|null
     */
    protected ?KernelInterface $kernel = null;

    /**
     * @var Collection|FeatureInterface[]
     */
    protected Collection $features;

    /**
     * @var Collection|FilterInterface[]
     */
    protected Collection $filters;

    /**
     *
     * @param KernelInterface $kernel
     * @param FeatureInterface[]|Collection $features
     * @param array $options
     */
    function __construct (KernelInterface $kernel, array $features, array $filters = [], array $options = [])
    {
        $this->buildOptions($options);

        $this->kernel = $kernel;

        if (count($features) == 0) {
            throw new \Exception('Atleast one feature is required per property!');
        }

        $this->features = new ArrayCollection();
        foreach ($features as $feature) {
            $this->addFeature($feature);
        }

        $this->filters = new ArrayCollection();
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getKernel(): ?KernelInterface
    {
        return $this->kernel;
    }

    abstract public function getTypeName(): ?string;

    public function getPropertyPath(): ?string
    {
        return $this->propertyPath;
    }

    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    public function getValue($entity)
    {
        /** @var PropertyAccessor $accessor */
        $accessor = $this->options['property_accessor'];
        /** @var Transformer $transformer */
        $transformer = $this->options['transformer'];

        $value =  $accessor->getValue($entity, $this->propertyPath);
        return $transformer->export($value, $entity);
    }

    public function setValue($entity, $value)
    {
        /** @var PropertyAccessor $accessor */
        $accessor = $this->options['property_accessor'];

        $accessor->setValue($entity, $this->propertyPath, $this->normalize($value, $entity));
    }

    /**
     * @param mixed $value
     * @param mixed $entity
     * @return mixed
     */
    public function normalize($value, $entity)
    {
        /** @var Transformer $transformer */
        $transformer = $this->options['transformer'];

        return $transformer->import($value, $entity);
    }

    abstract public function supports(string $type, $entity = null): bool;

    public function create(string $name, array $options = []): self
    {
        $instance = clone $this;
        $instance->init($name, $options);
        return $instance;
    }

    protected function init (string $name, array $options = [])
    {
        $this->name = $name;

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);

        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->propertyPath = $this->options['property_path'];
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'property_path' => null,
            'type' => null,
            'class' => null,
            'transformer' => new Transformer(),
            'property_accessor' => PropertyAccess::createPropertyAccessor(),
            'filters' => [],
        ]);
    }

    /**
     * @return FeatureInterface
     */
    public function getFeature($offset): ?FeatureInterface
    {
        return $this->features->offsetGet($offset);
    }

    /**
     * @return FeatureInterface[]|Collection
     */
    public function getFeatures()
    {
        return $this->features;
    }

    public function addFeature(FeatureInterface $feature): self
    {
        $this->features->add($feature);
        return $this;
    }

    public function removeFeature(FeatureInterface $feature)
    {
        $this->features->removeElement($feature);
        return $this;
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
