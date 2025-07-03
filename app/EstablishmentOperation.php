<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class EstablishmentOperation extends Model
{
    /**
     * @var string
     */
    protected $table = 'establishment_operation';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'establishment_id', 'operation_id'
    ];
}
