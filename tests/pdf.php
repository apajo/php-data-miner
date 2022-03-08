<?php

namespace PhpDataMinerTests;


use PhpDataMiner\DataMiner;
use PhpDataMiner\Model\Property\DateProperty;
use PhpDataMiner\Model\Property\Feature\DefaultFeature;
use PhpDataMiner\Model\Property\Feature\WordTreeFeature;
use PhpDataMiner\Model\Property\FloatProperty;
use PhpDataMiner\Model\Property\IntegerProperty;
use PhpDataMiner\Model\Property\Property;
use PhpDataMiner\Model\Property\Provider;
use PhpDataMiner\Model\Property\Registry;
use PhpDataMiner\Normalizer\Tokenizer\WordTree;
use PhpDataMiner\Normalizer\Transformer\ColonFilter;
use PhpDataMiner\Normalizer\Transformer\DateFilter;
use PhpDataMiner\Normalizer\Transformer\PriceFilter;
use PhpDataMiner\Normalizer\Transformer\Section;
use PhpDataMinerTests\Kernel\Storage\TestStorage;
use PhpDataMinerTests\Kernel\TestKernel;
use PhpDataMinerTests\Model\Ancestor;

require __DIR__.'/../vendor/autoload.php';

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new \ErrorException($errstr, $errno, 1, $errfile, $errline);
});

$entity = Ancestor::createModel();

$kernel = new TestKernel();
$feature = new WordTreeFeature();
$feature2 = new DefaultFeature();

$provider = new Provider(new Registry([
    new FloatProperty($kernel, [$feature, $feature2]),
    new IntegerProperty($kernel, [$feature, $feature2]),
    new DateProperty($kernel, [$feature, $feature2]),
    new Property($kernel, [$feature, $feature2]),
]));


$miner = DataMiner::create(
    $entity,
    [
        'storage' => new TestStorage(),
        'properties' => $provider
    ]
);

$source = shell_exec('pdftotext -layout tests/kopra.pdf -');

// perform some mutations in the content
$content = str_replace('280740', $entity->id, $source);
$content = str_replace('15 november 2021', $entity->date->format('d-m-Y'), $content);

$rows = explode("\n", $content);
unset($rows[rand(0, count($rows) - 1)]);
$content = implode("\n", $rows);


$doc = $miner->normalize($content, [
    'filters' => [
        DateFilter::class,
        PriceFilter::class,
        ColonFilter::class,
        Section::class,
        //NltkToken::class,
        WordTree::class,
    ]
]);


//$doc->_dump();

$trained = $miner->train($entity, $doc);
dump($trained);

$new = new Ancestor();
$resolve = $miner->predict($new, $doc);
dump($new);

