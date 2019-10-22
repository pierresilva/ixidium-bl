<?php

/**
 * @param string $guard
 *
 * @return string|null
 */

use App\Modules\Administration\Models\AdmiProduct;
use App\Modules\Administration\Models\AdmiParameter;
use App\Modules\Administration\Models\AdmiParameterDetail;
use Carbon\Carbon;

if (!function_exists('getModelForGuard')) {
  function getModelForGuard(string $guard)
  {
    return collect(config('auth.guards'))
      ->map(function ($guard) {
        return config("auth.providers.{$guard['provider']}.model");
      })->get($guard);
  }
}

/**
 * Retorna true si el usuario tiene asignadas las acciones pertinentes
 *
 * @param array $actions acciones permitidas para el componente
 *
 * @return mixed true | httpResponse
 */
if (!function_exists('user_can')) {
  function user_can(array $actions)
  {
    if (env('BASELINE_GENERATING_DOCUMENTATION')) {
      return true;
    };

    if (!auth()->user()) {
      auth_response(401, 'No autenticado!');
    }

    $token = auth()->payload();

    if (!$token) {
      auth_response(401, 'No autorizado!');
    }

    $user = \App\User::whereTsId($token->toArray()['id'])->first()->toArray();

    if (!$user) {
      auth_response(401, 'No autorizado!');
    }

    // dd($user['actions']);

    if ($user['actions']) {
      foreach (json_decode($user['actions']) as $tokenAction) {
        foreach ($actions as $action) {
          if ($tokenAction === $action) {
            return true;
          }
        }
      }
    }

    auth_response(401, 'No cuenta con permisos para realizar esta acción!');
  }
}

/**
 * Retorna true si el usuario tiene asignados los perfiles pertinentes
 *
 * @param array $profiles perfiles permitidos para el componente
 *
 * @return mixed true | httpResponse
 */
if (!function_exists('user_is')) {
  function user_is(array $profiles)
  {
    if (env('BASELINE_GENERATING_DOCUMENTATION')) {
      return true;
    };

    if (!auth()->user()) {
      auth_response(401, 'No autenticado!');
    }

    $token = auth()->payload();

    if (!$token) {
      auth_response(401, 'No autorizado!');
    }

    $user = \App\User::whereTsId($token->toArray()['id'])->first()->toArray();

    if (!$user) {
      auth_response(401, 'No autorizado!');
    }

    if ($user['profiles']) {
      foreach (json_decode($user['profiles']) as $tokenProfile) {
        foreach ($profiles as $profile) {
          if ($tokenProfile === $profile) {
            return true;
          }
        }
      }
    }

    auth_response(401, 'No cuenta con el perfil realizar esta acción!');
  }
}

function auth_response($code, $message)
{
  http_response_code($code);
  header('Content-Type: application/json');
  echo json_encode(['message' => $message]);
  die();
}

/**
 * Retorna el nombre de usuario
 *
 * @return mixed string | null
 */
if (!function_exists('get_user_name')) {
  function get_user_name()
  {
    if (!auth()->user()) {
      //auth_response(401, 'No autenticado!');
      return 'online';
    }

    $token = auth()->payload();

    if (!$token) {
      auth_response(401, 'No autorizado!');
    }

    $user = \App\User::whereTsId($token->toArray()['id'])->first()->toArray();

    if (!$user) {
      auth_response(401, 'No autorizado!');
    }

    if ($user['name']) {
      return $user['name'];
    }

    return null;
  }
}

/**
 * Retorna un objeto con la información del usuario
 *
 * @return mixed Object | null
 */
if (!function_exists('decode_token')) {
  function decode_token()
  {
    if (!auth()->user()) {
      return null;
    }

    $payload = auth()->payload();

    if ($payload) {
      return $payload->toArray();
    }

    return null;
  }
}


/**
 * Retorna el ID del auxiliar o asesor logueado
 *
 * @return mixed Object | null
 */
if (!function_exists('get_consultant_id_by_user')) {
  function get_consultant_id_by_user()
  {
    if (!auth()->user()) {
      auth_response(401, 'No autenticado!');
    }

    $token = auth()->payload();

    if (!$token) {
      auth_response(401, 'No autorizado!');
    }

    $dataUser = \App\User::whereTsId($token->toArray()['id'])->first()->toArray();

    $identification = $dataUser['identification'];
    $consultantID = null;

    $dataConsultant = \App\Modules\Quotations\Models\QuotConsultant::whereHas('thirdParty', function ($q) use ($identification) {
      $q->where('identification_number', $identification);
    })->select('id')->first();

    if ($dataConsultant) {
      $consultantID = $dataConsultant->id;
    }
    return $consultantID;
  }
}

/**
 * Retorna el objeto del usuario
 *
 * @return mixed User | false
 */
if (!function_exists('user_logged')) {
  function user_logged()
  {
    return auth()->user();
  }
}

if (!function_exists('get_setting')) {
  /**
   * get_setting()
   *
   * Retorna la informacion de una configuracion a partir de un código enviado por parámetro
   *
   * @param $codeSetting
   * @return array
   */
  function get_setting($codeSetting): array
  {
    $setting = \App\Modules\Administration\Models\AdmiSetting::where('code', $codeSetting)->where('status', 'active')->get(['id', 'setting', 'value', 'description'])->toArray();
    return $setting[0];
  }
}

if (!function_exists('get_category_sigas')) {
  /**
   * Retorna la categoria de de una persona de acuerdo a la Categoria de Recreaweb (A,B,C,D,E,F,G,H,I,O,P)
   *
   * @param string $categoryRecreaweb
   * @return string $categorySigas
   */
  function get_category_sigas($categoryRecreaweb)
  {

    $categoriesRecreaweb = [];

    $details = AdmiParameter::whereDetailsActives('categorias');

    foreach ($details as $detail) {
      $categoriesRecreaweb[] = $detail['code'];
    }

    $caregorySigas = $categoryRecreaweb;


    if (in_array($categoryRecreaweb, $categoriesRecreaweb)) {
      if ($categoryRecreaweb !== 'A' and $categoryRecreaweb !== 'B' and $categoryRecreaweb !== 'C') {
        $caregorySigas = 'P';
      }
    } else {
      $caregorySigas = "Error";
    }

    return $caregorySigas;
  }
}

//TODO mejorar proceso para buscar la homolagacion
if (!function_exists('get_category_recreaweb')) {
    /**
     * Retorna la categoria de de una persona de acuerdo a la Categoria de Sigas (A,B,C,P)
     *
     * @param string $categorySigas
     * @return string $categoryRecreaweb
     */
    function get_category_recreaweb ($categorySigas)
    {

        $categoriesSigas = ['A','B','C','P'];

         $categoryRecreaweb = $categorySigas;

        if(in_array($categorySigas, $categoriesSigas)){
            if ($categorySigas !== 'A' and $categorySigas !== 'B' and $categorySigas !== 'C') {
                $categoryRecreaweb = get_detail_parameters('categorias', $categorySigas )[0]['code'];
            }
        }else{
            $categoryRecreaweb = "Error";
        }

        return $categoryRecreaweb;
    }
}

if (!function_exists('cast_birth_date')) {
    /**
     * Retorna la fecha de nacimiento de formato SIGAS a formato Y-m-d para recreaweb
     *
     * @param string $datetimeSigas
     * @return string $birthdate
     */
    function cast_birth_date ($datetimeSigas)
    {
        $temp = explode(' ', $datetimeSigas);

        $date = explode('/', $temp[0]);

        $birthdate = $date[2].'-'.$date[1].'-'.$date[0];

        return $birthdate;
    }
}

if (!function_exists('get_parameter')) {
  function get_parameter($code): array
  {

    $parameter = App\Modules\Administration\Models\AdmiParameter::where('code', '=', $code)
      ->get(['id', 'code', 'name']);

    return $parameter->toArray();
  }
}


if (!function_exists('get_detail_parameters')) {
  /**
   * Retorna un detalle de parametro desde un objeto
   *
   * @param $codeParameter
   * @param $code
   * @return array
   */
  function get_detail_parameters($codeParameter, $code)
  {

    $parameter = \App\Modules\Administration\Models\AdmiParameter::where('code', '=', $codeParameter)->get(['id'])->toArray();

    if (!$parameter) {
      return response()->json([
        'message' => 'Parametro ' . $codeParameter . '->' . $code . ' detalle no existe!'
      ], 400);
    }

    $detail = \App\Modules\Administration\Models\AdmiParameterDetail::where('parameter_id', '=', $parameter[0]['id'])
      ->where('code', '=', $code)
      ->get(['id', 'name', 'code', 'data'])->toArray();

    if (!$detail) {
      return response()->json([
        'message' => 'Parametro ' . $code . ' no existe!'
      ], 400);
    }

    return $detail;
  }

}

if (!function_exists('get_programs_super')) {
  /**
   * Retorna un programa de la super según su serviceId o codeXml
   *
   * @param string $field
   * @param $value
   * @return array
   */
  function get_programs_super($field, $value): array
  {
    $data = [];

    if ($field !== 'services_id' and $field !== 'code_xml') {
      $data[0] = 'invalid field';
      return $data;
    }

    $result = \App\Modules\Reports\Models\RepoServiceProgram::where($field, '=', $value)
      ->get(['id', 'code_program', 'program', 'observation']);

    $data = $result->toArray();

    return $data;
  }
}

if (!function_exists('get_parameters_detail')) {

  /**
   * Retorna un detalle de parametro desde un objeto
   *
   * @param $parameterCode
   * @return array
   */
  function get_parameters_detail(string $parameterCode): array
  {
    $parameterDetail = [];
    $parameter = \App\Modules\Administration\Models\AdmiParameter::where('code', $parameterCode)->where('status', 'active')->first();

    if ($parameter) {
      $parameterDetail = $parameter->parameterDetails()->where('status', 'active')->get()->toArray();
    }

    return $parameterDetail;
  }

}


if (!function_exists('object_to_array')) {
  /**
   * Retorna un array desde un objeto
   *
   * @param $data object
   * @return array
   */
  function object_to_array($data)
  {
    if (is_object($data)) {
      $data = get_object_vars($data);
    }

    if (is_array($data)) {
      return array_map(__FUNCTION__, $data);
    } else {
      return $data;
    }
  }
}

if (!function_exists('save_base64_image')) {
  /**
   * Sabe base64 image to public path
   *
   * @param $base64
   * @param $path
   *
   * @return bool
   */
  function save_base64_image($base64, $path)
  {
    if (preg_match('/data:image/', $base64)) {
      // get the mimetype
      preg_match('/data:image\/(?<mime>.*?)\;/', $base64, $groups);
      $mimeType = $groups['mime'];
      // Generating a random filename
      $filename = uniqid();

      $filepath = "uploads/" . $path . "/$filename.$mimeType";

      $image = \Image::make($base64)
        ->encode($mimeType, 100)
        ->save(public_path($filepath));

      if ($image) {
        return $filepath;
      }
    }

    return false;
  }
}

if (!function_exists('get_data_asessor_by_id')) {
  /**
   * Retorna un detalle de parametro desde un objeto
   *
   * @param $codeParameter
   * @param $code
   * @return array
   */
  function get_data_asessor_by_id($consultantID): array
  {
    $asessorQuotation = \App\Modules\Quotations\Models\QuotConsultants::with('thirdParty.emailPrincipal', 'thirdParty.phonePrincipal', 'thirdParty.addressPrincipal')->consultantByTypeConsultant('asesor')->where('status', '=', 'active')->where('id', '=', $consultantID)->get();
    return $asessorQuotation->toArray();
  }
}

if (!function_exists('get_data_auxiliar_by_id')) {
  /**
   * Retorna un detalle de parametro desde un objeto
   *
   * @param $codeParameter
   * @param $code
   * @return array
   */
  function get_data_auxiliar_by_id($consultantID): array
  {
    $asessorQuotation = \App\Modules\Quotations\Models\QuotConsultants::with('thirdParty.emailPrincipal', 'thirdParty.phonePrincipal', 'thirdParty.addressPrincipal')->consultantByTypeConsultant('auxiliar')->where('status', '=', 'active')->where('id', '=', $consultantID)->get();
    return $asessorQuotation->toArray();
  }
}

if (!function_exists('get_data_consultant_by_id')) {
  /**
   * Retorna un detalle de parametro desde un objeto
   *
   * @param $codeParameter
   * @param $code
   * @return array
   */
  function get_data_consultant_by_id($consultantID = null, $locationId = null): array
  {
    $consultant = \App\Modules\Quotations\Models\QuotConsultants::with(
      'thirdParty.emailPrincipal',
      'thirdParty.phonePrincipal',
      'thirdParty.addressPrincipal',
      'typeConsultant'
    )
      ->where('status', '=', 'active');

    if ($consultantID) {
      $consultant->where('id', '=', $consultantID);
    }

    if ($locationId) {
      $consultant->where('location_id', '=', $locationId);
    }

    return $consultant->get()->toArray();
  }
}

if (!function_exists('get_tax_product_by_id')) {
  /**
   * Retorna la base y el impuesto de un producto especifico.
   *
   * @param $productID
   * @param int $saleValue
   * @return array
   */
  function get_tax_product_by_id($productID, $saleValue = 0): array
  {
    try {

      $response = [
        'message' => 'Impuesto calculado con éxito',
        'success' => true,
        'data' => [
          'base' => 0,
          'tax' => 0,
          'taxName' => '',
          'taxCode' => '',
          'saleValue' => 0,
        ]
      ];

      $product = \App\Modules\Administration\Models\AdmiProduct::with(['productTaxes', 'productTaxes.tax', 'prices' => function ($query) {

        // Obtiene el precio vigente
        return $query->whereFullDate()->orderBy('value', 'ASC');
      }])->where('id', $productID)->first()->toArray();

      if (count($product['product_taxes']) === 0) {
        $response['message'] = 'El producto no tiene parametrizado ningún impuesto';
        return $response;
      }

      if (count($product['product_taxes']) > 1) {
        throw new \Exception('El producto tiene más de un impuesto');
      }

      if ($saleValue === 0 && count($product['prices']) === 0) {
        $response['message'] = 'El producto no tiene un precio vigente.';
        return $response;
      }

      if ($saleValue === 0) {
        $saleValue = $product['prices'][0]['value'];
      }

      $tax = $product['product_taxes'][0]['value'];

      $base = $saleValue / (1 + ($tax / 100));
      $taxValue = $saleValue - $base;

      $response['data']['base'] = round($base, 2);
      $response['data']['tax'] = round($taxValue, 2);
      $response['data']['taxName'] = $product['product_taxes'][0]['tax']['tax'] . ' - ' . $product['product_taxes'][0]['value'];
      $response['data']['taxCode'] = $product['product_taxes'][0]['tax']['code'];
      $response['data']['saleValue'] = $saleValue;
    } catch (\Exception $e) {
      $response['message'] = $e->getMessage();
      $response['success'] = false;
    }

    return $response;
  }
}

if (!function_exists('generate_file_ftp_by_name')) {
  function generate_file_ftp_by_name($pathOrigin, $pathFinal, $nameFile): array
  {
    try {
      $return = [
        'success' => true,
        'message' => '',
      ];

      $myfile = new \Illuminate\Http\File($pathOrigin);

      if (file_exists($pathOrigin)) {
        \Storage::disk('sftp')->putFileAs($pathFinal, $myfile, $nameFile);
      } else {
        throw new \Exception('No existe archivo vinculado a la cotización');
      }
    } catch (Exception $e) {
      $return['success'] = false;
      $return['message'] = "No hay conectividad con el servidor FTP";
    }
    return $return;
  }
}

if (!function_exists('comma_separated_string_to_array')) {
  function comma_separated_string_to_array(string $string): array
  {

    $array = explode(',', $string);

    return $array;
  }
}

if (!function_exists('get_name_day_by_index')) {
  function get_name_day_by_index(int $index): string
  {

    $days = [
      'domingo',
      'lunes',
      'martes',
      'miercoles',
      'jueves',
      'viernes',
      'sabado'
    ];

    return $days[$index];
  }
}
/**
 *
 */
if (!function_exists('get_readable_date')) {
  function get_readable_date($date): string
  {
    if (mb_strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      setlocale(LC_TIME, 'spanish');
    } else {
      setlocale(LC_TIME, 'es_ES.UTF-8');
    }

    return strftime("%A, %d de %B del %Y", strtotime($date)); // date('D, j \d\e F \d\e\l Y', strtotime($date));
  }
}

if (!function_exists('get_readable_datetime')) {
  function get_readable_datetime($date): string
  {
    if (mb_strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      setlocale(LC_TIME, 'spanish');
    } else {
      setlocale(LC_TIME, 'es_ES.UTF-8');
    }

    return strftime("%A, %d de %B del %Y, %H:%M %p", strtotime($date)); // date('D, j \d\e F \d\e\l Y', strtotime($date));
  }
}
if (!function_exists('is_true')) {
  function is_true($val, $return_null = false)
  {
    $boolval = (is_string($val) ? filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool)$val);
    return ($boolval === null && !$return_null ? false : $boolval);
  }
}

if (!function_exists('utf8_string_array_encode')) {
  function utf8_string_array_encode(&$array)
  {
    $func = function (&$value, &$key) {
      if (is_string($value)) {
        $value = utf8_encode($value);
      }
      if (is_string($key)) {
        $key = utf8_encode($key);
      }
      if (is_array($value)) {
        utf8_string_array_encode($value);
      }
    };
    array_walk($array, $func);
    return $array;
  }
}

if (!function_exists('get_data_program_product')) {
  function get_data_program_product($codeProduct, $codeCategory, $date)
  {

    $data = ['success' => true, 'message' => 'Se consulto con exito', 'dataResponse' => []];

    try {
      //Se consulta la información del producto
      $categoryID = get_detail_parameters('categorias', $codeCategory)[0];
      $product = AdmiProduct::with([

        'prices' => function ($query) {
          // Obtiene el precio vigente
          return $query->whereFullDate()->orderBy('value', 'ASC');
        }
        , 'programsProduct' => function ($query) use ($categoryID, $date) {
          $query->whereHas('program', function ($query2) use ($date) {
            $query2->where('start_date', '<=', $date);
            $query2->where('end_date', '>=', $date);
            $query2->where('status', 'active');
          });
          $query->where('category_id', $categoryID['id']);

          $query->with('program');
          $query->with('category');
        }

      ])->where('code', $codeProduct)->get()->toArray();
      $product = $product[0];

      if (count($product['prices']) === 0) {
        throw new \Exception('El producto no tiene un precio vigente, por favor revise la parametrización');
      }

      if (count($product['programs_product']) === 0) {
        throw new \Exception('El producto no tiene vinculado un programa, por favor revise la parametrización');
      }

      $data['dataResponse'] = [
        'product' => $product['product'],
        'program_product_id' => $product['programs_product'][0]['id'],
        'program_description' => $product['programs_product'][0]['program']['description'],
        'category' => $product['programs_product'][0]['category']['code'],
        'value_without_discount' => $product['prices'][0]['value'],
        'subsidy_value' => $product['programs_product'][0]['subsidy_value'],
        'value' => ($product['prices'][0]['value'] - $product['programs_product'][0]['subsidy_value'])
      ];

    } catch (\Exception $e) {
      $data['success'] = false;
      $data['message'] = $e->getMessage();
    }

    return $data;
  }
}

if (!function_exists('add_days_to_date')) {
  function add_days_to_date($date, $days)
  {

    $dateCarbon = Carbon::parse($date);
    $dateCarbon->addDay($days);
    return $dateCarbon->format('Y-m-d');
  }
}

if (!function_exists('get_difference_days_between_dates')) {
  function get_difference_days_between_dates($start_date, $end_date)
  {
    $startDateCarbon = Carbon::parse($start_date);
    $endDate = Carbon::parse($end_date);

    return $endDate->diffInDays($startDateCarbon);
  }
}
