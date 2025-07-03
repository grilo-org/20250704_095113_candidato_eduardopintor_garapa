<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class EstablishmentCuisine extends Model
{
    /**
     * @var string
     */
    protected $table = 'establishment_cuisine';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'establishment_id', 'cuisine_id'
    ];
}
