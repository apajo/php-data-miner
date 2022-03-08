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
use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Estimator;
use Rubix\ML\Kernels\Distance\Manhattan;
use Rubix\ML\Persisters\Persister;

/**
 * Description of AbstractKernel
 *
 * @author Andres Pajo
 */
abstract class AbstractKernel implements KernelInterface
{
    /**
     * @var DatasetHelper
     */
    protected $dataset;

    /**
     * @var Estimator|Persister
     */
    protected $kernel;

    function __construct ()
    {
        $this->dataset = new DatasetHelper();
    }


    /**
     * @param ModelInterface $model
     * @param Document $doc
     * @param PropertyInterface $property
     * @return TokenInterface|null
     */
    public function predict (ModelInterface $model, PropertyInterface $property, Document $doc): ?TokenInterface
    {

    }

    /**
     * @param EntryInterface $entry
     * @param PropertyInterface $property
     */
    public function train (EntryInterface $entry, PropertyInterface $property)
    {
        $prop = $entry->getProperty($property->getPropertyPath());
        $samples = $this->dataset->buildLabeledDataset($property, $prop);

        $this->kernel->train($samples);
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
