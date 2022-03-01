<?php

namespace PhpDataMinerModel\Annotation;

use PhpDataMinerStorage\Model\Model as StorageModel;

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
