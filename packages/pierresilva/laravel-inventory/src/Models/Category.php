<?php

namespace pierresilva\Inventory\Models;

use pierresilva\Inventory\Traits\CategoryTrait;
use Baum\Node;

/**
 * Class BlogCategory.
 */
class Category extends Node
{
    use CategoryTrait;

    protected $table = 'categories';

    protected $fillable = [
        'name',
    ];

    protected $scoped = ['belongs_to'];

    /**
     * The hasMany inventories relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventories()
    {
        return $this->hasMany('pierresilva\Inventory\Models\Inventory', 'category_id', 'id');
    }
}
