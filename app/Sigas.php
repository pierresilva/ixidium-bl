<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sigas extends Model
{
  //

  protected $fillable = [
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
    return $this->hasOne('App\Sigas', 'id', 'contributor_id');
  }

  public function beneficiaries()
  {
    return $this->hasMany('App\Sigas', 'contributor_id', 'id');
  }
}
