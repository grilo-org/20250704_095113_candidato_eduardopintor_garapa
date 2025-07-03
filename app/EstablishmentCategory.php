<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class EstablishmentCategory extends Model
{
    /**
     * @var string 
     */
    protected $table = 'establishment_category';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'establishment_id', 'category_id'
    ];

}
