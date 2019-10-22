<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigasCouchDB extends Model
{
  //
  protected $connection = 'couchdb';

  protected $fillable = [
    'id',
    'document_type',
    'first_name',
    'second_name',
    'fist_surname',
    'second_surname',
    'birthday',
    'gender',
    'grace_period',
    'category',
    'contributor_id'
  ];

  protected $dates = [
    'created_at',
    'updated_at'
  ];

  public function contributor()
  {
    return $this->hasOne('App\SigasCouchDB', 'id', 'contributor_id');
  }

  public function beneficiaries()
  {
    return $this->hasMany('App\SigasCouchDB', 'contributor_id', 'id');
  }
}
