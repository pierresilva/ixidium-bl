<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Remove data or other key from array
     *
     * @param $data
     * @param string $key
     *
     * @return mixed
     */
    protected function getMeta($data, $key = 'data')
    {
      if (is_array($data)) {
        unset($data[$key]);
      } else if (is_object($data)) {
        unset($data->{$key});
      }

        return $data;
    }

  protected function getData($data, $key = 'data')
  {
    if (is_array($data)) {
      return ($data[$key]);
    } else if (is_object($data)) {
      return ($data->{$key});
    }
  }
}
