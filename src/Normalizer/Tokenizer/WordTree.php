<?php

namespace PhpDataMiner\Normalizer\Tokenizer;

use PhpDataMiner\Normalizer\Tokenizer\Token\Cluster;
use PhpDataMiner\Normalizer\Tokenizer\Token\Token;
use PhpDataMiner\Normalizer\Tokenizer\Token\TokenInterface;

/**
 * Description of Word
 *
 * @author Andres Pajo
 */
class WordTree extends Word
{
    const TOKENIZERS = [
        Row::class,
        Sentence::class,
        Column::class,
        Word::class,
    ];

    public function tokenize(string $text): array
    {
        /** @var TokenizerInterface[] $tokenizers */
        $tokenizers = self::TOKENIZERS;

        $result = new Cluster([$text]);
        $this->_tokenize($tokenizers, $result);

        return [$result];
    }

    /**
     * @param array $tokenizers
     * @param TokenInterface|Cluster $parent
     * @return mixed
     */
    protected function _tokenize(array $tokenizers, Cluster &$parent)
    {
        $tokenizer = $tokenizers ? $tokenizers[0] : null;

        foreach ($parent->getTokens() as $i => $token) {
            if (!$tokenizer) {
                $token = new Token($token, [
                    'index' => [
                        ...$parent->getOption('index'),
                        $i
                    ]
                ], $parent);

                $parent->setToken($i, $token);

                continue;
            }

            $items = (new $tokenizer())->tokenize($token);

            $cluster = new Cluster($items, ['index' => [
                ...$parent->getOption('index'),
                $i
            ]], $parent);

            $this->_tokenize( array_slice($tokenizers, 1), $cluster);

            $parent->setToken($i, $cluster);
        }
    }

    protected function clusterize (string $tokenizer, string $source, array $options = []): Cluster
    {
        /** @var TokenizerInterface $tokenizer */
        $tokenizer = new $tokenizer();
        $items = $tokenizer->tokenize($source);

        return new Cluster($items, $options);
    }
}
