<?php

namespace PhpDataMiner\Helpers;

use PhpDataMiner\Model\Property\PropertyInterface;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;
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
