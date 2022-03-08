<?php

namespace PhpDataMiner\Storage\Model;


/**
 * Description of Feature
 *
 * @author Andres Pajo
 */
interface FeatureInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return Property|null
     */
    public function getProperty(): ?Property;

    /**
     * @param Property|null $property
     */
    public function setProperty(?Property $property): void;

    /**
     * @return array|null
     */
    public function getValue(): ?array;

    /**
     * @param array|null $value
     */
    public function setValue(?array $value = []): void;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;
}
