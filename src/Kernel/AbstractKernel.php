<?php

namespace PhpDataMiner\Kernel;

use PhpDataMiner\Model\Property\PropertyInterface as ModelPropertyInterface;
use PhpDataMiner\Normalizer\Document\Pointer;
use PhpDataMiner\Storage\Model\PropertyInterface as StoragePropertyInterface;
use PhpDataMiner\Normalizer\Document\Document;
use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use PhpDataMiner\Storage\Model\ModelInterface;
use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Estimator;
use Rubix\ML\Kernels\Distance\Manhattan;
use Rubix\ML\Learner;
use Rubix\ML\Persisters\Persister;
use Rubix\ML\Serializers\RBX;

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
     * @var Estimator|Persister|Learner
     */
    protected $kernel;

    function __construct ()
    {
        $this->dataset = new DatasetHelper();

        $this->kernel = new KNearestNeighbors(3, false, new Manhattan());
    }

    protected function process ($invokable, StoragePropertyInterface $property = null): KernelInterface
    {
        $this->kernel = new KNearestNeighbors(3, false, new Manhattan());
        $serializer = new RBX();

        $kernelState = $property && $property->getModelProperty()->getKernelState() ? $property->getModelProperty()->getKernelState()  : null;
        if ($kernelState) {
            $this->kernel = $serializer->deserialize($kernelState);
        }

        call_user_func_array($invokable, [

        ]);

        if ($property && $property->getModelProperty()) {
            $encoded = $serializer->serialize($this->kernel);

            $property->getModelProperty() && $property->getModelProperty()->setKernelState($encoded);
        }
    }

    public function train (StoragePropertyInterface $property, ModelPropertyInterface $modelProperty, Token $token, Document $doc)
    {
        /**
         * @param Estimator|Persister|Learner $estimator
         */
        $this->process(function (Learner $estimator) use ($property, $modelProperty) {
            $samples = $property->getEntry()->getModel()->resolveSamples($modelProperty);

            $dataset = new Labeled(
                array_map(function ($a) {
                    return array_map('intval', explode('.', $a));
                }, array_column($samples, 'data'))
                , array_column($samples, 'label')
            );

            $estimator->train($dataset);
        }, $property);
    }

    public function predict(StoragePropertyInterface $property, ModelPropertyInterface $modelProperty, Document $doc): ?TokenInterface
    {
        /**
         * @param Estimator|Persister|Learner $estimator
         */
        $result =  $this->process(function (Learner $estimator) use ($property, $modelProperty, $doc) {
            $labels = $this->groupByValues($property->getEntry()->getModel(), $modelProperty);

            foreach ($labels as $index => $entries ) {
                $result = $doc->getTraverser()->getValue(
                    new Pointer(explode('.', $index)),
                    $modelProperty
                );

                if (!$result) {
                    continue;
                }

                $value = $modelProperty->normalize($result->getValue(), null);

                if (!$value) {
                    continue;
                }

                return $result;
            }

            return null;
        }, $property);
dump($result);
        return null;

    }

    /**
     * Build property feature vector data
     *
     * @param StoragePropertyInterface $property
     * @param ModelPropertyInterface $modelProperty
     * @param Token $token
     */
    public function buildVectors (StoragePropertyInterface $property, ModelPropertyInterface $modelProperty, Token $token, Document $doc)
    {
        foreach ($modelProperty->getFeatures() as $key => $feature) {
            $vector = $property->getFeatures()->offsetExists($key) ?
                $property->getFeatures()->offsetGet($key) : null;

            if (!$vector) {
                $vector = $property::createFeature();
                $property->addFeature($vector);
                $vector->setName($key);
            }

            $feature->vectorize($vector, $token);
        }
    }
    public function groupByValues (ModelInterface $model, ModelPropertyInterface $target): array
    {
        $labels = [];

        foreach ($model->getEntries() as $entry) {
            $prop = $entry->getProperty($target->getPropertyPath());

            if (!$prop || !$prop->getLabel()) {
                continue;
            }

            $value = $prop->getLabel()->getValue();

            if (!isset($labels[$value])){
                $labels[$value] = [];
            }

            $labels[$value][] = $entry;
        }

        uksort($labels, function (string $a, string $b) use ($labels) {
            return count($labels[$b]) - count($labels[$a]);
        });

        return $labels;
    }
}
