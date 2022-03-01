<?php

namespace PhpDataMinerNormalizer\Tokenizer\Token;

use PhpDataMinerHelpers\OptionsBuilderTrait;

/**
 * Description of TokenTrait
 *
 * @author Andres Pajo
 */
trait TokenTrait
{
    use OptionsBuilderTrait;

    /**
     * @var string|null
     */
    protected ?string $text = null;

    /**
     * @var string|null
     */
    protected ?string $lemma = null;

    /**
     * @var string|null
     */
    protected ?string $root = null;

    /**
     * @var string|null
     */
    protected ?string $form = null;

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getForm(): ?string
    {
        return $this->form;
    }

    public function setForm(?string $form): void
    {
        $this->form = $form;
    }

    public function getRoot(): ?string
    {
        return $this->root;
    }

    public function setRoot(?string $root): void
    {
        $this->root = $root;
    }

    public function getLemma(): ?string
    {
        return $this->lemma;
    }

    public function setLemma(?string $lemma): void
    {
        $this->lemma = $lemma;
    }

    public function getOption(string $name)
    {
        return $this->options[$name];
    }
}
