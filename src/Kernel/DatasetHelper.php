<?php

namespace PhpDataMiner\Kernel;

use PhpDataMiner\Model\Property\FlattenFeatureVectors;
use PhpDataMiner\Storage\Model\FeatureInterface;
use PhpDataMiner\Storage\Model\PropertyInterface as StoragePropertyInterface;
use PhpDataMiner\Model\Property\PropertyInterface as ModelPropertyInterface;
use Rubix\ML\Datasets\Labeled;

class DatasetHelper
{
    public function buildLabeledDataset(StoragePropertyInterface $property, ModelPropertyInterface $modelProperty): Labeled
    {
        $label = $property->getLabel()->getValue();

        $vectors = $this->getFeatureVector($modelProperty, $property);

        if ($modelProperty instanceof FlattenFeatureVectors) {
            $vectors = array_merge($vectors);
        }

        return new Labeled([$vectors], [$label]);
    }

    public function getFeatureVector(ModelPropertyInterface $modelProperty, StoragePropertyInterface $property): array
    {
        $vectors = $property->getFeatures()->map(function (FeatureInterface $a) use ($modelProperty)  {
            return $this->buildVector($modelProperty, $a);
        })->toArray();

        if ($modelProperty instanceof FlattenFeatureVectors) {
            $vectors = array_merge(...$vectors);
        }

        return $vectors;
    }

    protected function buildVector (ModelPropertyInterface $modelProperty, FeatureInterface $model): array
    {
        $feature = $modelProperty->getFeature($model->getName());

        return array_map(function (float $a) use ($modelProperty, $feature) {
            return $a * $feature->getWeight();
        }, $model->getValue());
    }
}
