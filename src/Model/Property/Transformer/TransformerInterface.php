<?php

namespace DataMiner\Model\Property\Transformer;


/**
 * Description of Transformer
 *
 * @author Andres Pajo
 */
interface TransformerInterface
{
    /**
     * Transform value from the external document
     *
     * @param mixed $value
     * @param mixed $entity
     * @return mixed
     */
    public function import($value, $entity);

    /**
     * Transform value from the internal entity
     *
     * @param mixed $value
     * @param mixed $entity
     * @return mixed
     */
    public function export($value, $entity);
}
