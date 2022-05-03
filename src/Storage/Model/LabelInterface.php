<?php

namespace PhpDataMiner\Storage\Model;


/**
 * Description of Label
 *
 * @author Andres Pajo
 */
interface LabelInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @param int|null $id
     * @return mixed
     */
    public function setId(int $id = null);

    /**
     * @return string|null
     */
    public function getValue(): ?string;

    /**
     * @param string|null $value
     * @return mixed
     */
    public function setValue(string $value = null);

    public function getText(): ?string;

    public function setText(?string $text): void;


    public function getModel(): ?ModelInterface;

    public function setModel(?ModelInterface $model);

    public function getEntry(): ?EntryInterface;

    public function setEntry(?EntryInterface $entry): void;
}
