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
     * @var string
     */
    protected ?string $name = null;

    /**
     * @var EntryInterface|null
     */
    protected ?EntryInterface $entry = null;

    /**
     * @var Label|null
     */
    protected ?Label $label = null;

    /**
     * @var Collection|FeatureVectorInterface[]
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEntry(): ?EntryInterface
    {
        return $this->entry;
    }
    
    public function setEntry(?EntryInterface $entry = null)
    {
        $this->entry = $entry;
    }

    public function getLabel(): ?LabelInterface
    {
        return $this->label;
    }

    public function setLabel(?LabelInterface $label): void
    {
        $this->label = $label;
    }

    /**
     * @return FeatureVectorInterface[]|Collection
     */
    public function getFeatureVectors(): Collection
    {
        return $this->features;
    }

    public function addFeatureVector(FeatureVectorInterface $propertyFeature)
    {
        $this->features->add($propertyFeature);
        $propertyFeature->setProperty($this);
    }

    public function removeFeatureVector(FeatureVectorInterface $propertyFeature)
    {
        $this->features->removeElement($propertyFeature);
        $propertyFeature->setProperty(null);
    }
}
