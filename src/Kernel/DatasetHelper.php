<?php

namespace PhpDataMiner\Kernel;

use PhpDataMiner\Model\Property\FlattenFeatureVectors;
use PhpDataMiner\Storage\Model\FeatureInterface;
use PhpDataMiner\Storage\Model\PropertyInterface as Model;
use PhpDataMiner\Model\Property\PropertyInterface;
use Rubix\ML\Datasets\Labeled;

class DatasetHelper
{
    public function buildLabeledDataset(PropertyInterface $property, Model $model): Labeled
    {
        $label = $model->getLabel()->getValue();

        $vectors = $this->getFeatureVector($property, $model);

        if ($property instanceof FlattenFeatureVectors) {
            $vectors = array_merge($vectors);
        }

        return new Labeled([$vectors], [$label]);
    }

    public function getFeatureVector(PropertyInterface $property, Model $model): array
    {
        $vectors = $model->getFeatures()->map(function (FeatureInterface $a) use ($property)  {
            return $this->buildVector($property, $a);
        })->toArray();

        if ($property instanceof FlattenFeatureVectors) {
            $vectors = array_merge(...$vectors);
        }

        return $vectors;
    }

    protected function buildVector (PropertyInterface $property, FeatureInterface $model): array
    {
        $feature = $property->getFeature($model->getName());

        return array_map(function (float $a) use ($property, $feature) {
            return $a * $feature->getWeight();
        }, $model->getValue());
    }
}
