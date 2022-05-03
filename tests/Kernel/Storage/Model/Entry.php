<?php

namespace PhpDataMinerTests\Kernel\Storage\Model;

use PhpDataMiner\Storage\Model\Entry as Base;
use PhpDataMiner\Storage\Model\ModelProperty;
use PhpDataMiner\Storage\Model\ModelPropertyInterface;
use PhpDataMiner\Storage\Model\Property;
use PhpDataMiner\Storage\Model\PropertyInterface;
use ReflectionObject;

/**
 * Description of Entry
 *
 * @author Andres Pajo
 */
class Entry extends Base
{
    public static function createProperty(): PropertyInterface
    {
        $new = new Property();
        $ref = new ReflectionObject($new); $prop = $ref->getProperty('id'); $prop->setAccessible(true); $prop->setValue($new, rand());
        return $new;
    }
}
