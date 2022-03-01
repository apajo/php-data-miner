<?php

namespace PhpDataMinerTests\Kernel\Storage;

use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Storage\Model\Entry;
use PhpDataMiner\Storage\Model\Label;
use PhpDataMiner\Storage\Model\Model;
use PhpDataMiner\Storage\Model\ModelInterface;
use PhpDataMiner\Storage\Model\Property;
use PhpDataMiner\Storage\StorageInterface;
use PhpDataMiner\Storage\StorageTrait;

/**
 * Description of Miner
 *
 * @author Andres Pajo
 */
class TestStorage implements StorageInterface
{
    use StorageTrait;


    protected $labelModel = Label::class;

    protected $entryModel = Entry::class;

    protected $propertyModel = Property::class;

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
            $model = new TestModel();
        }

        return $model;
    }

    public function getModel($entity): ?ModelInterface
    {
        $model = new TestModel();
        $model->setModel(get_class($entity));

        return $model;
    }

    public function getLabelValue (Token $token)
    {
        return implode('.', $token->getPointer()->get());
    }
}
