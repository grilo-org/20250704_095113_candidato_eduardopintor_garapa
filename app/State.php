<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;
use GarAppa\State;
use GarAppa\City;

class State extends Model
{
    /**
     * @var string
     */
    protected $table = 'state';

    /**
     * @var bool - Disabled timestamps columns
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'abbreviation'
    ];
}
