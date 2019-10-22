<?php

namespace pierresilva\Inventory\Models;

use Baum\Node;

/**
 * Class AdmiLocation.
 */
class Location extends Node
{
    protected $table = 'locations';

    protected $fillable = [
        'name',
    ];

    protected $scoped = ['belongs_to'];

    /**
     * The hasMany stocks relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks()
    {
        return $this->hasMany('pierresilva\Inventory\Models\InventoryStock', 'location_id', 'id');
    }
}
