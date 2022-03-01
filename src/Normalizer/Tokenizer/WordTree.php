<?php

namespace DataMiner\Normalizer\Tokenizer;

use DataMiner\Normalizer\Tokenizer\Token\Cluster;
use DataMiner\Normalizer\Tokenizer\Token\Token;
use DataMiner\Normalizer\Tokenizer\Token\TokenInterface;

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

//        foreach ($parent->getTokens() as $i => $cluster) {
//            $this->_tokenize( array_slice($tokenizers, 1), $cluster);
//        }
    }

//    protected function processPage (Document $doc, string &$content, array &$meta)
//    {
//        $content = preg_split(self::SEPARATORS['row'], $content);
//
//        foreach ($content as $ri => $_row) {
//            $row = $doc->getContent()->addChild(new Row($ri, $_row));
//
//            $columns = preg_spilt(self::SEPARATORS['column'], $_row);
//
//            foreach ($columns as $ci => $_column) {
//                preg_match_all(self::SEPARATORS['word'], $_column, $matches, PREG_SET_ORDER, 0);
//                $matches = array_column($matches, 0);
//
//                $col = $row->addChild(new Column($ci, $_column));
//                foreach ($matches as $i => $_word) {
//                    $col->addChild(new Word($i, null, $_word));
//                }
//
//            }
//        }
//    }

    protected function clusterize (string $tokenizer, string $source, array $options = []): Cluster
    {
        /** @var TokenizerInterface $tokenizer */
        $tokenizer = new $tokenizer();
        $items = $tokenizer->tokenize($source);

        return new Cluster($items, $options);
    }
}
