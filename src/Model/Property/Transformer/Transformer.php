<?php

namespace PhpDataMiner\Model\Property\Transformer;

/**
 * Description of Transformer
 *
 * @author Andres Pajo
 */
class Transformer implements TransformerInterface
{
    /**
     * Transform value for the internal entity
     *
     * @param mixed $value
     * @param mixed $entity
     * @return mixed
     */
    public function import ($value, $entity)
    {
        return $value;
    }

    /**
     * Transform value for the external document
     *
     * @param mixed $value
     * @param mixed $entity
     * @return mixed
     */
    public function export ($value, $entity)
    {
        return $value;
    }
}
