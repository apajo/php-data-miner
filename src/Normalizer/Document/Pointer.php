<?php

namespace PhpDataMinerNormalizer\Document;

use ArrayIterator;

/**
 * Description of Pointer
 *
 * @author Andres Pajo
 */
class Pointer
{
    /**
     * @var array|null
     */
    protected ?array $value = null;

    /**
     * @var int|null
     */
    private ?int $resolution = null;

    public function getResolution(): ?int
    {
        return $this->resolution;
    }

    public function setResolution(?int $resolution): void
    {
        $this->resolution = $resolution;
    }
    
    /**
     * @param array|null $value
     */
    function __construct (array $value = null, $resolution = null)
    {
        $this->value = $value;
        $this->resolution = $resolution;
    }

    public function get ()
    {
        return $this->value;
    }

    public function iterator ()
    {
        return new ArrayIterator($this->value);
    }

    public function __toString ()
    {
        return implode('.', $this->value);
    }
}
