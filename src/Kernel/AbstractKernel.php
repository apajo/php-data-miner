<?php

namespace PhpDataMiner\Kernel;

use PhpDataMiner\Model\Property\PropertyInterface as ModelPropertyInterface;
use PhpDataMiner\Storage\Model\PropertyInterface as StoragePropertyInterface;
use PhpDataMiner\Normalizer\Document\Document;
use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use PhpDataMiner\Storage\Model\Feature;
use PhpDataMiner\Storage\Model\ModelInterface;
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
     * @param ModelPropertyInterface $modelProperty
     * @param Document $doc
     * @return TokenInterface|null
     */
    public function predict (StoragePropertyInterface $property, ModelPropertyInterface $modelProperty, Document $doc): ?TokenInterface
    {
        return null;
    }


    public function train (StoragePropertyInterface $property, ModelPropertyInterface $modelProperty)
    {
        $samples = $this->dataset->buildLabeledDataset($modelProperty, $property->getEntry()->getModel());

        $this->kernel->train($samples);
    }

    /**
     * Build property feature vector data
     *
     * @param StoragePropertyInterface $model
     * @param Token $token
     * @param ModelPropertyInterface $property
     */
    public function buildVectors (StoragePropertyInterface $model, Token $token, ModelPropertyInterface $modelProperty)
    {
        foreach ($modelProperty->getFeatures() as $key => $feature) {
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
