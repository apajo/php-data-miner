<?php

namespace PhpDataMiner\Model\Property\Feature;

use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Storage\Model\Feature;

class WordTreeFeature extends DefaultFeature
{
    public function vectorize(Feature &$vector, Token $token)
    {
        $vector->setValue($token->getPointer()->get());
    }
}
