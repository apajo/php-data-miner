<?php

namespace PhpDataMinerNormalizer\Tokenizer\Token;

use PhpDataMinerNormalizer\Tokenizer\TokenizerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Description of Cluster
 *
 * @author Andres Pajo
 */
class Cluster extends Token
{
    /**
     * @var TokenizerInterface[]|Collection
     */
    protected ?Collection $tokens = null;

    function __construct (array $values, array $options = [], TokenInterface $parent = null)
    {
        parent::__construct('', $options, $parent);

        $this->tokens = new ArrayCollection($values);
    }

    /**
     * @return []
     */
    public function getValue ()
    {
        $value = implode(' ', array_map(function ($token) {
            if ($token instanceof Cluster) {
                return $token->getValue();
            }

            return $token;
        }, $this->tokens->toArray()));

        return $value;
    }

    public function toArray ()
    {
        return array_filter(array_map(function ($value) {
            if ($value instanceof Cluster) {
                return $value->toArray();
            }

            // TODO THERE SHOULDN'T be any strings
            if (is_string($value)) {
                return new Token($value, [] , $this);
            }

            if (!$value){
                return ;
            }

            return $value->getValue();
        }, $this->tokens->toArray()));
    }

    public function __toString ()
    {
        return (string)$this->getValue();
    }

    public function getTokens ()
    {
        return $this->tokens;
    }

    public function setTokens (Cluster $tokens)
    {
        $this->tokens = $tokens;
    }

    public function setToken ($offset, TokenInterface $token)
    {
        $this->tokens->offsetSet($offset, $token);
        $token->setParent($this);
    }
}
