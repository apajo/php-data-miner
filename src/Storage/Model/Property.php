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
    protected ?ModelProperty $model_property = null;

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

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->model_property ? $this->model_property->getName() : null;
    }

    public function getModelProperty(): ?ModelProperty
    {
        return $this->model_property;
    }

    public function setModelProperty(?ModelProperty $model_property): void
    {
        $this->model_property = $model_property;
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

        $label = $this::createLabel();
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

    public static function createFeature (): FeatureInterface
    {
        return new Feature();
    }
}
