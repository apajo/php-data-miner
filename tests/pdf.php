<?php

namespace PhpDataMinerTests;


use PhpDataMiner\DataMiner;
use PhpDataMiner\Model\Property\AbstractProperty;
use PhpDataMiner\Model\Property\DateProperty;
use PhpDataMiner\Model\Property\Feature\WordTreeFeature;
use PhpDataMiner\Model\Property\FloatProperty;
use PhpDataMiner\Model\Property\IntegerProperty;
use PhpDataMiner\Model\Property\Property;
use PhpDataMiner\Model\Property\Provider;
use PhpDataMiner\Model\Property\Registry;
use PhpDataMiner\Normalizer\Tokenizer\WordTree;
use PhpDataMiner\Normalizer\Transformer\ColonFilter;
use PhpDataMiner\Normalizer\Transformer\DateFilter;
use PhpDataMiner\Normalizer\Transformer\NumberFilter;
use PhpDataMiner\Normalizer\Transformer\PriceFilter;
use PhpDataMiner\Normalizer\Transformer\Section;
use PhpDataMinerTests\Helpers\Load;
use PhpDataMinerTests\Kernel\Storage\TestStorage;
use PhpDataMinerTests\Kernel\TestKernel;

use PhpDataMinerTests\Model\Invoice;

require __DIR__ . '/../vendor/autoload.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new \ErrorException($errstr, $errno, 1, $errfile, $errline);
});


$kernel = new TestKernel();
$feature = new WordTreeFeature();

$properties = new Provider(new Registry([
    new FloatProperty($kernel, [$feature], [new NumberFilter()]),
    new IntegerProperty($kernel, [$feature], [new NumberFilter()]),
    new DateProperty($kernel, [$feature], [new DateFilter()]),
    new Property($kernel, [$feature]),
]));
$filters = [
    new ColonFilter(),
    new Section(),
    //new NltkToken::class,
    new WordTree(),
];

$miner = DataMiner::create(
    new Invoice(),
    $properties,
    $filters,
    [
        'storage' => new TestStorage(),
    ]
);

$path = __DIR__ . '/training';
$file = $path . '/files.csv';
$files = $path . '/files';
$index = 0;

$loaded = new Load($file, $files, 50);
list($trains, $predicts) = $loaded->sliceList(4);

foreach ($trains as $index => $train) {
    $filePath = $files . '/' . $train['file'];
    $content = shell_exec('pdftotext -layout ' . $filePath . ' -');
    $entity = Invoice::createModel($train);

    $doc = $miner->normalize($content);

    $trainerd = $miner->train($entity, $doc);
    dump([$index, $entity->number]);
}

dump(['////////////////////////////////////////////////', '////////////////// PREDICTING //////////////////']);
foreach ($predicts as $index => $predict) {
    $entity = Invoice::createModel([]);

    $filePath = $files . '/' . $predict['file'];
    $content = shell_exec('pdftotext -layout ' . $filePath . ' -');
    $doc = $miner->normalize($content);

    $predicted = $miner->predict($entity, $doc);
    dump([$index, $predict, $entity]);
}
