<?php

namespace PhpDataMiner\Kernel;


use PhpDataMiner\Model\Property\PropertyInterface as ModelPropertyInterface;
use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Storage\Model\PropertyInterface as StoragePropertyInterface;
use PhpDataMiner\Normalizer\Document\Document;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use PhpDataMiner\Storage\Model\ModelInterface;

/**
 * Description of AbstractKernel
 *
 * @author Andres Pajo
 */
interface KernelInterface
{
    /**
     * @param StoragePropertyInterface $model
     * @param ModelPropertyInterface $property
     * @param Document $doc
     * @return TokenInterface|null
     */
    public function predict(StoragePropertyInterface $property, ModelPropertyInterface $modelProperty, Document $doc): ?TokenInterface;

    /**
     * @param StoragePropertyInterface $property
     * @param ModelPropertyInterface $modelProperty
     * @param Token $token
     * @param Document $doc
     * @return mixed
     */
    public function train (StoragePropertyInterface $property, ModelPropertyInterface $modelProperty, Document $doc, Token $token);

    /**
     * @param StoragePropertyInterface $property
     * @param ModelPropertyInterface $modelProperty
     * @param Token $token
     * @param Document $doc
     * @return mixed
     */
    public function buildVectors (StoragePropertyInterface $property, ModelPropertyInterface $modelProperty, Document $doc, Token $token);
}
