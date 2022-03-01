<?php

namespace PhpDataMinerModel\Annotation;


use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;

/**
 * @Annotation
 * @Target({"METHOD","PROPERTY"})
 */
final class Property implements AnnotationInterface
{
    /**
     * @var mixed
     */
    public $type;

    /**
     * @var array
     */
    public array $properties = [];

    /**
     * @var PropertyInfoExtractor
     */
    protected $propertyInfo;

    function __construct ()
    {
        $phpDocExtractor = new PhpDocExtractor();
        $reflectionExtractor = new ReflectionExtractor();
        $this->propertyInfo = new PropertyInfoExtractor(
            [$reflectionExtractor],
            [$phpDocExtractor, $reflectionExtractor],
        );
    }

    public function getInfo ()
    {
        $propertyTypes = $this->propertyInfo->getTypes($describer->model, $pathBuilder->getPropertyPath());
    }
}
