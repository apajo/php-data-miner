<?php

namespace DataMiner\Normalizer;

use DataMiner\Helpers\OptionsBuilderTrait;
use DataMiner\Normalizer\Document\Document;
use Exception;
use Rubix\ML\Tokenizers\Tokenizer;
use Rubix\ML\Transformers\Transformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Normalizer implements NormalizerInterface
{
    use OptionsBuilderTrait;

    function __construct(array $options = [])
    {
        $this->buildOptions($options);
    }

    public function normalize (Document $document)
    {
        $filters = $this->getOption('filters');

        foreach ($filters as $filter) {
            $this->applyFilter(new $filter, $document);
        }
    }


    public function applyFilter ($filter, Document $document)
    {
        if ($filter instanceof Transformer) {
            $document->transform($filter);
            return;
        }

        if ($filter instanceof Tokenizer) {
            $document->tokenize($filter);
            return;
        }

        throw new Exception('Unknown filter type: ' . $filter);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'filters' => []
        ));
    }
}
