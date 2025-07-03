<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class EstablishmentCurator extends Model
{
    /**
     * @var string
     */
    protected $table = 'establishment_curator';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'curator_id', 'establishment_id'
    ];
}
