<?php

namespace DataMiner\Helpers;

use DataMiner\Model\Property\PropertyInterface;
use DataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use Doctrine\Common\Collections\ArrayCollection;

class ResolveResult
{
    protected $items;

    protected $title;

    function __construct (string $title = null)
    {
        $this->title = $title;
        $this->items = new ArrayCollection();
    }

    public function add (PropertyInterface $feature, TokenInterface $token, $value)
    {
        $this->items->add([
            $feature->getPropertyPath(),
            $value
        ]);
    }
}
