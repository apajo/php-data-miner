<?php

namespace DataMinerTests;

use DataMiner\Miner;
use DataMiner\Model\Annotation\Model;
use DataMiner\Model\Annotation\Property\Property;
use DataMinerTests\Model\Ancestor;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

require __DIR__ . '/../vendor/autoload.php';

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new \ErrorException($errstr, $errno, 1, $errfile, $errline);
});


use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Kernels\Distance\Manhattan;
use Rubix\ML\Tokenizers\Sentence;

$tokenizer = new Sentence();

$data = [
//    ['aa00', [4, 3, 2]],
//    ['aa11', [1, 1, 0]],
//    ['bb11', [2, 2, 0]],
//    ['cc000', [3, 3, 0]],
//    ['dd10', [4, 4, 6]],
//    ['dd12', [4, 4, 2]],
//    ['dd13', [4, 4, 0]],
//    ['dd11', [4, 4, 0]],
//    ['cc111', [3, 3, 0]],
//    ['cc111', [3, 3, 0]],
];

$grps = 6;
$target = [3, 0, 1];
for ($i = 0; $i < 50; $i++) {
    $data[] = [(string)($i % $grps), [($i % $grps), rand(0, 9), rand(0, 9)]];
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
                }, $a[1]))]) . "\t";
        }, $data), $grps)
    )
)
);

$classifier = new KNearestNeighbors(3, false, new Manhattan());
$classifier->train(new Labeled(array_column($data, 1), array_column($data, 0)));


$predict = new Unlabeled([$target]);
print_r([
    'Target: ' . implode('.', $target) . ' -->> ',
    'Prediction: '. implode('', $classifier->predict($predict)),
    'Probability: ',
    $classifier->proba($predict)
]);
