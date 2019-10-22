<?php

namespace App\Modules\Reporter\Models;

use Illuminate\Database\Eloquent\Model;

class RepoReport extends Model
{
  //
  // protected $connection = 'sqlsrv';
  protected $table = 'repo_reports';
  protected $primaryKey = 'id';
  protected $fillable = [
    'name',
    'connection',
    'module',
    'description',
    'start_at',
    'end_at',
    'sql',
    'fields',
    'options',
  ];
  protected $dates = [
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  public static function boot()
  {
    parent::boot();
    self::creating(function ($model) {
      //
    });
    self::updating(function ($model) {
      //
    });
    self::deleting(function ($model) {
      //
    });
  }
}
