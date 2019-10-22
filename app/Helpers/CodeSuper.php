<?php

namespace App\Helpers;
use App\Modules\Administration\Models\AdmiCompensationOffice;
use App\Modules\Reports\Models\RepoCodeSuper;
use App\Modules\Reports\Models\RepoHomologationSuper;

/**
 * @author Andres Felipe Lara <anslara89@gmail.com>
 * @package Reports
 * @version 1.0.0 <2019-01-10>
 */

class CodeSuper
{

    private static $codes = [];
    private static $success = true;
    private static $message = 'Códigos recuperados con éxito';

    public static function result(){
       return self::$codes;
    }

    public static function message(){
        return self::$message;
    }

    public static function success(){
        return self::$success;
    }

    /**
     * Retorna 1 registro del código Padre de las tablas de referencia de la super
     *
     * @param string $code código del padre
     * @return CodeSuper
     */

    public static function getParentCodeSuper ($code)
    {

        try {
            $parent = RepoCodeSuper::where('code', '=', $code)
                ->where('code', '=', $code)
                ->where('status', 'active')
                ->whereNull('parent_id')
                ->get(['id', 'code', 'name', 'status']);

            self::$codes = $parent->toArray()[0];

        } catch (\Exception $e) {
            self::$message = $e->getMessage();
            self::$success = false;
        }

        return new self();
    }


    /**
     * Retorna 1 registro del código del Hijo de las tablas de referencia de la super
     *
     * @param string $codeParent código del padre
     * @param string $codeChild codigo a buscar
     * @return CodeSuper
     */

    public static function getChildrenCodeSuper($codeParent, $codeChild)
    {
        try {
            $parent = RepoCodeSuper::where('code', '=', $codeParent)->whereNull('parent_id')->get(['id'])->toArray();

            $detail = RepoCodeSuper::where('parent_id', '=', $parent[0]['id'])
                ->where('code_child', '=', $codeChild)
                ->where('status', 'active')
                ->whereNotNull('parent_id')
                ->get(['id', 'code', 'name', 'code_child', 'description_child', 'status']);

            static::$codes = $detail->toArray()[0];

        } catch (\Exception $e) {
            self::$message = $e->getMessage();
            self::$success = false;
        }

       return new self();
    }

    /**
     * Retorna todos los registros del código del Hijo de las tablas de referencia de la super
     *
     * @param string $codeParent código del padre
     * @return CodeSuper
     */

    public static function getAllChildrenCodeSuper($codeParent)
    {

        try {
            $parent = RepoCodeSuper::where('code', '=', $codeParent)->whereNull('parent_id')->get(['id'])->toArray();

            $detail = RepoCodeSuper::where('parent_id', '=', $parent[0]['id'])
                ->where('status', 'active')
                ->get(['id', 'code', 'name', 'code_child', 'description_child', 'status']);

            self::$codes = $detail->toArray();

        } catch (\Exception $e) {
            self::$message = $e->getMessage();
            self::$success = false;
        }

        return new self();
    }


    /**
     * Retorna el codigo del rango de edad
     *
     * @param string $ageSigas Edad persona en formato Servicio sigas '46,96'
     * @return string $range
     */

    public static function calculateRangeAge($ageSigas = '-1'): string
    {

       $age  = round((float) $ageSigas);

        switch ($age) {

            case ($age > 0 and $age < 1):
                $range = "2";
                break;

            case ($age >= 1 and $age <= 5):
                $range = "3";
                break;

            case ($age >= 6 and $age <= 10):
                $range = "4";
                break;

            case ($age >= 11 and $age <= 15):
                $range = "5";
                break;

            case ($age >= 16 and $age <= 18):
                $range = "6";
                break;

            case ($age >= 19 and $age <= 23):
                $range = "7";
                break;

            case ($age >= 24 and $age <= 45):
                $range = "8";
                break;

            case ($age >= 46 and $age <= 60):
                $range = "9";
                break;

            case ($age > 60):
                $range = "10";
                break;
            default: $range = "1";

        }

        return $range;
    }

    /**
     * Retorna el codigo homologado de la super desde parametro detalle
     *
     * @param string $codeParameter  código del parametro
     * @param string $code
     * @return string
     */

    public static function getHomologationCode ($codeParameter, $code)
    {
        /*
        $child = self::getChildrenCodeSuper($codeSuperParent, $code)->result();
        $id = RepoHomologationSuper::where('repo_code_super_id', $child['id'])->first()->parameter_detail_id;
     */

        $child = get_detail_parameters($codeParameter, $code)[0];
        $id = RepoHomologationSuper::where('parameter_detail_id', $child['id'])->first()->repo_code_super_id;
        $codeHomologation = RepoCodeSuper::find($id)->code_child;


        return $codeHomologation;

    }

    /**
     * Retorna los datos de la caja de compensacion por codigo
     *
     * @param string $code
     * @return string
     */

    public static function getCompensationOfficeByCode ($code)
    {

        $row = AdmiCompensationOffice::where('code', $code)->first();

        if (!$row) {
            return 'Codigo ' . $code . '  no existe!';
        }

        return $row->toArray();

    }


}