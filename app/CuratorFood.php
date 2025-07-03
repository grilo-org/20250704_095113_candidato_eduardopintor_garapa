<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class CuratorFood extends Model
{
    protected $table = 'curator_food';

    protected $fillable = [
        'curator_id', 'foods_ids'
    ];

    protected $casts = [
        'foods_ids' => 'array'
    ];
}
