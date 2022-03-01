<?php

namespace DataMiner\Normalizer\Tokenizer\Token;

use DataMiner\Normalizer\Document\Pointer;
use Stringable;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of Token
 *
 * @author Andres Pajo
 */
class Token implements Stringable, TokenInterface
{
    use TokenTrait;

    /**
     * @var TokenInterface|Cluster|null
     */
    private ?TokenInterface $parent = null;

    /**
     * @param string $text
     * @param array $options
     */
    function __construct ($text, array $options = [], TokenInterface $parent = null)
    {
        $this->text = $text;
        $this->parent = $parent;

        $this->buildTokenOptions($options);
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->text;
    }

    function __toString()
    {
        return (string)$this->text;
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'index' => [],
            'start' => null,
            'end' => null,
            'tokenizer' => null,
        ]);
    }

    /**
     * @return TokenInterface|Cluster|null
     */
    public function getParent(): ?TokenInterface
    {
        return $this->parent;
    }

    /**
     * @param TokenInterface|null $parent
     */
    public function setParent(TokenInterface $parent = null)
    {
        $this->parent = $parent;
    }

    public function getPointer(): ?Pointer
    {
        return new Pointer($this->getOption('index'));
    }

    protected function buildTokenOptions(array $options = [])
    {
        if (!$this->parent) {
            $options = array_merge([
                'start' => 0,
                'end' => strlen($this->getText()),
            ], $options);
            $this->buildOptions($options);
            return;
        }

        /** @var int $offset */
        $parentOffset = $this->parent->getOption('start') ?: 0;
        $index = $this->parent->getTokens()->indexOf($this);

        /** @var TokenInterface $lastSibling */
        $lastSibling = $this->parent->getTokens()->offsetGet($index - 1);
        $lastSiblingEnd = $lastSibling ? $lastSibling->getOption('end') : null;

        $this->buildOptions(array_merge([
            'start' => $lastSiblingEnd,
            'end' => $lastSiblingEnd + strlen($this->text),
        ], $options));

        //        return array_map(function ($a, $key) use (&$offset)  {
//
//            $token = new Token(
//                $a,
//                [
//                    'index' => [...$this->options['index'], $key],
//                    'start' => $offset,
//                    'end' => $offset + strlen($a),
//                ],
//                $this
//            );
//
//            $offset += strlen($a) + 1;
//
//            return $token;
//        }, $values, array_keys($values));
    }
}
