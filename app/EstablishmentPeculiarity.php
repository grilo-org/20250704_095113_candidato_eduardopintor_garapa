<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class EstablishmentPeculiarity extends Model
{
    /**
     * @var string
     */
    protected $table = 'establishment_peculiarity';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'establishment_id', 'peculiarity_id'
    ];
}
