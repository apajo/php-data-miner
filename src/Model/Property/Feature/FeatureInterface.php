<?php

namespace PhpDataMiner\Model\Property\Feature;

use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Storage\Model\Feature;

interface FeatureInterface
{
    public function vectorize(Feature &$vector, Token $token);

    public function getWeight(): float;
}
