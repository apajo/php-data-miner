<?php

namespace PhpDataMiner\Normalizer\Tokenizer;

/**
 * Description of Word
 *
 * @author Andres Pajo
 */
class Word extends AbstractTokenizer implements TokenizerInterface
{
    function __construct (array $options = [])
    {
        parent::__construct("/([\w'-]+\S)/u", $options);
    }

    /**
     * @param string $text
     * @return array
     */
    public function tokenize(string $text) : array
    {
        $tokens = [];

        preg_match_all($this->pattern, $text, $tokens);

        return $tokens[0];
    }
}
