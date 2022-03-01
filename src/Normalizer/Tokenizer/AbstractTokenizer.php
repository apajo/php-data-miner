<?php

namespace PhpDataMiner\Normalizer\Tokenizer;

use PhpDataMiner\Helpers\OptionsBuilderTrait;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use Rubix\ML\Tokenizers\Tokenizer as Base;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of Tokenizer
 *
 * @author Andres Pajo
 */
abstract class AbstractTokenizer implements Base, TokenizerInterface
{
    use OptionsBuilderTrait;

    /**
     * @var string
     */
    protected string $pattern = '';

    function __construct (string $pattern, array $options = [])
    {
        $this->buildOptions($options);

        $this->pattern = $pattern;
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

    /**
     * Return the string representation of the object.
     *
     * @internal
     *
     * @return string
     */
    public function __toString() : string
    {
        return get_class($this);
    }

    /**
     * @param string $text
     * @return TokenInterface[]
     */
    public function tokenize(string $text): array
    {
        return preg_split($this->pattern, $text) ?: [];
    }
}
