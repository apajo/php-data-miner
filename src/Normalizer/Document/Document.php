<?php

namespace PhpDataMinerNormalizer\Document;

use ArrayIterator;
use PhpDataMinerHelpers\OptionsBuilderTrait;
use PhpDataMinerNormalizer\Tokenizer\Token\Cluster;
use PhpDataMinerNormalizer\Tokenizer\Token\Token;
use PhpDataMinerNormalizer\Tokenizer\Token\TokenInterface;
use Rubix\ML\Tokenizers\Tokenizer;
use Rubix\ML\Transformers\Transformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Document implements DocumentInterface
{
    use OptionsBuilderTrait;

    /**
     * @var Cluster[]|Token[]|null
     */
    protected ?array $content = null;

    /**
     * @var string|null
     */
    protected ?string $locale;

    /**
     * @var Traverser|null
     * @property
     */
    protected ?Traverser $traverser = null;

    function __construct(string $value, array $options = [])
    {
        $this->buildOptions($options);

        $this->content = [new Token($value)];

        $this->traverser = new Traverser($this);
    }

    /**
     * Get the traverses
     *
     * @return Traverser|null
     */
    public function getTraverser(): ?Traverser
    {
        return $this->traverser;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'traverser':
                return $this->traverser;
        }
    }

    /**
     * Get all the contents
     *
     * @return Cluster[]|Token[]|null
     */
    public function getContent(): ?array
    {
        return $this->content;
    }

    /**
     * Get value with a pointer
     *
     * @param Pointer $pointer
     * @return TokenInterface|null
     */
    public function get (Pointer $pointer): ?TokenInterface
    {
        $index = $pointer->get();

        /** @var TokenInterface|Cluster $content */
        $content = $this->content[0];

        while ($index) {
            $i = array_shift($index);
            $content = $content->getTokens()->offsetGet($i);

            if (!($content instanceof Cluster)) {
                return $content;
            }
        }

        return null;
    }

    /**
     * Get content value
     *
     * @return string
     */
    public function getValue ()
    {
        return implode(' ',
            array_map(function (TokenInterface $token) {
                return $token->getValue();
            }, $this->content[0]->getTokens())
        );
    }

    /**
     * Retrive a simple array of the content
     *
     * @return array
     */
    public function toArray ()
    {
        return array_map(function (TokenInterface $token) {
            return $token->toArray();
        }, $this->content[0]->getTokens()->toArray());
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function tokenize (Tokenizer $tokenizer): void
    {
        foreach ($this->content as $i => $token) {
            $tokens = $tokenizer->tokenize($token);
            $this->content[$i] = $tokens[0];
        }
    }

    public function transform (Transformer $transformer): void
    {
        $content = $this->content;
        $transformer->transform($content);
        $this->content = $content;
    }

    public function getIterator(): ArrayIterator
    {
        $iterator = new ArrayIterator();

        foreach ($this->content as $row) {
            if ($row instanceof Cluster) {
                $this->getClusterIterator($row, $iterator);
            }
        }

        return $iterator;
    }

    protected function getClusterIterator(Cluster $cluster, ArrayIterator $iterator)
    {
        foreach ($cluster->getTokens() as $token) {
            if ($token instanceof Cluster) {
                $this->getClusterIterator($token, $iterator);
            }

            if ($token instanceof Token) {
                $iterator->append($token);
            }
        }
    }

    /**
     * Dump for debugging
     */
    public function _dump()
    {
        $iter = $this->getIterator();

        while($iter->valid() ) {
            /** @var TokenInterface $token */
            $token = $iter->current();
            dump ($this->_printDebug($token));
            $iter->next();
        }
    }

    protected function _printDebug(TokenInterface $item)
    {
        return sprintf(
            '[%s %s] %s',
            str_pad($item->getPointer(), 6, ' ', STR_PAD_LEFT),
            gettype($item->getText()) !== 'object' ? gettype($item->getText()) : get_class($item->getText()),
            $item
        );
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'filters' => []
        ));
    }
}
