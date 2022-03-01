<?php

namespace PhpDataMiner\Normalizer;

use PhpDataMiner\Normalizer\Document\Document;
use Rubix\ML\Tokenizers\Tokenizer;
use Rubix\ML\Transformers\Transformer;

interface NormalizerInterface
{
    /**
     * @param $filter
     * @param Document $document
     * @return Tokenizer|Transformer
     */
    public function applyFilter($filter, Document $document);
}
