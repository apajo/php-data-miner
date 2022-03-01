<?php

namespace PhpDataMiner\Model\Property\Feature;

class WordTreeFeature extends AbstractFeature
{
    public function vectorize(FeatureVector &$vector, Token $token)
    {
        $vector->setValue($token->getPointer()->get());
    }
}
