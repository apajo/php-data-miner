<?php

namespace PhpDataMinerTests;


use PhpDataMiner\DataMiner;
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
use PhpDataMiner\Storage\Model\FeatureInterface;
use PhpDataMiner\Storage\Model\PropertyInterface;
use PhpDataMinerTests\Helpers\Load;
use PhpDataMinerTests\Kernel\Storage\TestStorage;
use PhpDataMinerTests\Kernel\TestKernel;
use PhpDataMinerTests\Model\Invoice;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;

require __DIR__ . '/../vendor/autoload.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new \ErrorException($errstr, $errno, 1, $errfile, $errline);
});

VarDumper::setHandler(function ($var) {
    $cloner = new VarCloner();
    $dumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();
    $clone = $cloner->cloneVar($var);
    $dumper->dump($clone->withMaxDepth(4));
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

$loaded = new Load($file, $files, 10);
list($trains, $predicts) = $loaded->sliceList(4);

foreach ($trains as $index => $train) {
    $filePath = $files . '/' . $train['file'];
    $content = shell_exec('pdftotext -layout ' . $filePath . ' -');
    $entity = Invoice::createModel($train);

    $doc = $miner->normalize($content);

    $entry = $miner->train($entity, $doc);
    //dump([$index, $entity->number]);
    //dump($entry->getProperties()->toArray());
//    dump($entry->getProperties()->map(function (PropertyInterface $a) {
//        dump($a);
////        return $a->getFeatures()->map(function (FeatureInterface $b) {
////            return $b->setValue();
////        });
//    }));
    dd($entry->getModel());
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
