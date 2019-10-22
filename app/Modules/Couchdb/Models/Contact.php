<?php

namespace App\Modules\Couchdb\Models;

use pierresilva\CouchDB\Eloquent\Model as Eloquent;
use pierresilva\CouchDB\Eloquent\SoftDeletes;

class Contact extends Eloquent
{

  use SoftDeletes;

  protected $connection = 'couchdb';

  protected $collection = 'books_collection';

  protected $dates = [
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  protected $fillable = [
    'name',
    'address',
    'phone'
  ];

  //

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
