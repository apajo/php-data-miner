<?php

namespace PhpDataMiner\Storage\Model;

/**
 * Description of Label
 *
 * @author Andres Pajo
 */
class Label implements LabelInterface
{
    /**
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * @var string|null
     */
    protected ?string $text = null;

    /**
     * @var string|null
     */
    protected ?string $value = null;

    /**
     * @var ModelInterface|null
     */
    protected ?ModelInterface $model = null;

    /**
     * @var EntryInterface|null
     */
    protected ?EntryInterface $entry = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id = null)
    {
        $this->id = $id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value = null)
    {
        $this->value = $value;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text = null): void
    {
        $this->text = $text;
    }

    public function getModel(): ?ModelInterface
    {
        return $this->model;
    }

    public function setModel(?ModelInterface $model)
    {
        $this->model = $model;
    }

    public function getEntry(): ?EntryInterface
    {
        return $this->entry;
    }

    public function setEntry(?EntryInterface $entry): void
    {
        $this->entry = $entry;
    }
}
