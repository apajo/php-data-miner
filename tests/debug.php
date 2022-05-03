<?php

namespace DataMinerTests;

use Symfony\Component\VarDumper\Cloner\Stub;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;

class Debug {
    public static function ClassCaster($object, $array, Stub $stub, $isNested, $filter)
    {
    }
}

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new \ErrorException($errstr, $errno, 1, $errfile, $errline);
});

VarDumper::setHandler(function ($var) {
    $cloner = new VarCloner([
        //'object' => [Debug::class, 'ClassCaster']
    ]);


    $dumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();

    $clone = $cloner->cloneVar($var);
    $dumper->dump ($clone->withMaxDepth(3));
});
