<?php

namespace PhpDataMiner\Normalizer\Tokenizer;

/**
 * Description of Word
 *
 * @author Andres Pajo
 */
class NltkToken extends AbstractTokenizer implements TokenizerInterface
{
    const NAMED_ENTITY_PERSON = 'PER';
    const NAMED_ENTITY_ORGANIZATION = 'ORG';
    const NAMED_ENTITY_LOCATION = 'LOC';

    const HOST = 'http://nltk.apajo.ee:5000/estnltk';

    function __construct (array $options = [])
    {
        parent::__construct('', $options);
    }

    /**
     * @param string $text
     * @return array
     */
    public function tokenize($text) : array
    {
        $data = [
            'text' => $text,
        ];

        $ch = curl_init(self::HOST);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);

        curl_close($ch);

        dump (json_decode($result));

        return [];
    }
}
