<?php

namespace PhpDataMinerTests\Kernel\Storage;

use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Storage\Model\ModelInterface;
use PhpDataMiner\Storage\StorageInterface;
use PhpDataMiner\Storage\StorageTrait;
use ReflectionObject;
use PhpDataMinerTests\Kernel\Storage\Model\Model;

/**
 * Description of Miner
 *
 * @author Andres Pajo
 */
class TestStorage implements StorageInterface
{
    use StorageTrait;

    function __construct ()
    {
        $this->path = realpath(__DIR__.'/../../../var/cache/') . '/storage';
    }

    public function save(ModelInterface $model): bool
    {
        return (bool)file_put_contents($this->path, serialize($model));
    }

    public function load($entity, array $options = []): ModelInterface
    {
        if (!is_file($this->path)) {
            return $this->getModel($entity);
        }

        $content = file_get_contents($this->path);
        $model = unserialize($content);

        if (!$model) {
            $model = new Model();
        }

        return $model;
    }

    public function getModel($entity): ?ModelInterface
    {
        $model = new Model();
        $ref = new ReflectionObject($model); $prop = $ref->getProperty('id'); $prop->setAccessible(true); $prop->setValue($model, rand());
        $model->setModel(get_class($entity));

        return $model;
    }

    public function getLabelValue (Token $token)
    {
        return implode('.', $token->getPointer()->get());
    }
}
