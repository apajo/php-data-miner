<?php

namespace PhpDataMinerNormalizer\Document;

use PhpDataMinerModel\Property\PropertyInterface;
use PhpDataMinerNormalizer\Tokenizer\Token\TokenInterface;

class Traverser
{
    /**
     * @var DocumentInterface $document
     */
    protected DocumentInterface $document;

    function __construct (DocumentInterface $document)
    {
        $this->document = $document;
    }

    /**
     * Retrive the value of the component
     *
     * @param Pointer $pointer
     * @param PropertyInterface|null $component
     * @return TokenInterface
     */
    public function getValue(Pointer $pointer, PropertyInterface $property = null): ?TokenInterface
    {
        return $this->document->get($pointer);
    }

    public function search($entity, PropertyInterface $property = null): ?TokenInterface
    {
        $target = $property->getValue($entity);

        $iterator = $this->document->getIterator();
        foreach ($iterator as $item) {
            if (!$item) {
                continue;
            }

            if (!$this->compareItems($item, $target)) {
                continue;
            }

            return $item;
        }


        return null;
    }


    public function find($target): ?TokenInterface
    {
        $iterator = $this->document->getIterator();
        foreach ($iterator as $item) {
            if (!$item) {
                continue;
            }

            if (!$this->compareItems($item, $target)) {
                continue;
            }

            return $item;
        }

        return null;
    }

    protected function compareItems (TokenInterface $token, $target)
    {
        return $token->getValue() == $target;
    }
}
