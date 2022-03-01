<?php

namespace PhpDataMinerNormalizer\Transformer;

/**
 * Description of ColonFilter
 *
 * @author Andres Pajo
 */
class NumberFilter extends RegexFilter
{
    function __construct (array $options = [])
    {
        parent::__construct(['/(?!\s)(,)+/m' => '.'], $options);
    }
}
