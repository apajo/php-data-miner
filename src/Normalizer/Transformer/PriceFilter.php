<?php

namespace PhpDataMinerNormalizer\Transformer;

/**
 * Description of PriceFilter
 *
 * @author Andres Pajo
 */
class PriceFilter extends ValueUnitFilter
{
    protected function getUnitSamples (string $locale = null): array
    {
        return [
            '€', 'eur',
            '$', 'dollar',
            '£', 'nael',
            'kroon'
        ];
    }
}
