<?php

namespace DataMiner\Kernel;


use DataMiner\Model\Property\PropertyInterface;
use DataMiner\Normalizer\Document\Document;
use DataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use DataMiner\Storage\Model\Entry;
use DataMiner\Storage\Model\EntryInterface;
use DataMiner\Storage\Model\ModelInterface;
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
