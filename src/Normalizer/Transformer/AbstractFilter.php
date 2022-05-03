<?php

namespace PhpDataMiner\Normalizer\Transformer;

use PhpDataMiner\Helpers\OptionsBuilderTrait;
use Rubix\ML\Transformers\RegexFilter;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of AbstractFilter
 *
 * @author Andres Pajo
 */
abstract class AbstractFilter extends RegexFilter implements FilterInterface
{
    function __construct (array $patterns, array $options = [])
    {
        $this->buildOptions($options);

        $this->patterns = $patterns;
    }
    /**
     * @var array
     */
    protected array $options = [];

    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    protected function buildOptions (array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function configureOptions(OptionsResolver $resolver)
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
}
