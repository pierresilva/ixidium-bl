<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
  protected $fillable = [
    'order_id',
    'invoice_date',
    'due_date',
    'tax',
    'shipping',
    'amount_due',
    'amount_paid'
  ];
}
