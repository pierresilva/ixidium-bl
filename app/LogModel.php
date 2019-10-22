<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogModel extends Model
{

  //
  use SoftDeletes;

  protected $table      = 'log_models';
  protected $primaryKey = 'id';
  protected $dates      = [
    'created_at',
    'updated_at',
    'deleted_at'
  ];
  protected $fillable   = [
    'fqn'
  ];

  public function createdBy()
  {
    return $this->belongsTo('\App\User', 'created_by', 'id');
  }

  public function updatedBy()
  {
    return $this->belongsTo('\App\User', 'updated_by', 'id');
  }

  public function deletedBy()
  {
    return $this->belongsTo('\App\User', 'updated_by', 'id');
  }

  public static function boot()
  {
    parent::boot();

    self::creating(function ($model) {
      //
      $model->created_by = auth()->user()->id;
    });
    self::updating(function ($model) {
      //
      $model->updated_by = auth()->user()->id;
    });
    self::deleting(function ($model) {
      //
      $model->deleted_by = auth()->user()->id;
    });
  }

}
