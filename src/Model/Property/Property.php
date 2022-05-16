<?php

namespace PhpDataMiner\Model\Property;

use PhpDataMiner\Model\Property\Transformer\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Property extends AbstractProperty
{
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'transformer' => new CallbackTransformer(
                function ($value) {
                    return $value;
                }, function ($value) {
                return $value;
            }
            ),
        ]);
    }

    public function supports(string $type, $entity = null): bool
    {
        return true;
    }

    public function getTypeName(): ?string
    {
        return 'default';
    }

}
