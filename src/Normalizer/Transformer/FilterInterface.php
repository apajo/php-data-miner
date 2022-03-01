<?php

namespace DataMiner\Normalizer\Transformer;


use Rubix\ML\Transformers\Transformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of AbstractFilter
 *
 * @author Andres Pajo
 */
interface FilterInterface extends Transformer
{
    public function configureOptions(OptionsResolver $resolver);
}
