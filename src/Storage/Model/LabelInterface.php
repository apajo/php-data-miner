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

    /**
     * @return string|null
     */
    public function getProperty(): ?string;

    /**
     * @param string|null $property
     */
    public function setProperty(?string $property): void;
}
