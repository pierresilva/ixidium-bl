<?php

namespace App\Modules\Reporter\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'Products';

    protected $primaryKey = 'ProductID';

    protected $fillable = [
        'ProductName',
        'SupplierID',
        'CategoryID',
        'QuantityPerUnit',
        'UnitPrice',
        'UnitsInStock',
        'UnitsOnOrder',
        'ReorderLevel',
        'Discontinued'
    ];

    public function category ()
    {
        return $this->belongsTo(Category::class, 'CategoryID', 'CategoryID');
    }

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
