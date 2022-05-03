<?php

namespace PhpDataMiner\Storage\Summary;

class Summary
{
    /**
     * @var string|null
     */
    private ?string $title = null;

    /**
     * @var array
     */
    protected array $cols = [];


    /**
     * @var array
     */
    protected array $rows = [];

    function __construct(string $title = null)
    {
        $this->title = $title;
    }

    public function setColumns(array $columns)
    {
        $this->cols[] = $columns;
    }

    public function addRow(string $name, array $columns)
    {
        $this->rows[] = [
            $name,
            ...$columns,
        ];
    }
}
