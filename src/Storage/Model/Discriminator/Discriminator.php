<?php

namespace PhpDataMiner\Storage\Model\Discriminator;

class Discriminator implements DiscriminatorInterface
{
    /**
     * @var array|null
     */
    protected ?array $discriminator = null;

    /**
     * @param array|string $discriminator
     */
    function __construct($discriminator)
    {
        if (is_string($discriminator)) {
            $discriminator = explode('.', $discriminator);
        }

        $this->discriminator = $discriminator;
    }

    public function getString(): ?string
    {
        if (!$this->discriminator) {
            return null;
        }

        return implode('.', $this->getArray());
    }

    public function getArray(): ?array
    {
        if (!$this->discriminator) {
            return null;
        }

        return array_map(function ($a) {
            if (!is_numeric($a)) {
                return '%';
            }

            return intval($a);

        }, $this->discriminator);
    }

    public function matches(DiscriminatorInterface $discriminator): bool
    {
        $a = $discriminator->getArray();
        $b = $this->getArray();

        if (count($a ?: []) !== count($b ?: [])) {
            return false;
        }

        for ($i = 0; $i < count($a); $i++) {
            if ($a[$i] !== $b[$i] && !array_intersect(['%', '*', null], [$a[$i], $b[$i]])) {
                return false;
            }
        }

        return true;
    }

    function __toString()
    {
        return $this->getString();
    }
}
