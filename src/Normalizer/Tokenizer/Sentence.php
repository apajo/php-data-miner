<?php

namespace PhpDataMinerNormalizer\Tokenizer;

/**
 * Description of Word
 *
 * @author Andres Pajo
 */
class Sentence extends AbstractTokenizer implements TokenizerInterface
{
    function __construct (array $options = [])
    {
        parent::__construct('/(?<=[^\d][.?!])\s+(?=[a-z])/i', $options);
    }
}
