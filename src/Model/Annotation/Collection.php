<?php

namespace DataMiner\Model\Annotation;


/**
 * @Annotation
 * @Target({"METHOD","PROPERTY"})
 *
 * @author Andres Pajo
 */
class Collection implements AnnotationInterface
{
    /**
     * @var string
     */
    public ?string $class;
}
