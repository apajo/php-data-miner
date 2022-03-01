<?php

namespace PhpDataMinerNormalizer\Tokenizer\Token;


/**
 * Description of Token
 *
 * @author Andres Pajo
 */
interface TokenInterface
{
    public function getText(): ?string;

    public function getOption(string $name);

    public function getForm(): ?string;

    public function getRoot(): ?string;

    public function getLemma(): ?string;

    /**
     * @return TokenInterface|Cluster|null
     */
    public function getParent(): ?TokenInterface;

    /**
     * @param TokenInterface|null $parent
     * @return mixed
     */
    public function setParent(TokenInterface $parent = null);
}
