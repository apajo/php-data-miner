<?php

namespace PhpDataMinerNormalizer\Tokenizer;

/**
 * Description of Column
 *
 * @author Andres Pajo
 */
class Column extends AbstractTokenizer implements TokenizerInterface
{
    function __construct (array $options = [])
    {
        parent::__construct('/(^:\t)|(\t\s*)/m', $options);
    }
}
