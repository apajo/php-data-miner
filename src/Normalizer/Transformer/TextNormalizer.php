<?php

namespace PhpDataMiner\Normalizer\Transformer;

/**
 * Description of Section
 *
 * @author Andres Pajo
 */
class TextNormalizer extends AbstractFilter
{
    function __construct (array $options = [])
    {
        parent::__construct([]  , $options);
    }

    public function transform(array &$samples) : void
    {
        foreach ($samples as $key => &$value) {
            $samples[$key] = mb_strtolower($value);
        }
    }
}
