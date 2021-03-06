<?php

namespace PhpDataMiner\Model\Property;

use PhpDataMiner\Model\Property\Transformer\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\VarDumper\Caster\Caster;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

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
