<?php

namespace DataMiner\Normalizer\Tokenizer;

/**
 * Description of Row
 *
 * @author Andres Pajo
 */
class Row extends AbstractTokenizer implements TokenizerInterface
{
    function __construct (array $options = [])
    {
        parent::__construct('/(\n\s*)/m', $options);
    }
}
