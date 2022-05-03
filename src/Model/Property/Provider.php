<?php


namespace PhpDataMiner\Model\Property;

use PhpDataMiner\Model\Annotation\Property as PropertyAnnotation;
use Doctrine\Common\Collections\ArrayCollection;
use ReflectionProperty;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Type;

class Provider
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var PropertyInfoExtractor
     */
    protected $propertyInfo;


    function __construct (Registry $registry)
    {
        $this->registry = $registry;

        $phpDocExtractor = new PhpDocExtractor();
        $reflectionExtractor = new ReflectionExtractor();
        $this->propertyInfo = new PropertyInfoExtractor(
            [$reflectionExtractor],
            [$phpDocExtractor, $reflectionExtractor],
        );

    }

    public function getRegistry (): Registry
    {
        return $this->registry;
    }

    public function resolveProperty(ReflectionProperty $reflection, Type $info = null, array $annots = []): ?PropertyInterface
    {
        if (!$info) {
            return $this->registry->getDefaultType();
        }

        $types = [
            $info->getClassName() ?: $info->getBuiltinType()
        ];

        if ($info->getClassName()) {
            $types[] = $info->getClassName();
        }

        if ($info->getBuiltinType()) {
            $types[] = $info->getBuiltinType();
        }

        foreach ($annots as $annot) {
            if (!($annot instanceof PropertyAnnotation)) {
                continue;
            }

            if (!$annot->type) {
                continue;
            }

            array_unshift($types,
                $annot->type
            );
        }

        /** @var PropertyInterface $property */
        foreach ($this->registry->getTypes() as $property) {
            foreach ($types as $type) {
                if (!$property->supports($type)) {
                    continue;
                }

                return $property;
            }
        }

        return null;
    }

    public function getType(string $class, string $propertyPath): ?Type
    {
        $types = $this->propertyInfo->getTypes($class, $propertyPath);

        if (!$types) {
            return null;
        }

        $type = $this->resolvePropertyInfoType($types);

        return $type;
    }


    /**
     * @param Type[] $types
     */
    protected function resolvePropertyInfoType(array $types): ?Type
    {
        $_types = new ArrayCollection($types);

        $types = array_filter((clone $_types)->toArray(), function (Type $a) { return $a->getBuiltinType() !== 'object'; });

        $classes = array_filter((clone $_types)->toArray(), function (Type $a) { return $a->getClassName(); });

        if ($classes) {
            return $classes[0];
        }

        if ($types) {
            return $types[0];
        }

        return null;
    }


}
