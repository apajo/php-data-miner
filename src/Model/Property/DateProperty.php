<?php

namespace PhpDataMiner\Model\Property;

use PhpDataMiner\Model\Property\Transformer\CallbackTransformer;
use DateTime;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateProperty extends AbstractProperty
{
    public function getTypeName(): ?string
    {
        return 'date';
    }

    public function supports(string $type, $entity = null): bool
    {
        return in_array(strtolower($type), ['date', 'datetime']) || ($type instanceof DateTime);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'transformer' => new CallbackTransformer(
                function ($value) {
                    $date = DateTime::createFromFormat('d-m-Y', (string)$value);

                    if (!($date instanceof DateTime)) {
                        return null;
                    }

                    return $date;
                },

                function ($value) {
                    if (!$value) {
                        return null;
                    }

                    if (!($value instanceof DateTime)) {
                        return null;
                    }

                    return $value->format('d-m-Y');
                }
            ),
        ));
    }
}
