<?php

namespace PhpDataMiner\Model\Property\Feature;

use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Storage\Model\Feature;

abstract class AbstractFeature implements FeatureInterface
{
    abstract public function vectorize(Feature &$vector, Token $token);
}
