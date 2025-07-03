<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class EstablishmentSpecialEnvironment extends Model
{
    /**
     * @var string
     */
    protected $table = 'establishment_special_environment';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'establishment_id', 'special_environment_id'
    ];
}
