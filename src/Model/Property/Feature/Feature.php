<?php

namespace PhpDataMinerModel\Property\Feature;

use PhpDataMinerNormalizer\Tokenizer\Token\Token;
use PhpDataMinerStorage\Model\FeatureVector;

class Feature
{
    public function vectorize(FeatureVector &$vector, Token $token)
    {
        $vector->setValue($token->getPointer()->get());
    }
}
