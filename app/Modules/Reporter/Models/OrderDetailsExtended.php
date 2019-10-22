<?php

namespace App\Modules\Reporter\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetailsExtended extends Model
{
    protected $table = 'Order Details Extended';
    //

    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            //
        });
        self::updating(function ($model) {
            //
        });
        self::deleting(function($model) {
            //
        });
    }
}
