<?php

namespace PhpDataMiner\Model\Property\Feature;

use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Storage\Model\FeatureVector;

interface FeatureInterface
{
    public function vectorize(FeatureVector &$vector, Token $token);
}
