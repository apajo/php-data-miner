<?php

namespace DataMiner\Normalizer\Transformer;

use DataMiner\Helpers\OptionsBuilderTrait;
use Rubix\ML\Transformers\RegexFilter;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of AbstractFilter
 *
 * @author Andres Pajo
 */
abstract class AbstractFilter extends RegexFilter
{
    use OptionsBuilderTrait;

    function __construct (array $patterns, array $options = [])
    {
        $this->buildOptions($options);

        $this->patterns = $patterns;
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
}
