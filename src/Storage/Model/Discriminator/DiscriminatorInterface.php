<?php

namespace PhpDataMiner\Storage\Model\Discriminator;

/**
 * Entry discriminator
 */
interface DiscriminatorInterface
{
    /**
     * @return string|null
     */
    public function getString(): ?string;

    /**
     * @return array|null
     */
    public function getArray(): ?array;

    public function matches(DiscriminatorInterface $discriminator): bool;
}
