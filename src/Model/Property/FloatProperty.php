<?php

namespace DataMiner\Model\Property;

use DataMiner\Model\Property\Transformer\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FloatProperty extends AbstractProperty
{
    public function supports(string $type, $entity = null): bool
    {
        return in_array($type, ['float', 'decimal', 'double']);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'transformer' => new CallbackTransformer(
                function ($value) {
                    return (float)((string)str_replace(',' , '.', (string)$value));
                }, function ($value) {
                    return (float)(string)$value;
                }
            ),
        ]);
    }

    public function getTypeName(): ?string
    {
        return 'float';
    }
}
