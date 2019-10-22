<?php


namespace App\Helpers;
use Carbon\Carbon;
/**
 * Class to validate date
 *
 * @author Alexis Plaza <alexisplaza1996@gmail.com>
 */
class ValidateDate
{
    public function __construct()
    {

    }

    /**
     * Función para retornar y validar una fecha
     * @param $date
     * @return string|static
     */
    public static function convertStringToDate($date)
    {
        $date_formated = "";
        if (($date)) {
            $pattern = "/^(0?[1-9]|[12][0-9]|3[01])[\/|-](0?[1-9]|[1][012])[\/|-]((19|20)?[0-9]{2})$/";
            if (preg_match($pattern, $date)) {
                $values = preg_split("[\/|-]", $date);
                if (checkdate($values[1], $values[0], $values[2])) {
                    $date_formated = Carbon::createFromFormat('d/m/Y', $date);
                }
            }
        }
        return $date_formated;
    }
}