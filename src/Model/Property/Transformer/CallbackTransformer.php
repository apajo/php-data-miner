<?php

namespace PhpDataMiner\Model\Property\Transformer;

/**
 * Description of Transformer
 *
 * @author Andres Pajo
 */
class CallbackTransformer implements TransformerInterface
{
    /**
     * @var callable
     */
    protected $import;

    /**
     * @var callable
     */
    protected $export;

    /**
     * @param callable $import
     * @param callable $export
     */
    function __construct($import, $export)
    {
        $this->import = $import;
        $this->export = $export;
    }

    /**
     * @inheritdoc
     */
    public function import ($value, $entity)
    {
        $cb = $this->import;
        return $cb($value, $entity);
    }

    /**
     * @inheritdoc
     */
    public function export ($value, $entity)
    {
        $cb = $this->export;
        return $cb($value, $entity);
    }
}
