<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class EstablishmentType extends Model
{
    /**
     * @var string
     */
    protected $table = 'establishment_type';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'establishment_id', 'type_id'
    ];
}
