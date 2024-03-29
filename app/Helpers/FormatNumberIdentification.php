<?php

namespace App\Helpers;

/**
 * Class to read file .csv
 *
 * @author Alexis Plaza <alexisplaza1996@gmail.com>
 */
class FormatNumberIdentification
{
    public function __construct()
    {

    }

    /**
     * @param $numberIdentification
     * @param $codeTypeDocument
     * @return string
     */
    function formatNumberIdentification($numberIdentification , $codeTypeDocument){

        $thousandSeparatorIdentification = number_format($numberIdentification, 0, '', '.');

        if($codeTypeDocument === "NIT"){
            $digitVerification = $this->calculateDigitVerification($numberIdentification);
            $thousandSeparatorIdentification .= "-".$digitVerification;
            $formatNumberIdentification = str_pad($thousandSeparatorIdentification, 17, " ", STR_PAD_LEFT);
        }else{
            $formatNumberIdentification = str_pad($thousandSeparatorIdentification, 17, " ", STR_PAD_RIGHT);
        }
        return $formatNumberIdentification;
    }

    /**
     * @param $nit
     * @return bool|int|string
     */
    public function calculateDigitVerification($nit)
    {
        if (!is_numeric($nit)) {
            return false;
        }

        $arr = array(1 => 3, 4 => 17, 7 => 29, 10 => 43, 13 => 59, 2 => 7, 5 => 19,
            8 => 37, 11 => 47, 14 => 67, 3 => 13, 6 => 23, 9 => 41, 12 => 53, 15 => 71);
        $x = 0;
        $y = 0;
        $z = strlen($nit);
        $dv = '';

        for ($i = 0; $i < $z; $i++) {
            $y = substr($nit, $i, 1);
            $x += ($y * $arr[$z - $i]);
        }

        $y = $x % 11;

        if ($y > 1) {
            $dv = 11 - $y;
            return $dv;
        } else {
            $dv = $y;
            return $dv;
        }
    }


}