<?php


namespace PhpDataMiner\Storage\Model;

/**
 * Class ModelProperty
 * @package PhpDataMiner\Storage\Model
 */
class ModelProperty implements ModelPropertyInterface
{
    /**
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * @var string
     */
    protected ?string $name = null;

    /**
     * @var string|null
     */
    protected $kernel_state = null;

    /**
     * @var Model|null
     */
    protected ?Model $model = null;

    public function getKernelState(): ?string
    {
        return $this->kernel_state;
    }

    public function setKernelState(?string $kernelState): void
    {
        $this->kernel_state = $kernelState;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getModel(): ?ModelInterface
    {
        return $this->model;
    }

    public function setModel(?ModelInterface $model): void
    {
        $this->model = $model;
    }

}
