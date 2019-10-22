<?php

namespace App\Modules\Reporter\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'Categories';

    protected $primaryKey = 'CategoryID';

    protected $fillable = [
        'CategoryName',
        'Description',
        'Picture',
    ];

    public function products ()
    {
        return $this->hasMany(Product::class, 'CategoryID', 'CategoryID');
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
