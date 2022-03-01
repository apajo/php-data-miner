<?php

namespace PhpDataMiner\Normalizer\Transformer;

/**
 * Description of Section
 *
 * @author Andres Pajo
 */
class Section extends RegexFilter
{
    function __construct (array $options = [])
    {
        parent::__construct(['/(\s{2,})/m' => "\t"], $options);
    }
}
