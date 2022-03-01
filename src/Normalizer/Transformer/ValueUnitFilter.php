<?php

namespace PhpDataMiner\Normalizer\Transformer;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ValueUnitFilter
 *
 * @author Andres Pajo
 */
abstract class ValueUnitFilter extends RegexFilter
{
    abstract protected function getUnitSamples (string $locale = null): array;

    function __construct (array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);

        parent::__construct(
            ['/(\d*\.?\d+)\s?(' . implode('|', $this->getUnitSamples($this->options['locale'])) . '+)/' => [$this, '_transform']],
            $options
        );
    }


    public function transform(array &$samples) : void
    {
        if (empty($this->patterns)) {
            return;
        }

        $this->filter($samples);
    }

    protected function filter(array &$samples) : void
    {
        foreach ($samples as $key => $value) {
            foreach ($this->patterns as $pattern => $replace) {
                $samples[$key] = preg_replace_callback($pattern, $replace, $value);
            }
        }
    }

    protected function _transform($groups)
    {
        $result = [
            $groups[1],
            strtolower($groups[2]),
        ];

        return implode('', $result);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'locale' => 'et_EE'
        ]);
    }

    /**
     * Generates text based long and short month names (in locale)
     *
     * @param string|null $locale
     * @return array
     */
    protected function transformDateComponents (array $resultParts): ?array
    {
        $resultParts[1] = $this->resolveMonth($resultParts[1]);

        return $resultParts;
    }

    protected function resolveMonth($month) : ?int
    {
        $months = $this->getLocaleMonthNames();

        if (is_numeric($month)) {
            return $month;
        }

        foreach ([0, 1] as $nameType) {
            if (($index = array_search($month, array_column($months, $nameType))) !== false) {
                return $index + 1;
            }
        }

        //  predict by similarity
        uasort($months, function ($a, $b) use ($month, $months) {
            $similarity = 0;

            foreach ([0, 1] as $nameType) {
                $_Asim = similar_text(
                    $a[$nameType],
                    $month
                );
                $_Bsim = similar_text(
                    $a[$nameType],
                    $month
                );
                $_sim = $_Asim > $_Bsim ? $_Asim : $_Bsim;

                $similarity = $_sim > $similarity ? $_sim : $similarity;
            }

            return $similarity;
        });

        reset($months);

        return key($months);
    }

    /**
     * Generates text based long and short month names (in locale)
     *
     * @param string|null $locale
     * @return array
     */
    protected function getLocaleMonthNames(string $locale = null) : array
    {
        $currentLocale = setlocale(LC_ALL, 0);

        $months = array_map(function ($i) use ($locale)  {
            setlocale(LC_ALL, $locale ?: $this->options['locale']);
            return [
                mb_strtolower(utf8_encode(strftime('%B', mktime(0, 0, 0, $i, 1)))),
                mb_strtolower(utf8_encode(strftime('%h', mktime(0, 0, 0, $i, 1))))
            ];
        }, range(1, 12));

        setlocale(LC_ALL, $currentLocale);

        return $months;
    }

}
