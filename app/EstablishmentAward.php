<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class EstablishmentAward extends Model
{
    /**
     * @var string
     */
    protected $table = 'establishment_award';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'establishment_id', 'award_id'
    ];
}
