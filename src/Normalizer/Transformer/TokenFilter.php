<?php

namespace PhpDataMinerNormalizer\Transformer;

use PhpDataMinerNormalizer\Tokenizer\Token\Token;

/**
 * Description of RegexFilter
 *
 * @author Andres Pajo
 */
class TokenFilter extends AbstractFilter
{
    /**
     * @var array
     */
    protected array $patterns = [];

    /**
     * @var string|array
     */
    protected $replace;

    function __construct ()
    {
        parent::__construct([]);
    }


    public function transform(array &$samples) : void
    {
        foreach ($samples as $key => $value) {
            $samples[$key] = new Token($value);
        }
    }
}
