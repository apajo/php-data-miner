<?php

namespace PhpDataMinerTests\Kernel\Storage\Model;

use PhpDataMiner\Storage\Model\Entry as Base;
use PhpDataMiner\Storage\Model\EntryInterface;
use PhpDataMiner\Storage\Model\PropertyInterface;
use ReflectionObject;

/**
 * Description of Entry
 *
 * @author Andres Pajo
 */
class Entry extends Base implements EntryInterface
{
    public static function createProperty(): PropertyInterface
    {
        $new = new Property();
        $ref = new ReflectionObject($new); $prop = $ref->getProperty('id'); $prop->setAccessible(true); $prop->setValue($new, rand());
        return $new;
    }

}
