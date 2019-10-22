<?php
namespace App\Modules\Reporter\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Cyberduck\LaravelExcel\Contract\SerialiserInterface;

class ReportSerialiser implements SerialiserInterface
{

  public $columns;

  public function __construct($columns)
  {
    $this->columns = $columns;
  }

  public function getData($data)
  {
    $return = [];
    foreach ($data as $datum) {
      $return[] = $datum;
    }

    return $return;
  }

  public function getHeaderRow()
  {

    $return = [];
    foreach ($this->columns as $column) {
      $return[] = $column['title'];
    }
    return $return;
  }
}
