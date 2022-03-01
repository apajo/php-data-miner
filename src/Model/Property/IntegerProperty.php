<?php

namespace PhpDataMiner\Model\Property;

use PhpDataMiner\Model\Property\Transformer\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntegerProperty extends AbstractProperty
{
    public function supports(string $type, $entity = null): bool
    {
        return in_array($type, ['integer', 'int', 'number']);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'transformer' => new CallbackTransformer(
                function ($value) {
                    return (int)(string)$value;
                }, function ($value) {
                    return (int)(string)$value;
                }
            ),
        ));
    }

    public function getTypeName(): ?string
    {
        return 'int';
    }
}
