<?php


namespace PhpDataMinerModel;

use ArrayIterator;
use ArrayObject;
use PhpDataMinerHelpers\OptionsBuilderTrait;
use PhpDataMinerModel\Annotation\Collection;
use PhpDataMinerModel\Annotation\Ignore;
use PhpDataMinerModel\Annotation\Model;
use PhpDataMinerModel\Annotation\Property as PropertyAnnotation;
use PhpDataMinerModel\Property\Property;
use PhpDataMinerModel\Property\PropertyInterface;
use PhpDataMinerModel\Property\Provider;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPathBuilder;

AnnotationRegistry::registerLoader('class_exists');
AnnotationRegistry::registerAutoloadNamespace('PhpDataMinerModel\Annotation', __DIR__ . "/../src/Model/Annotation");

class Mapper
{
    use OptionsBuilderTrait;

    /**
     * @var AnnotationReader|null
     */
    protected ?AnnotationReader $reader;

    /**
     * @var PropertyAccessor|null
     */
    protected ?PropertyAccessor $accessor;

    /**
     * @var Provider
     */
    protected $provider;

    function __construct (array $options = [])
    {
        $this->buildOptions($options);

        $this->provider = $this->options['provider'];

        $this->reader = new AnnotationReader();
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }


    /**
     * @param $entity
     * @return Property[]|ArrayIterator
     */
    public function describe ($entity): Describer
    {
        $describer = new Describer();
        $describer->iterator = new ArrayObject();
        $describer->model = get_class($entity);
        $describer->entity = $entity;

        $this->readClass($describer);

        return $describer;
    }


    /**
     * @param $entity
     * @return Property[]|ArrayIterator
     */
    public function getIterator ($entity): ArrayIterator
    {
        $describer = $this->describe($entity);

        return $describer->iterator->getIterator();
    }

    public function readClass (Describer $describer)
    {
        $reflectionClass = new ReflectionClass($describer->model);

        /** @var Model $model */
        $annots = $this->reader->getClassAnnotations(
            $reflectionClass,
        );

        foreach ($annots as $annot) {
            if (!($annot instanceof Model)) {
                throw new InvalidClassModelAnnotation('Data miner model neets @Model annotation!');
            }

            $describer->strategy = $annot->strategy;
            $describer->storageModel = $annot->storageModel;

            $this->readProperties(
                $reflectionClass->getProperties(),
                $describer,
                new PropertyPathBuilder(null)
            );
        }
    }

    /**
     * @param ReflectionProperty[] $properties
     * @param Describer $describer
     */
    protected function readProperties (array $properties, Describer $describer, PropertyPathBuilder $pathBuilder)
    {
        /** @var ReflectionProperty $property */
        foreach ($properties as $property) {
            $propertyPathBuilder = new PropertyPathBuilder($pathBuilder->getPropertyPath());
            $this->resolvePropertyAction($property, $describer, $propertyPathBuilder);
        }
    }

    protected function readCollection (Collection $annotation, Describer $describer, PropertyPathBuilder $pathBuilder)
    {
        $reflectionClass = new ReflectionClass($annotation->class);
        $items = $this->accessor->getValue($describer->entity, $pathBuilder->getPropertyPath());

        foreach ($items as $index => $item) {
            $propertyPathBuilder = new PropertyPathBuilder($pathBuilder->getPropertyPath());
            $propertyPathBuilder->appendIndex($index);

            $this->readProperties(
                $reflectionClass->getProperties(),
                $describer,
                $propertyPathBuilder
            );
        }
    }

    protected function resolvePropertyAction (ReflectionProperty $property, Describer $describer, PropertyPathBuilder $pathBuilder)
    {
        $name = $property->getName();
        $annots = $this->reader->getPropertyAnnotations($property);

        if ($this->isIgnored($annots)) {
            return true;
        }

        $pathBuilder->appendProperty($name);
        $type = $this->provider->getType($describer->model, $pathBuilder->getPropertyPath());

        /** @var PropertyInterface $class */
        $class = $this->provider->resolveProperty($property, $type, $annots);

        $modelProperty = $class->create(
            $name,
            [
                'property_path' => (string)$pathBuilder->getPropertyPath(),
                'type' => $type ? $type->getBuiltinType() : null,
                'class' => $type ? $type->getClassName() : null,
            ]
        );

        foreach ($annots as $annot) {
            foreach ($this->options['annotations'] as $class => $callback) {
                if (!($annot instanceof $class)) {
                    continue;
                }

                if (!$callback($annot, $describer, $modelProperty, $pathBuilder)) {
                    return false;
                }
            }

        }



        if ($describer->strategy === Model::IMPLICIT && !$annots) {
            $describer->iterator->offsetSet(
                (string)$pathBuilder->getPropertyPath(),
                $modelProperty,
            );

            return true;
        }

        return false;
    }

    protected function isIgnored (array $annots)
    {
        $annotations = array_keys($this->options['annotations']);

        foreach ($annots as $annot) {
            if ($annot instanceof Ignore) {
                return true;
            }

            if (in_array(get_class($annot), $annotations)) {
                return false;
            }
        }

        return true;
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'provider' => null,
            'annotations' => [
                Ignore::class => function () {
                    return false;
                },
                Collection::class => function ($annot, $describer, $modelProperty, $pathBuilder) {
                    $this->readCollection(
                        $annot,
                        $describer,
                        $pathBuilder
                    );

                    return true;
                },
                PropertyAnnotation::class => function ($annot, $describer, $modelProperty, $pathBuilder) {
                    $describer->iterator->offsetSet(
                        (string)$pathBuilder->getPropertyPath(),
                        $modelProperty,
                    );

                    return true;
                }
            ],
        ));
    }
}
