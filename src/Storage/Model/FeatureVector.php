<?php


namespace PhpDataMinerStorage\Model;

/**
 * Description of FeatureVector
 *
 * @author Andres Pajo
 */
class FeatureVector implements FeatureVectorInterface
{
    /**
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * @var string|null
     */
    protected ?string $value = null;

    /**
     * @var Property|null
     */
    protected ?Property $property = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): void
    {
        $this->property = $property;
    }

    public function getValue(): ?array
    {
        $parts = explode('.', $this->value);
        return array_map('intval', $parts);
    }

    public function setValue(?array $value = []): void
    {
        $this->value = $value ? implode('.', $value) : null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    function __toString()
    {
        return $this->value ?: '';
    }
}
