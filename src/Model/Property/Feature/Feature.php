<?php

namespace DataMiner\Model\Property\Feature;

use DataMiner\Normalizer\Tokenizer\Token\Token;
use DataMiner\Storage\Model\FeatureVector;

class Feature
{
    public function vectorize(FeatureVector &$vector, Token $token)
    {
        $vector->setValue($token->getPointer()->get());
    }
}
