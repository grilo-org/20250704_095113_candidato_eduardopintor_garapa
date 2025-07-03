<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    /**
     * @var string
     */
    protected $table = 'food';

    /**
     * @var array
     */
    protected $fillable = [
        'establishment_id', 'food_name', 'firebase_id'
    ];

    /**
     * @var bool - Disabled timestamps columns
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function establishment() {
        return $this->belongsTo(\GarAppa\Establishment::class);
    }
}
