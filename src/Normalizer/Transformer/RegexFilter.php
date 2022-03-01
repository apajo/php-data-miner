<?php

namespace PhpDataMiner\Normalizer\Transformer;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of RegexFilter
 *
 * @author Andres Pajo
 */
class RegexFilter extends AbstractFilter
{
    /**
     * @var array
     */
    protected array $patterns = [];

    /**
     * @var string|array
     */
    protected $replace;

    function __construct (array $patterns, array $options = [])
    {
        parent::__construct($patterns, $options);
    }


    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

    public function transform(array &$samples) : void
    {
        if (empty($this->patterns)) {
            return;
        }

        $this->filter($samples);
    }

    protected function filter(array &$samples) : void
    {
        foreach ($samples as $key => $value) {
            foreach ($this->patterns as $pattern => $replace) {
                $samples[$key] = preg_replace($pattern, $replace, $value);
            }
        }
    }
}
