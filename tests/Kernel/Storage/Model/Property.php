<?php

namespace PhpDataMinerTests\Kernel\Storage\Model;

use PhpDataMiner\Storage\Model\LabelInterface;
use PhpDataMiner\Storage\Model\Property as Base;
use ReflectionObject;

/**
 * Description of Property
 *
 * @author Andres Pajo
 */
class Property extends Base
{
    public static function createLabel (): LabelInterface
    {
        $new =  new Label();
        $ref = new ReflectionObject($new); $prop = $ref->getProperty('id'); $prop->setAccessible(true); $prop->setValue($new, rand());
        return $new;
    }
}
