<?php

namespace PhpDataMinerTests\Kernel;

use PhpDataMiner\Kernel\AbstractKernel;
use PhpDataMiner\Kernel\KernelInterface;
use PhpDataMiner\Model\Property\PropertyInterface;
use PhpDataMiner\Normalizer\Document\Document;
use PhpDataMiner\Normalizer\Document\Pointer;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;
use PhpDataMiner\Storage\Model\ModelInterface;
use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Kernels\Distance\Manhattan;

/**
 * Description of AbstractKernel
 *
 * @author Andres Pajo
 */
class TestKernel extends AbstractKernel implements KernelInterface
{
    function __construct ()
    {
        parent::__construct();
        $this->kernel = new KNearestNeighbors(3, false, new Manhattan());
    }

    public function predict (ModelInterface $model, PropertyInterface $property, Document $doc): ?TokenInterface
    {
        $labels = $this->groupByVectors($model, $property);

        foreach ($labels as $index => $entries ) {
            $result = $doc->getTraverser()->getValue(
                new Pointer(explode('.', $index)),
                $property
            );

            if (!$result) {
                continue;
            }

            $value = $property->normalize($result->getValue(), null);

            if (!$value) {
                continue;
            }

            return $result;
        }

        return null;
    }

    public function groupByVectors (ModelInterface $model, PropertyInterface $property): array
    {
        $labels = [];

        foreach ($model->getEntries() as $entry) {
            $prop = $entry->getProperty($property->getPropertyPath());

            if (!$prop) {
                continue;
            }

            $vec = $this->dataset->getFeatureVector($property, $prop);

            $value = implode('.', $vec);//$prop->getLabel()->getValue();

            if (!isset($labels[$value])){
                $labels[$value] = 0;
            }

            $labels[$value]++;// = $entry->getId();
        }

        uksort($labels, function (string $a, string $b) use ($labels) {
            return ($labels[$b]) - ($labels[$a]);
        });

        return $labels;
    }
}
