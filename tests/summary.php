<?php

namespace DataMinerTests;

use PhpDataMiner\DataMiner;
use PhpDataMiner\Model\Property\DateProperty;
use PhpDataMiner\Model\Property\FloatProperty;
use PhpDataMiner\Model\Property\IntegerProperty;
use PhpDataMiner\Model\Property\Property;
use PhpDataMiner\Normalizer\Tokenizer\WordTree;
use PhpDataMiner\Normalizer\Transformer\ColonFilter;
use PhpDataMiner\Normalizer\Transformer\DateFilter;
use PhpDataMiner\Normalizer\Transformer\PriceFilter;
use PhpDataMiner\Normalizer\Transformer\Section;
use PhpDataMiner\Storage\Summary\Entry;
use PhpDataMiner\Storage\Summary\Model;
use DataMinerTests\Kernel\Storage\TestStorage;
use DataMinerTests\Kernel\TestKernel;
use DataMinerTests\Model\Ancestor;

require __DIR__.'/../vendor/autoload.php';

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new \ErrorException($errstr, $errno, 1, $errfile, $errline);
});

$entity = Ancestor::createModel();

$kernel = new TestKernel();
$storage = new TestStorage();

$miner = DataMiner::create(
    $kernel,
    $entity,
    [
        'storage' => $storage,
    ]
);

$summary = new Model($storage);


$summary->build($miner->getModel());
