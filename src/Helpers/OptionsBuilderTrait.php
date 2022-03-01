<?php

namespace PhpDataMinerHelpers;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of OptionsBuilderTrait
 *
 * @author Andres Pajo
 */
trait OptionsBuilderTrait
{
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

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
