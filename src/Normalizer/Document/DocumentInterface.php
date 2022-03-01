<?php

namespace PhpDataMiner\Normalizer\Document;

use ArrayIterator;
use PhpDataMiner\Normalizer\Tokenizer\Token\Cluster;
use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use Rubix\ML\Tokenizers\Tokenizer;
use Rubix\ML\Transformers\Transformer;

interface DocumentInterface
{
    /**
     * Get the traverses
     *
     * @return Traverser|null
     */
    public function getTraverser(): ?Traverser;

    /**
     * Get all the contents
     *
     * @return Cluster[]|Token[]|null
     */
    public function getContent(): ?array;

    /**
     * Get value with a pointer
     *
     * @param Pointer $pointer
     * @return TokenInterface|null
     */
    public function get(Pointer $pointer): ?TokenInterface;

    /**
     * Get content value
     *
     * @return string
     */
    public function getValue();

    /**
     * Retrive a simple array of the content
     *
     * @return array
     */
    public function toArray();

    /**
     * @return string|null
     */
    public function getLocale(): ?string;

    public function tokenize(Tokenizer $tokenizer): void;

    public function transform(Transformer $transformer): void;

    public function getIterator(): ArrayIterator;

    /**
     * Dump for debugging
     */
    public function _dump();
}
