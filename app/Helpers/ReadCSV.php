<?php

namespace App\Helpers;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

/**
 * Class to read file .csv
 *
 * @author Alexis Plaza <alexisplaza1996@gmail.com>
 */
class ReadCSV
{
    public function __construct()
    {

    }

    /**
     * @param $filename
     * @param string $delimiter
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function readFile($filename , $delimiter = ',')
    {
        $url_path = public_path($filename);
        $reader = new Csv();
        $reader->setDelimiter($delimiter);
        $spreadsheet = $reader->load($url_path);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        return $sheetData;
    }
}