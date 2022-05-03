<?php

namespace PhpDataMinerTests;


use PhpDataMiner\Manager;
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
use PhpDataMiner\Normalizer\Transformer\Section;
use PhpDataMinerTests\Helpers\Load;
use PhpDataMinerTests\Kernel\Storage\TestStorage;
use PhpDataMinerTests\Kernel\TestKernel;
use PhpDataMinerTests\Model\Invoice;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/debug.php';

chdir(__DIR__);
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

$miner = Manager::create(
    new Invoice(),
    $properties,
    new TestStorage(),
    $filters,
    []
);

$path = __DIR__ . '/training';
$file = $path . '/files.csv';
$files = $path . '/files';
$index = 0;

$loaded = new Load($file, $files, 5);
list($trains, $predicts) = $loaded->sliceList(1);

foreach ($trains as $index => $train) {
    $filePath = $files . '/' . $train['file'];
    $content = shell_exec('pdftotext -layout ' . $filePath . ' -');
    $entity = Invoice::createModel($train);

    $doc = $miner->normalize($content);

    $entry = $miner->train($entity, $doc);
    dump([$index, $entity->number]);
}
//dump($miner->getModel());


dump(['////////////////////////////////////////////////', '////////////////// PREDICTING //////////////////']);
foreach ($predicts as $index => $predict) {
    $entity = Invoice::createModel([]);

    $filePath = $files . '/' . $predict['file'];
    $content = shell_exec('pdftotext -layout ' . $filePath . ' -');
    $doc = $miner->normalize($content);

    $entry = $miner->predict($entity, $doc);
    dump('>>>>>>>>>>>>>>>>>>>>>>>>', $entity, $entry);
}
