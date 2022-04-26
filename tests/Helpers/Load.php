<?php

namespace PhpDataMinerTests\Helpers;


class Load
{
    protected $list = [];

    function __construct (string $csv, string $pdfDir, int $limit = null)
    {
        $index = 0;

        if (($handle = fopen($csv, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($limit && $index > $limit) {
                    continue;
                }

                $row = array_combine([
                    'number', 'sum', 'duedate', 'vat', 'reference', 'ponumber', 'received', 'file'
                ], $data);

                $filePath = $pdfDir . '/' . $row['file'];

                if (!is_file($filePath)) {
                    continue;
                }

                $this->list[] = $row;

                $index++;
            }

            fclose($handle);
        }
    }

    public function getList ( )
    {
        return $this->list;
    }

    public function sliceList (int $count)
    {
        $predict = [];
        $train = $this->list;

        for ($i = 0; $i < $count; $i++) {
            $index = array_rand($train);
            $slice = array_slice($train, $index, 1);
            unset($train[$index]);

            array_push($predict, ...$slice);
        }

        return [
            array_values($train),
            array_values($predict),
        ];
    }
}
