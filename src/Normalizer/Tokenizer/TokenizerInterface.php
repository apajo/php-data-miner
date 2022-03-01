<?php

namespace PhpDataMinerNormalizer\Tokenizer;

use Rubix\ML\Tokenizers\Tokenizer;

/**
 * Description of TokenizerInterface
 *
 * @author Andres Pajo
 */
interface TokenizerInterface extends Tokenizer
{
    public function tokenize(string $text): array;
}
