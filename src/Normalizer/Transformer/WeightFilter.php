<?php

namespace PhpDataMinerNormalizer\Transformer;

/**
 * Description of WeightFilter
 *
 * @author Andres Pajo
 */
class WeightFilter extends ValueUnitFilter
{
    protected function getUnitSamples (string $locale = null): array
    {
        return [
            'kg', 'kilo',
            'tonn', 'naela'
        ];
    }
}
