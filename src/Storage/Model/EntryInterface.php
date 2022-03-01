<?php

namespace PhpDataMinerStorage\Model;


use PhpDataMinerStorage\Model\Discriminator\DiscriminatorInterface;
use Doctrine\Common\Collections\Collection;

/**
 * Description of Entry
 *
 * @author Andres Pajo
 */
interface EntryInterface
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
     * @return ModelInterface|null
     */
    public function getModel(): ?ModelInterface;

    /**
     * @param ModelInterface|null $model
     */
    public function setModel(?ModelInterface $model): void;

    /**
     * @return DiscriminatorInterface|null
     */
    public function getDiscriminator(): ?DiscriminatorInterface;

    /**
     * @param DiscriminatorInterface|null $discriminator
     */
    public function setDiscriminator(?DiscriminatorInterface $discriminator = null): void;

    /**
     * @return PropertyInterface[]|Collection
     */
    public function getProperties(): Collection;

    /**
     * @return PropertyInterface
     */
    public function getProperty(string $property): ?PropertyInterface;

    /**
     * @param PropertyInterface $property
     * @return bool
     */
    public function addProperty(PropertyInterface $property);

    /**
     * @param PropertyInterface $property
     * @return bool
     */
    public function removeProperty(PropertyInterface $property);
}
