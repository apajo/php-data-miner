<?php

namespace DataMinerTests;

use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Clusterers\KMeans;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Kernels\Distance\Manhattan;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Serializers\RBX;
use Rubix\ML\Tokenizers\Sentence;

require __DIR__ . '/../vendor/autoload.php';

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new \ErrorException($errstr, $errno, 1, $errfile, $errline);
});


$tokenizer = new Sentence();


function testData () {
    return [
        rand(0, 5),
        rand(0, 5),
        rand(0, 5),
    ];
}
$data = [
];

$grps = 10;
$target = [5, 0, 1];

for ($i = 0; $i < 100; $i++) {
    $item = [(string)(($i % $grps)), [rand(0, 9), rand(0, 9), rand(0, 9)]];

    if (rand(0, 1) === 0) {
        $item = [(string)(($i % $grps)), [5, 0, rand(0, 9)]];
    }

    $data[] = $item;
}


echo(
implode( "\n",
    array_map(
        function (array $a) {
            return implode(" -> ", $a);
        },
        array_chunk(array_map(function (array $a) {
            return implode(' ', ['['.$a[0].']', implode('.', array_map(function (string $b) {
                    return str_pad($b, 2, " ", STR_PAD_LEFT);
                }, $a[1]))]) . "  ";
        }, $data), $grps)
    )
)
);

$path = realpath(__DIR__ . '/../var/cache') . '/train';
if (is_file($path)) {
    $estimator = PersistentModel::load(new Filesystem($path), new RBX());
} else {
    $estimator = new PersistentModel(new KNearestNeighbors(3, false, new Manhattan()), new Filesystem($path), new RBX());
}

$estimator->train(new Labeled(array_column($data, 1), array_column($data, 0)));
$estimator->save();

$predict = new Unlabeled([$target]);
print_r([
    'Target: ' . implode('.', $target) . ' -->> ',
    'Prediction: '. implode('', $estimator->predict($predict)),
    'Probability: ',
    $estimator->proba($predict)
]);


