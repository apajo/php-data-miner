<?php

namespace PhpDataMiner\Storage\Model;


/**
 * Class ModelProperty
 * @package PhpDataMiner\Storage\Model
 */
interface ModelPropertyInterface
{
    public function getKernelState(): ?string;

    public function setKernelState(?string $kernelState): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function getModel(): ?ModelInterface;

    public function setModel(?ModelInterface $model): void;
}
