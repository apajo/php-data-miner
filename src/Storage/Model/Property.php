<?php


namespace PhpDataMiner\Storage\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class Property implements PropertyInterface
{
    /**
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * @var EntryInterface|null
     */
    protected ?EntryInterface $entry = null;

    /**
     * @var Label|null
     */
    protected ?Label $label = null;

    /**
     * @var ModelProperty|null
     */
    private ?ModelProperty $modelProperty = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->modelProperty ? $this->modelProperty->getName() : null;
    }

    public function getModelProperty(): ?ModelProperty
    {
        return $this->modelProperty;
    }

    public function setModelProperty(?ModelProperty $modelProperty): void
    {
        $this->modelProperty === $modelProperty;
    }

    /**
     * @var Collection|FeatureInterface[]
     */
    protected Collection $features;

    public function __construct()
    {
        $this->features = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntry(): ?EntryInterface
    {
        return $this->entry;
    }

    public function setEntry(?EntryInterface $entry = null)
    {
        $this->entry = $entry;
    }

    public function getLabel(bool $create = false): ?LabelInterface
    {
        if ($this->label) {
            return $this->label;
        }

        if (!$create) {
            return null;
        }

        $label = self::createLabel();
        $label->setEntry($this->getEntry());
        $this->getEntry()->getModel()->addLabel($label);

        return $label;
    }

    public function setLabel(?LabelInterface $label): void
    {
        $this->label = $label;
    }

    /**
     * @return FeatureInterface[]|Collection
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(FeatureInterface $propertyFeature)
    {
        $this->features->add($propertyFeature);
        $propertyFeature->setProperty($this);
    }

    public function removeFeature(FeatureInterface $propertyFeature)
    {
        $this->features->removeElement($propertyFeature);
        $propertyFeature->setProperty(null);
    }

    public static function createLabel(): LabelInterface
    {
        return new Label();
    }
}
