<?php

namespace PhpDataMinerStorage\Builder;


/**
 * Description of Builder
 *
 * @author Andres Pajo
 */
class Results
{
    protected $types = [];

    function __construct (array $types = [])
    {
        $this->types = array_fill_keys(
            $types,
            0
        );
    }

    function increase (string $type)
    {
        $this->types[$type] = isset($this->types[$type]) ? $this->types[$type] : $this->types[$type] = 0;

        $this->types[$type] = $this->types[$type] + 1;
    }

    function serialize ()
    {
        return implode("\n", array_map(function ($count, $type) {
            return $type . ': ' .  $count;
        }, $this->types, array_keys($this->types)));
    }
}
