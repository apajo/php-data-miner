<?php

namespace PhpDataMinerTests\Kernel\Storage\Model;

use PhpDataMiner\Storage\Model\Discriminator\Discriminator;
use PhpDataMiner\Storage\Model\Discriminator\DiscriminatorInterface;
use PhpDataMiner\Storage\Model\EntryInterface;
use PhpDataMiner\Storage\Model\LabelInterface;
use PhpDataMiner\Storage\Model\Model as Base;
use PhpDataMiner\Storage\Model\ModelInterface;
use PhpDataMiner\Storage\Model\ModelPropertyInterface;
use PhpDataMinerTests\Model\Invoice;
use ReflectionObject;

/**
 * Description of Model
 *
 * @author Andres Pajo
 */
class Model extends Base
{
    /**
     * @param Invoice $value
     * @return DiscriminatorInterface
     */
    public static function createEntryDiscriminator($value): DiscriminatorInterface
    {
        return new Discriminator([
            $value->number,
        ]);
    }

    public static function createModel (): ModelInterface
    {
        $new = new Model();
        $ref = new ReflectionObject($new); $prop = $ref->getProperty('id'); $prop->setAccessible(true); $prop->setValue($new, rand());
        return $new;
    }

    public static function createProperty (): ModelPropertyInterface
    {
        $new = new ModelProperty();
        $ref = new ReflectionObject($new); $prop = $ref->getProperty('id'); $prop->setAccessible(true); $prop->setValue($new, rand());
        return $new;
    }

    public static function createEntry (): EntryInterface
    {
        $new =  new Entry();
        $ref = new ReflectionObject($new); $prop = $ref->getProperty('id'); $prop->setAccessible(true); $prop->setValue($new, rand());
        return $new;
    }

    public static function createLabel (): LabelInterface
    {
        $new =  new Label();
        $ref = new ReflectionObject($new); $prop = $ref->getProperty('id'); $prop->setAccessible(true); $prop->setValue($new, rand());
        return $new;
    }
}
