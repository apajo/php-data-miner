PHP Data Miner
============

### Introduction

[php-data-miner](https://github.com/apajo/php-data-miner) extracts data
from structured data formats (such as PDF documents). 

### Prerequisites

* Unix-like OS
* GNU Make
* PHP (>=7.4)
* NodeJS (>=8)

### Installation

```bash 
$ sudo apt-get install make gcc gfortran php-dev libopenblas-dev liblapacke-dev re2c build-essential
```

### Usage

#### Annotation

Annotate your model with `@Model()` and properties with `@Property()` annotations.

```php
use PhpDataMiner\Model\Annotation\Model;
use PhpDataMiner\Model\Annotation\Property;

/**
 * @Model()
 */
class Invoice
{
    /**
     * @var string
     * @Property()
     */
    protected string $number;
}
```


#### Create your miner

```php
$miner = $this->miner->create($entity, [
    'storage' => new CustomStorage(),
    'property_types' => [
        new FloatProperty(),
        new IntegerProperty(),
        new DateProperty(),
        new Property(),
    ]
]);

$pdfContents = shell_exec('pdftotext -layout incoice.pdf -');

$doc = $miner->normalize($pdfContents, [
    'filters' => [
        DateFilter::class,
        ColonFilter::class,
        Section::class,
        WordTree::class,
    ]
]);

$entity = new Invoice();
```

> You need to have __pdftotext__ installed to read PDF contents like shown above 

* filters (or transformers) transform and normalize the content
* __WordTree__ filter is as special kind of tokenizer for nesting and grouping the contents (by rows, columns, sentences etc)

> It's recommended that you place your tokenizers as the last ones in the filters list

#### Training

Train your model with data you've already entered (supervised learning):

```php
...

$trainedProperties = $miner->train($entity, $doc);
```

#### Mining (or predicting)

Apply predicted data to your model:

```php
...

$predictedProperties = $miner->predict($entity, $doc);
```

#### Entry discrimination (filtering)

Edit your storage model `PhpDataMiner\Storage\Model\Model::createEntryDiscriminator()` method to set entry filter:

```php

use PhpDataMiner\Storage\Model\Model;
use PhpDataMiner\Storage\Model\ModelInterface;

class InvoiceModel extends Model implements ModelInterface
{
    public static function createEntryDiscriminator($invoice): DiscriminatorInterface
    {
        return new Discriminator([
            $invoice->getClient() ? $entity->getClient()->getId() : null,
            $invoice->getId(),
        ]);
    }
}
```

### Versioning

Version numbering is done following the [semantic versioning](https://semver.org/) 

### TODO

* Natural language toolkit (NLTK) support 
* Feature vectors for properties

### Testing

```bash 
$ make tests [test_name]
```
