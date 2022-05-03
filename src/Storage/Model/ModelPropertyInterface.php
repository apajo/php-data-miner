<?php

namespace PhpDataMiner\Storage\Model;


/**
 * Class ModelProperty
 * @package PhpDataMiner\Storage\Model
 */
interface ModelPropertyInterface
{
    public function getKernel(): ?string;

    public function setKernel(?string $kernel): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function getModel(): ?ModelInterface;

    public function setModel(?ModelInterface $model): void;
}
