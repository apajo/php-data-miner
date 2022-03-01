<?php

namespace PhpDataMinerKernel;

use Core\BaseBundle\Entity\Miner\StorageFeature;
use PhpDataMinerModel\Property\PropertyInterface;
use PhpDataMinerNormalizer\Document\Document;
use PhpDataMinerNormalizer\Tokenizer\Token\Token;
use PhpDataMinerNormalizer\Tokenizer\Token\TokenInterface;
use PhpDataMinerStorage\Model\Entry;
use PhpDataMinerStorage\Model\EntryInterface;
use PhpDataMinerStorage\Model\ModelInterface;
use PhpDataMinerStorage\Model\PropertyInterface as StoragePropertyInterface;
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
            $vector = $model->getFeatureVectors()->offsetExists($key) ?
                $model->getFeatureVectors()->offsetGet($key) : null;

            if (!$vector) {
                $vector = new StorageFeature();
                $model->addFeatureVector($vector);
                $vector->setName($key);
            }

            $feature->vectorize($vector, $token);
        }
    }
}
