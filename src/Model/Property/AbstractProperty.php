<?php

namespace DataMiner\Model\Property;

use DataMiner\Helpers\OptionsBuilderTrait;
use DataMiner\Kernel\KernelInterface;
use DataMiner\Model\Property\Feature\Feature;
use DataMiner\Model\Property\Transformer\Transformer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class AbstractProperty implements PropertyInterface
{
    use OptionsBuilderTrait;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string|null
     */
    private ?string $propertyPath = null;

    /**
     * @var PropertyAccessor|null
     */
    protected ?PropertyAccessor $accessor;

    /**
     * @var KernelInterface|null
     */
    protected ?KernelInterface $kernel = null;

    /**
     * @var Collection|Feature[]
     */
    private Collection $features;

    function __construct (array $options = [])
    {
        $this->buildOptions($options);

        $this->features = new ArrayCollection();

        $this->kernel = $this->getOption('kernel');
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
            'kernel' => null,
            'property_accessor' => PropertyAccess::createPropertyAccessor(),
        ]);
    }

    /**
     * @return Feature[]|Collection
     */
    public function getFeatures()
    {
        return $this->features;
    }

    public function addFeature(Feature $feature)
    {
        $this->features->add($feature);
    }

    public function removeFeature(Feature $feature)
    {
        $this->features->removeElement($feature);
    }
}
