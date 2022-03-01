<?php

namespace PhpDataMiner\Kernel;


use PhpDataMiner\Model\Property\PropertyInterface;
use PhpDataMiner\Normalizer\Document\Document;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use PhpDataMiner\Storage\Model\Entry;
use PhpDataMiner\Storage\Model\EntryInterface;
use PhpDataMiner\Storage\Model\ModelInterface;
use Doctrine\Common\Collections\Collection;

/**
 * Description of AbstractKernel
 *
 * @author Andres Pajo
 */
interface KernelInterface
{
    /**
     * @param ModelInterface $model
     * @param Document $doc
     * @param PropertyInterface $property
     * @return TokenInterface|null
     */
    public function predict(ModelInterface $model, PropertyInterface $property, Document $doc): ?TokenInterface;

    /**
     * @param Entry[]|Collection $entries
     */
    public function train(EntryInterface $entry, PropertyInterface $property);
}
