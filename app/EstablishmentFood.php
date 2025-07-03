<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class EstablishmentFood extends Model
{
    /**
     * @var string
     */
    protected $table = 'establishment_food';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'establishment_id', 'food_id'
    ];
}
