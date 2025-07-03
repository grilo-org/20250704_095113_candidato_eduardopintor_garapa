<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * @var string
     */
    protected $table = 'city';

    /**
     * @var bool - Disabled timestamps columns
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'state_id'
    ];
}
