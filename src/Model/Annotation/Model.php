<?php

namespace DataMiner\Model\Annotation;

use DataMiner\Storage\Model\Model as StorageModel;

/**
 * @Annotation
 * @Target("CLASS")
 *
 * @author Andres Pajo
 */
final class Model implements AnnotationInterface
{
    const IMPLICIT = 'implicit';
    const EXPLICIT = 'explicit';

    /**
     * @Enum({"explicit", "implicit"})
     */
    public $strategy = self::EXPLICIT;

    /**
     * @var string
     */
    public $storageModel = StorageModel::class;
}
