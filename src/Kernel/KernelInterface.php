<?php

namespace PhpDataMinerKernel;


use PhpDataMinerModel\Property\PropertyInterface;
use PhpDataMinerNormalizer\Document\Document;
use PhpDataMinerNormalizer\Tokenizer\Token\TokenInterface;
use PhpDataMinerStorage\Model\Entry;
use PhpDataMinerStorage\Model\EntryInterface;
use PhpDataMinerStorage\Model\ModelInterface;
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
