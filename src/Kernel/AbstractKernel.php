<?php

namespace PhpDataMiner\Kernel;

use Core\BaseBundle\Entity\Miner\StorageFeature;
use PhpDataMiner\Model\Property\PropertyInterface;
use PhpDataMiner\Normalizer\Document\Document;
use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use PhpDataMiner\Storage\Model\Entry;
use PhpDataMiner\Storage\Model\EntryInterface;
use PhpDataMiner\Storage\Model\Feature;
use PhpDataMiner\Storage\Model\ModelInterface;
use PhpDataMiner\Storage\Model\PropertyInterface as StoragePropertyInterface;
use Doctrine\Common\Collections\Collection;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Estimator;
use Rubix\ML\Persisters\Persister;

/**
 * Description of AbstractKernel
 *
 * @author Andres Pajo
 */
abstract class AbstractKernel implements KernelInterface
{
    /**
     * @var Estimator|Persister
     */
    protected $kernel;

    /**
     * @param ModelInterface $model
     * @param Document $doc
     * @param PropertyInterface $property
     * @return TokenInterface|null
     */
    public function predict (ModelInterface $model, PropertyInterface $property, Document $doc): ?TokenInterface
    {
        $storageProperty = $model->getProperty($property->getPropertyPath());

        $dataset = new Unlabeled([
            [0, 1, 2, 3, 4]
        ]);

        //$this->kernel->predict($dataset);
    }

    /**
     * @param Entry[]|Collection $entries
     */
    public function train (EntryInterface $entry, PropertyInterface $property)
    {
        $storageProperty = $entry->getProperty();
        $samples = $entry->getModel()->resolveSamples($property);

        $dataset = new Labeled(array_column($samples, 'data'), array_column($samples, 'label'));

        //$this->kernel->train($dataset);
    }

    /**
     * Build property feature vector data
     *
     * @param StoragePropertyInterface $model
     * @param Token $token
     * @param PropertyInterface $property
     */
    public function buildVectors (StoragePropertyInterface $model, Token $token, PropertyInterface $property)
    {
        foreach ($property->getFeatures() as $key => $feature) {
            $vector = $model->getFeatures()->offsetExists($key) ?
                $model->getFeatures()->offsetGet($key) : null;

            if (!$vector) {
                $vector = new Feature();
                $model->addFeature($vector);
                $vector->setName($key);
            }

            $feature->vectorize($vector, $token);
        }
    }
}
